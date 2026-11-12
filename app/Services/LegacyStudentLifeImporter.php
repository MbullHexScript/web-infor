<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LegacyStudentLifeImporter
{
    /**
     * @return array<string, mixed>
     */
    public function inspect(): array
    {
        return [
            'sources' => $this->sources(),
            'items' => collect($this->inventory())->map(fn (array $item) => [
                ...$item,
                'verification_status' => 'pending',
                'translation_status' => 'pending',
                'migration_note' => 'Perlu verifikasi manual sebelum publish.',
            ])->values()->all(),
            'rejected' => [
                [
                    'source_url' => 'https://informatika.fsainsuinbanten.my.id/',
                    'content_type' => 'student-life:unverified-personal-data',
                    'reason' => 'Data mahasiswa/alumni tanpa consent atau identitas lengkap tidak dimigrasikan otomatis.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function import(?string $type = null, bool $dryRun = true): array
    {
        $items = collect($this->inventory())
            ->when($type && $type !== 'all', fn ($collection) => $collection->where('type', $type))
            ->values();

        $report = [
            'dry_run' => $dryRun,
            'type' => $type ?? 'all',
            'scanned' => $items->count(),
            'created_or_updated' => 0,
            'duplicates' => 0,
            'rejected' => 1,
            'items' => $items->all(),
        ];

        $this->writeReport($report);

        if ($dryRun) {
            return $report;
        }

        foreach ($items as $item) {
            $exists = DB::table('content_migrations')
                ->where('source_url', $item['source_url'])
                ->where('content_type', 'student-life:'.$item['type'])
                ->exists();

            DB::table('content_migrations')->updateOrInsert(
                [
                    'source_url' => $item['source_url'],
                    'content_type' => 'student-life:'.$item['type'],
                ],
                [
                    'target_path' => $item['target_url'],
                    'verification_status' => 'pending',
                    'translation_status' => 'pending',
                    'media_migration_status' => 'pending',
                    'revision_notes' => $item['title'].' | '.$item['note'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );

            $exists ? $report['duplicates']++ : $report['created_or_updated']++;
        }

        $this->writeReport($report);

        return $report;
    }

    /**
     * @return array<int, string>
     */
    private function sources(): array
    {
        return [
            'https://informatika.fsainsuinbanten.my.id/',
            'https://fsainsuinbanten.my.id/',
            'https://uinbanten.ac.id/',
            'https://hmps-pink.vercel.app/',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function inventory(): array
    {
        return [
            [
                'source_url' => 'https://hmps-pink.vercel.app/',
                'type' => 'hmps',
                'title' => 'Website HMPS Informatika',
                'year' => null,
                'person_name' => null,
                'level' => null,
                'media' => 'external website',
                'target_url' => '/id/kemahasiswaan/hmps',
                'note' => 'Tautan eksternal saja; tidak di-iframe dan tidak disalin penuh.',
            ],
            [
                'source_url' => 'https://uinbanten.ac.id/',
                'type' => 'admissions',
                'title' => 'Informasi PMB resmi UIN',
                'year' => null,
                'person_name' => null,
                'level' => null,
                'media' => 'external website',
                'target_url' => '/id/pmb',
                'note' => 'Nama jalur, tanggal, biaya, dan kuota harus diisi manual dari sumber resmi.',
            ],
            [
                'source_url' => 'https://informatika.fsainsuinbanten.my.id/',
                'type' => 'achievements',
                'title' => 'Inventaris Prestasi Website Lama',
                'year' => null,
                'person_name' => null,
                'level' => null,
                'media' => 'pending',
                'target_url' => '/id/kemahasiswaan/prestasi',
                'note' => 'Belum ada data terverifikasi otomatis; perlu kurasi manual untuk privasi mahasiswa.',
            ],
            [
                'source_url' => 'https://informatika.fsainsuinbanten.my.id/',
                'type' => 'facilities',
                'title' => 'Inventaris Fasilitas Website Lama',
                'year' => null,
                'person_name' => null,
                'level' => null,
                'media' => 'pending',
                'target_url' => '/id/fasilitas',
                'note' => 'Spesifikasi perangkat tidak dimigrasikan tanpa verifikasi.',
            ],
            [
                'source_url' => 'https://informatika.fsainsuinbanten.my.id/',
                'type' => 'alumni',
                'title' => 'Inventaris Alumni Website Lama',
                'year' => null,
                'person_name' => null,
                'level' => null,
                'media' => 'pending',
                'target_url' => '/id/kemahasiswaan/alumni',
                'note' => 'Alumni tidak dipublish tanpa consent.',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $report
     */
    private function writeReport(array $report): void
    {
        $directory = storage_path('logs/imports/student-life');
        File::ensureDirectoryExists($directory);
        File::put($directory.'/student-life-import-'.now()->format('Ymd-His').'.json', json_encode($report, JSON_PRETTY_PRINT));
    }
}
