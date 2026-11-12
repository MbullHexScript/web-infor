<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InformationSeeder extends Seeder
{
    public function run(): void
    {
        $this->newsCategories();
        $this->documentCategories();
        $this->faqs();
        $this->localDemoContent();
    }

    private function newsCategories(): void
    {
        $items = [
            ['Akademik', 'Academic', 'BookOpen'],
            ['Kemahasiswaan', 'Student Affairs', 'Users'],
            ['Prestasi', 'Achievements', 'Trophy'],
            ['Riset', 'Research', 'FlaskConical'],
            ['Pengabdian', 'Community Service', 'HeartHandshake'],
            ['Kerja Sama', 'Partnerships', 'Handshake'],
            ['Seminar dan Workshop', 'Seminars and Workshops', 'Presentation'],
            ['Penerimaan Mahasiswa Baru', 'Admissions', 'GraduationCap'],
        ];

        foreach ($items as $index => [$id, $en, $icon]) {
            DB::table('news_categories')->updateOrInsert(
                ['slug_id' => Str::slug($id)],
                [
                    'name_id' => $id,
                    'name_en' => $en,
                    'title_id' => $id,
                    'title_en' => $en,
                    'slug' => Str::slug($id),
                    'slug_en' => Str::slug($en),
                    'color_token' => ['purple', 'graphite', 'red', 'yellow'][$index % 4],
                    'icon' => $icon,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function documentCategories(): void
    {
        $items = [
            ['Kalender Akademik', 'Academic Calendar'],
            ['Jadwal Kuliah', 'Class Schedule'],
            ['Panduan Akademik', 'Academic Guide'],
            ['Kurikulum', 'Curriculum'],
            ['Formulir', 'Forms'],
            ['Panduan Tugas Akhir', 'Final Project Guide'],
            ['Panduan Magang', 'Internship Guide'],
            ['MBKM', 'MBKM'],
            ['SOP', 'SOP'],
            ['Lainnya', 'Others'],
        ];

        foreach ($items as $index => [$id, $en]) {
            DB::table('document_categories')->updateOrInsert(
                ['slug_id' => Str::slug($id)],
                [
                    'name_id' => $id,
                    'name_en' => $en,
                    'title_id' => $id,
                    'title_en' => $en,
                    'slug' => Str::slug($id),
                    'slug_en' => Str::slug($en),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function faqs(): void
    {
        $items = [
            ['Akademik', 'Bagaimana cara melihat struktur mata kuliah?', 'Struktur mata kuliah dapat dilihat pada menu Akademik, kemudian pilih Struktur Mata Kuliah.', 'How can I see the course structure?', 'The course structure is available from Academics, then Course Structure.'],
            ['Kurikulum', 'Apakah Kurikulum OBE 2026 sudah final?', 'Kurikulum OBE 2026 ditampilkan bertahap dan data resmi perlu diverifikasi oleh pengelola program studi.', 'Is the OBE 2026 curriculum final?', 'The OBE 2026 curriculum is published gradually and official data requires verification by the study program.'],
            ['RPS', 'Di mana repositori RPS tersedia?', 'Repositori RPS tersedia pada menu Akademik dan hanya menampilkan dokumen publik yang telah diterbitkan.', 'Where is the course plan repository?', 'The course plan repository is available in Academics and only shows published public documents.'],
            ['PMB', 'Di mana informasi penerimaan mahasiswa baru?', 'Informasi PMB diarahkan ke kanal resmi universitas dan dapat diperbarui melalui dashboard admin.', 'Where is admission information?', 'Admissions information links to official university channels and can be updated from the admin dashboard.'],
            ['Layanan Prodi', 'Bagaimana menghubungi Program Studi Informatika?', 'Email resmi program studi adalah informatika@uinbanten.ac.id.', 'How do I contact the Informatics Study Program?', 'The official study program email is informatika@uinbanten.ac.id.'],
        ];

        foreach ($items as $index => [$category, $questionId, $answerId, $questionEn, $answerEn]) {
            DB::table('faqs')->updateOrInsert(
                ['question_id' => $questionId],
                [
                    'category' => $category,
                    'title_id' => $questionId,
                    'title_en' => $questionEn,
                    'question_en' => $questionEn,
                    'answer_id' => $answerId,
                    'answer_en' => $answerEn,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'is_featured' => $index < 3,
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'translation_status' => 'complete',
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function localDemoContent(): void
    {
        if (! App::environment(['local', 'testing'])) {
            return;
        }

        $categoryId = DB::table('news_categories')->where('slug_id', 'akademik')->value('id');

        DB::table('news')->updateOrInsert(
            ['slug_id' => 'demo-kurikulum-obe-2026'],
            [
                'category_id' => $categoryId,
                'title_id' => '[DEMO] Kurikulum OBE 2026 Siap Dikaji Publik',
                'title_en' => '[DEMO] OBE 2026 Curriculum Ready for Public Review',
                'slug' => 'demo-kurikulum-obe-2026',
                'slug_en' => 'demo-obe-2026-curriculum',
                'excerpt_id' => 'Data demo lokal untuk menguji modul berita. Jangan gunakan sebagai berita resmi.',
                'excerpt_en' => 'Local demo data for testing the news module. Do not use as official news.',
                'content_id' => '<p>Konten ini hanya tersedia pada environment local/testing untuk validasi tampilan dan pencarian.</p>',
                'content_en' => '<p>This content is only available in local/testing for interface and search validation.</p>',
                'author_name' => 'Admin Program Studi',
                'status' => 'published',
                'verification_status' => 'verified',
                'translation_status' => 'complete',
                'is_featured' => true,
                'is_pinned' => false,
                'published_at' => now()->subDays(2),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        DB::table('events')->updateOrInsert(
            ['slug_id' => 'demo-workshop-ai'],
            [
                'title_id' => '[DEMO] Workshop AI untuk Pembelajaran',
                'title_en' => '[DEMO] AI Workshop for Learning',
                'slug' => 'demo-workshop-ai',
                'slug_en' => 'demo-ai-workshop',
                'description_id' => 'Agenda demo untuk validasi daftar, status, dan berkas ICS.',
                'description_en' => 'Demo event for validating list, status, and ICS downloads.',
                'start_at' => now()->addDays(7)->setHour(9)->setMinute(0),
                'end_at' => now()->addDays(7)->setHour(11)->setMinute(30),
                'timezone' => 'Asia/Jakarta',
                'location_id' => 'Laboratorium Informatika',
                'location_en' => 'Informatics Laboratory',
                'organizer_id' => 'Program Studi Informatika',
                'organizer_en' => 'Informatics Study Program',
                'status' => 'published',
                'verification_status' => 'verified',
                'translation_status' => 'complete',
                'is_featured' => true,
                'is_public' => true,
                'published_at' => now()->subDay(),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        $albumId = DB::table('albums')->updateOrInsert(
            ['slug_id' => 'demo-dokumentasi-kegiatan'],
            [
                'title_id' => '[DEMO] Dokumentasi Kegiatan',
                'title_en' => '[DEMO] Activity Documentation',
                'slug' => 'demo-dokumentasi-kegiatan',
                'slug_en' => 'demo-activity-documentation',
                'description_id' => 'Album demo tanpa hotlink media lama.',
                'description_en' => 'Demo album without hotlinking legacy media.',
                'event_date' => now()->subDays(10)->toDateString(),
                'category' => 'Akademik',
                'is_public' => true,
                'is_featured' => true,
                'status' => 'published',
                'verification_status' => 'verified',
                'translation_status' => 'complete',
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }
}
