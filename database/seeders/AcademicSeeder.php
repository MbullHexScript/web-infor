<?php

namespace Database\Seeders;

use App\Services\CurriculumWorkbookImporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $categories = [
            ['key' => 'curriculum', 'title_id' => 'Kurikulum', 'title_en' => 'Curriculum'],
            ['key' => 'academic-calendar', 'title_id' => 'Kalender Akademik', 'title_en' => 'Academic Calendar'],
            ['key' => 'class-schedule', 'title_id' => 'Jadwal Kuliah', 'title_en' => 'Class Schedule'],
            ['key' => 'academic-guide', 'title_id' => 'Panduan Akademik', 'title_en' => 'Academic Guide'],
            ['key' => 'final-project', 'title_id' => 'Panduan Tugas Akhir', 'title_en' => 'Final Project Guide'],
            ['key' => 'internship', 'title_id' => 'Panduan Magang', 'title_en' => 'Internship Guide'],
            ['key' => 'mbkm', 'title_id' => 'MBKM', 'title_en' => 'MBKM'],
            ['key' => 'sop', 'title_id' => 'SOP', 'title_en' => 'SOP'],
            ['key' => 'forms', 'title_id' => 'Formulir', 'title_en' => 'Forms'],
            ['key' => 'other', 'title_id' => 'Lainnya', 'title_en' => 'Other'],
        ];

        foreach ($categories as $index => $category) {
            DB::table('document_categories')->updateOrInsert(
                ['slug' => $category['key']],
                [
                    'title_id' => $category['title_id'],
                    'title_en' => $category['title_en'],
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'metadata' => json_encode(['sort_order' => $index + 1], JSON_UNESCAPED_UNICODE),
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }

        $path = config('academic.curriculum_import_path');
        if (! $path || ! File::exists($path)) {
            return;
        }

        try {
            app(CurriculumWorkbookImporter::class)->import($path, dryRun: false, updateExisting: true);
        } catch (Throwable $exception) {
            DB::table('admin_activity_logs')->insert([
                'title_id' => 'Import kurikulum awal gagal',
                'title_en' => 'Initial curriculum import failed',
                'status' => 'warning',
                'verification_status' => 'pending',
                'metadata' => json_encode(['message' => $exception->getMessage()], JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
