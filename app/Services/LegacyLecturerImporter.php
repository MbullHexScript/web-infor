<?php

namespace App\Services;

use App\Support\LegacyLecturerInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LegacyLecturerImporter
{
    /**
     * @return array<string, mixed>
     */
    public function inspect(): array
    {
        $lecturers = LegacyLecturerInventory::lecturers();
        $names = collect($lecturers)->pluck('name');

        return [
            'source_url' => 'https://informatika.fsainsuinbanten.my.id/struktur-organisasi-2/',
            'lecturers' => $lecturers,
            'count' => count($lecturers),
            'duplicate_names' => $names->duplicates()->values()->all(),
            'photos_found' => collect($lecturers)->whereNotNull('photo_url')->count(),
            'warnings' => [
                'Data legacy ditandai needs_review karena gelar, NIDN, email, dan tautan akademik belum diverifikasi dari sumber resmi baru.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function import(bool $dryRun = false, bool $downloadPhotos = true): array
    {
        $inspect = $this->inspect();
        $created = 0;
        $updated = 0;
        $photoCount = 0;

        if (! $dryRun) {
            foreach ($inspect['lecturers'] as $index => $lecturer) {
                $slug = Str::slug($lecturer['name']);
                $photoId = $downloadPhotos ? $this->downloadPhoto($lecturer['photo_url'], $slug) : null;
                $photoCount += $photoId ? 1 : 0;
                $payload = [
                    'full_name' => $lecturer['name'],
                    'academic_title' => $lecturer['degree'],
                    'employment_status' => 'Dosen tetap/homebase - perlu verifikasi',
                    'academic_position_id' => $lecturer['position_id'],
                    'academic_position_en' => $lecturer['position_en'],
                    'expertise_id' => null,
                    'expertise_en' => null,
                    'biography_id' => 'Profil dosen hasil migrasi awal dari website lama. Detail biografi, pendidikan, dan tautan akademik perlu diverifikasi admin.',
                    'biography_en' => null,
                    'education_id' => null,
                    'education_en' => null,
                    'photo_id' => $photoId,
                    'photo_alt' => 'Foto '.$lecturer['name'].', dosen Program Studi Informatika UIN Sultan Maulana Hasanuddin Banten.',
                    'title_id' => $lecturer['name'],
                    'title_en' => $lecturer['name'],
                    'slug' => $slug,
                    'status' => 'published',
                    'verification_status' => 'needs_review',
                    'is_active' => true,
                    'is_featured' => $lecturer['featured'],
                    'sort_order' => $index + 1,
                    'metadata' => json_encode(['legacy_source' => $inspect['source_url'], 'photo_source' => $lecturer['photo_url'], 'needs_verification' => true], JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                ];

                $existing = DB::table('lecturers')->where('slug', $slug)->first();
                if ($existing) {
                    $updated++;
                    DB::table('lecturers')->where('id', $existing->id)->update($payload);
                } else {
                    $created++;
                    DB::table('lecturers')->insert(array_merge($payload, ['created_at' => now()]));
                }
            }

            $this->syncManagementPositions();
        }

        $report = array_merge($inspect, [
            'mode' => $dryRun ? 'dry-run' : 'commit',
            'created' => $created,
            'updated' => $updated,
            'photos_imported' => $photoCount,
        ]);

        $this->writeReport($report);

        return $report;
    }

    private function downloadPhoto(?string $url, string $slug): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $response = Http::timeout(20)->get($url);
            if (! $response->successful()) {
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'jpg';
            $path = "lecturers/legacy/{$slug}.{$extension}";
            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Throwable) {
            return null;
        }
    }

    private function syncManagementPositions(): void
    {
        $positions = [
            ['slug' => 'ahmad-tabrani', 'position_id' => 'Ketua Program Studi Informatika', 'position_en' => 'Head of Informatics Study Program', 'sort' => 1],
            ['slug' => 'm-iman-wahyudi', 'position_id' => 'Sekretaris Program Studi Informatika', 'position_en' => 'Secretary of Informatics Study Program', 'sort' => 2],
            ['slug' => 'birru-muqdamien', 'position_id' => 'Gugus Kendali Mutu', 'position_en' => 'Quality Assurance Unit', 'sort' => 3],
        ];

        foreach ($positions as $position) {
            $lecturer = DB::table('lecturers')->where('slug', $position['slug'])->first();
            DB::table('management_positions')->updateOrInsert(
                ['slug' => Str::slug($position['position_id'])],
                [
                    'lecturer_id' => $lecturer?->id,
                    'person_name' => $lecturer?->full_name,
                    'position_id' => $position['position_id'],
                    'position_en' => $position['position_en'],
                    'title_id' => $position['position_id'],
                    'title_en' => $position['position_en'],
                    'status' => 'published',
                    'verification_status' => 'needs_review',
                    'is_active' => true,
                    'sort_order' => $position['sort'],
                    'metadata' => json_encode(['source' => 'legacy-lecturer-import'], JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    /**
     * @param  array<string, mixed>  $report
     */
    private function writeReport(array $report): void
    {
        $directory = storage_path('logs/imports/lecturers');
        File::ensureDirectoryExists($directory);
        $base = $directory.'/legacy-lecturers-'.now()->format('Ymd-His');
        File::put($base.'.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::put($base.'.md', "# Legacy Lecturer Import\n\n- Mode: {$report['mode']}\n- Count: {$report['count']}\n- Created: {$report['created']}\n- Updated: {$report['updated']}\n- Photos: {$report['photos_imported']}\n");
    }
}
