<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RedirectManager
{
    /**
     * @return array{old_path:string,new_path:string,redirect_code:int,is_active:bool,note:?string}
     */
    public function validate(array $input, ?int $ignoreId = null): array
    {
        $old = $this->normalizePath((string) ($input['old_path'] ?? $input['source_path'] ?? ''));
        $rawNew = (string) ($input['new_path'] ?? $input['target_path'] ?? '');
        if ($this->unsafeTarget($rawNew)) {
            throw ValidationException::withMessages(['new_path' => 'Unsafe redirect target.']);
        }
        $new = $this->normalizeTarget($rawNew);
        $code = (int) ($input['redirect_code'] ?? $input['status_code'] ?? 301);

        if ($old === '' || $new === '') {
            throw ValidationException::withMessages(['old_path' => 'Old and new path are required.']);
        }
        if ($old === $new) {
            throw ValidationException::withMessages(['new_path' => 'Redirect target cannot equal source.']);
        }
        if (! in_array($code, [301, 302, 307, 308], true)) {
            throw ValidationException::withMessages(['redirect_code' => 'Unsupported redirect code.']);
        }
        if ($this->unsafeTarget($new)) {
            throw ValidationException::withMessages(['new_path' => 'Unsafe redirect target.']);
        }

        $duplicate = DB::table('legacy_redirects')->where('old_path', $old)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()
            || DB::table('legacy_redirects')->where('source_path', $old)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists();
        if ($duplicate) {
            throw ValidationException::withMessages(['old_path' => 'Duplicate redirect source.']);
        }

        $loop = DB::table('legacy_redirects')->where(fn ($query) => $query->where('old_path', $new)->orWhere('source_path', $new))->exists();
        if ($loop) {
            throw ValidationException::withMessages(['new_path' => 'Redirect chain or loop detected.']);
        }

        return [
            'old_path' => $old,
            'new_path' => $new,
            'redirect_code' => $code,
            'is_active' => (bool) ($input['is_active'] ?? true),
            'note' => $input['note'] ?? null,
        ];
    }

    public function store(array $input): int
    {
        $data = $this->validate($input);

        return DB::table('legacy_redirects')->insertGetId([
            ...$data,
            'source_path' => $data['old_path'],
            'target_path' => $data['new_path'],
            'status_code' => $data['redirect_code'],
            'hit_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function audit(): array
    {
        $rows = DB::table('legacy_redirects')->whereNull('deleted_at')->get();
        $duplicates = $rows->groupBy(fn ($row) => $row->old_path ?: $row->source_path)->filter(fn ($group) => $group->count() > 1)->keys()->values()->all();
        $loops = $rows->filter(fn ($row) => ($row->old_path ?: $row->source_path) === ($row->new_path ?: $row->target_path))->values()->all();

        return [
            'total' => $rows->count(),
            'duplicates' => $duplicates,
            'loops' => $loops,
            'warnings' => count($duplicates) + count($loops),
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);
        $path = preg_replace('#^https?://[^/]+#i', '', $path) ?: $path;

        return '/'.ltrim($path, '/');
    }

    private function normalizeTarget(string $path): string
    {
        $path = trim($path);
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return '/'.ltrim($path, '/');
    }

    private function unsafeTarget(string $target): bool
    {
        $normalized = Str::lower(ltrim(trim($target), '/'));

        return Str::startsWith($normalized, ['javascript:', 'data:', 'vbscript:'])
            || Str::contains($target, ['../', '..\\'])
            || Str::startsWith($target, '/admin');
    }
}
