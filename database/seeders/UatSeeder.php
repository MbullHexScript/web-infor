<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UatSeeder extends Seeder
{
    public function run(): void
    {
        abort_if(App::environment('production'), 403, 'UAT seeder must not run in production.');

        $now = now();

        if (Schema::hasTable('news')) {
            DB::table('news')->updateOrInsert(
                ['slug' => 'uat-berita-informatika'],
                $this->onlyExistingColumns('news', [
                    'title_id' => '[UAT] Berita Informatika',
                    'title_en' => '[UAT] Informatics News',
                    'slug_id' => 'uat-berita-informatika',
                    'slug_en' => 'uat-informatics-news',
                    'excerpt_id' => 'Konten UAT untuk validasi alur berita.',
                    'excerpt_en' => 'UAT content for validating the news workflow.',
                    'content_id' => 'Konten ini hanya untuk UAT dan tidak boleh dipakai di production.',
                    'content_en' => 'This content is for UAT only and must not be used in production.',
                    'status' => 'draft',
                    'verification_status' => 'needs_review',
                    'translation_status' => 'complete',
                    'published_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        if (Schema::hasTable('announcements')) {
            DB::table('announcements')->updateOrInsert(
                ['slug' => 'uat-pengumuman-informatika'],
                $this->onlyExistingColumns('announcements', [
                    'title_id' => '[UAT] Pengumuman Informatika',
                    'title_en' => '[UAT] Informatics Announcement',
                    'slug_id' => 'uat-pengumuman-informatika',
                    'slug_en' => 'uat-informatics-announcement',
                    'content_id' => 'Pengumuman UAT.',
                    'content_en' => 'UAT announcement.',
                    'status' => 'draft',
                    'verification_status' => 'needs_review',
                    'translation_status' => 'complete',
                    'starts_at' => $now,
                    'expires_at' => $now->copy()->addDays(7),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        if (Schema::hasTable('events')) {
            DB::table('events')->updateOrInsert(
                ['slug' => 'uat-agenda-informatika'],
                $this->onlyExistingColumns('events', [
                    'title_id' => '[UAT] Agenda Informatika',
                    'title_en' => '[UAT] Informatics Event',
                    'slug_id' => 'uat-agenda-informatika',
                    'slug_en' => 'uat-informatics-event',
                    'description_id' => 'Agenda UAT.',
                    'description_en' => 'UAT event.',
                    'status' => 'draft',
                    'verification_status' => 'needs_review',
                    'translation_status' => 'complete',
                    'start_at' => $now->copy()->addDays(3),
                    'end_at' => $now->copy()->addDays(3)->addHours(2),
                    'timezone' => 'Asia/Jakarta',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        if (Schema::hasTable('contact_messages')) {
            DB::table('contact_messages')->updateOrInsert(
                ['email' => 'uat@example.test', 'subject' => '[UAT] Pertanyaan PMB'],
                $this->onlyExistingColumns('contact_messages', [
                    'name' => '[UAT] Calon Mahasiswa',
                    'email' => 'uat@example.test',
                    'category' => 'admissions',
                    'subject' => '[UAT] Pertanyaan PMB',
                    'message' => 'Pesan UAT untuk validasi inbox kontak.',
                    'status' => 'new',
                    'locale' => 'id',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        if (Schema::hasTable('media')) {
            DB::table('media')->updateOrInsert(
                ['checksum' => 'uat-'.Str::slug(config('app.name', 'informatika'))],
                $this->onlyExistingColumns('media', [
                    'disk' => 'public',
                    'file_name' => 'uat-placeholder.txt',
                    'original_name' => '[UAT] Placeholder',
                    'path' => 'uat/placeholder.txt',
                    'mime_type' => 'text/plain',
                    'file_size' => 0,
                    'title_id' => '[UAT] Placeholder',
                    'title_en' => '[UAT] Placeholder',
                    'alt_text_id' => 'Placeholder UAT',
                    'alt_text_en' => 'UAT placeholder',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }
    }

    private function onlyExistingColumns(string $table, array $payload): array
    {
        $columns = Schema::getColumnListing($table);

        return collect($payload)->only($columns)->all();
    }
}
