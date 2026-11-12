<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaLibraryService
{
    private const IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];

    private const PDF_MIMES = ['application/pdf'];

    private const DOCUMENT_MIMES = [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public function store(UploadedFile $file, ?int $userId = null, string $type = 'document'): int
    {
        $this->validateUpload($file, $type);

        $checksum = hash_file('sha256', $file->getRealPath());
        $extension = Str::lower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');
        $storedName = now()->format('YmdHis').'-'.Str::random(16).'.'.$extension;
        $directory = 'media/'.now()->format('Y/m');
        $path = $file->storeAs($directory, $storedName, 'public');

        [$width, $height] = $this->dimensions($file);

        return DB::table('media')->insertGetId([
            'title_id' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'title_en' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'slug' => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'-'.Str::lower(Str::random(6)),
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'file_name' => $storedName,
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'file_size' => $file->getSize(),
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'checksum' => $checksum,
            'uploaded_by' => $userId,
            'optimization_status' => str_starts_with((string) $file->getMimeType(), 'image/') ? 'pending' : 'not_applicable',
            'status' => 'uploaded',
            'verification_status' => 'pending',
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function replace(int $mediaId, UploadedFile $file, ?int $userId = null): void
    {
        $media = DB::table('media')->where('id', $mediaId)->first();
        abort_unless($media, 404);

        DB::table('media_versions')->insert([
            'media_id' => $mediaId,
            'version_number' => DB::table('media_versions')->where('media_id', $mediaId)->count() + 1,
            'path' => $media->path,
            'checksum' => $media->checksum ?: 'missing',
            'file_size' => $media->file_size ?: $media->size ?: 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->validateUpload($file, $this->typeFromMime((string) $file->getMimeType()));
        $checksum = hash_file('sha256', $file->getRealPath());
        $extension = Str::lower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');
        $storedName = now()->format('YmdHis').'-'.Str::random(16).'.'.$extension;
        $path = $file->storeAs('media/'.now()->format('Y/m'), $storedName, 'public');
        [$width, $height] = $this->dimensions($file);

        DB::table('media')->where('id', $mediaId)->update([
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'file_name' => $storedName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'file_size' => $file->getSize(),
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'checksum' => $checksum,
            'uploaded_by' => $userId,
            'updated_at' => now(),
        ]);
    }

    public function validateUpload(UploadedFile $file, string $type): void
    {
        $mime = (string) $file->getMimeType();
        $extension = Str::lower($file->getClientOriginalExtension());
        $size = (int) $file->getSize();

        if (in_array($extension, ['php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'cmd', 'js', 'svg'], true)) {
            throw ValidationException::withMessages(['file' => 'Executable, script, and SVG uploads are not allowed.']);
        }

        if ($type === 'image' || str_starts_with($mime, 'image/')) {
            if (! in_array($mime, self::IMAGE_MIMES, true) || $size > 5 * 1024 * 1024 || ! @getimagesize($file->getRealPath())) {
                throw ValidationException::withMessages(['file' => 'Invalid image upload.']);
            }

            return;
        }

        if ($type === 'pdf' || $mime === 'application/pdf') {
            $handle = fopen($file->getRealPath(), 'rb');
            $signature = $handle ? fread($handle, 5) : '';
            if ($handle) {
                fclose($handle);
            }
            if ($mime !== 'application/pdf' || $signature !== '%PDF-' || $size > 20 * 1024 * 1024) {
                throw ValidationException::withMessages(['file' => 'Invalid PDF upload.']);
            }

            return;
        }

        if (! in_array($mime, self::DOCUMENT_MIMES, true) || $size > 20 * 1024 * 1024) {
            throw ValidationException::withMessages(['file' => 'Unsupported document upload.']);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function audit(): array
    {
        $orphans = DB::table('media')
            ->leftJoin('media_usages', 'media.id', '=', 'media_usages.media_id')
            ->whereNull('media_usages.id')
            ->whereNull('media.deleted_at')
            ->select('media.id', 'media.path', 'media.original_name')
            ->get();

        return [
            'total' => DB::table('media')->whereNull('deleted_at')->count(),
            'orphans' => $orphans->count(),
            'missing_alt' => DB::table('media')->whereNull('deleted_at')->whereNull('alt_text_id')->where('mime_type', 'like', 'image/%')->count(),
            'items' => $orphans->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function cleanup(bool $dryRun = true): array
    {
        $orphans = collect($this->audit()['items']);
        $deleted = 0;

        if (! $dryRun) {
            foreach ($orphans as $media) {
                DB::table('media')->where('id', $media->id)->update(['deleted_at' => now(), 'updated_at' => now()]);
                $deleted++;
            }
        }

        return ['dry_run' => $dryRun, 'candidates' => $orphans->count(), 'deleted' => $deleted];
    }

    /**
     * @return array{0:int|null,1:int|null}
     */
    private function dimensions(UploadedFile $file): array
    {
        if (! str_starts_with((string) $file->getMimeType(), 'image/')) {
            return [null, null];
        }

        $size = @getimagesize($file->getRealPath());

        return $size ? [$size[0], $size[1]] : [null, null];
    }

    private function typeFromMime(string $mime): string
    {
        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }

        return $mime === 'application/pdf' ? 'pdf' : 'document';
    }

    public function publicUrl(object $media): ?string
    {
        return $media->path ? Storage::disk($media->disk ?: 'public')->url($media->path) : null;
    }
}
