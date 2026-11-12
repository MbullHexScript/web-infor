<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentLifeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        SiteSetting::updateOrCreate(
            ['key' => 'student_life.hmps'],
            [
                'value' => [
                    'name_id' => 'Himpunan Mahasiswa Program Studi Informatika',
                    'name_en' => 'Informatics Student Association',
                    'url' => 'https://hmps-pink.vercel.app/',
                    'description_id' => 'HMPS Informatika menjadi ruang organisasi mahasiswa untuk belajar kepemimpinan, kolaborasi, dan kontribusi akademik.',
                    'description_en' => 'The Informatics Student Association provides a student organization space for leadership, collaboration, and academic contribution.',
                ],
                'type' => 'json',
                'group' => 'student_life',
                'is_public' => true,
            ],
        );

        SiteSetting::updateOrCreate(
            ['key' => 'admissions.portal'],
            [
                'value' => [
                    'url' => 'https://pmb.uinbanten.ac.id/',
                    'note_id' => 'Portal PMB resmi UIN Sultan Maulana Hasanuddin Banten.',
                    'note_en' => 'Official admissions portal of UIN Sultan Maulana Hasanuddin Banten.',
                ],
                'type' => 'json',
                'group' => 'admissions',
                'is_public' => true,
            ],
        );

        SiteSetting::updateOrCreate(
            ['key' => 'site.contact.public'],
            [
                'value' => [
                    'name_id' => 'Program Studi Informatika',
                    'name_en' => 'Informatics Study Program',
                    'faculty_id' => 'Fakultas Sains dan Teknologi',
                    'university_id' => 'UIN Sultan Maulana Hasanuddin Banten',
                    'email' => 'informatika@uinbanten.ac.id',
                    'phone' => null,
                    'address_id' => 'Alamat resmi perlu diverifikasi dan dilengkapi melalui dashboard admin.',
                    'address_en' => 'The official address must be verified and completed from the admin dashboard.',
                    'service_hours_id' => 'Jam pelayanan menunggu verifikasi.',
                    'service_hours_en' => 'Service hours are pending verification.',
                    'map_embed_url' => null,
                    'map_url' => null,
                ],
                'type' => 'json',
                'group' => 'contact',
                'is_public' => true,
            ],
        );

        $this->seedPages($now);
        $this->seedFaqs($now);
        $this->seedAdmissions($now);
        $this->seedDevelopmentSamples($now);
    }

    private function seedPages($now): void
    {
        $pages = [
            [
                'slug' => 'kehidupan-mahasiswa',
                'slug_id' => 'kehidupan-mahasiswa',
                'slug_en' => 'campus-life',
                'title_id' => 'Kehidupan Mahasiswa',
                'title_en' => 'Campus Life',
                'page_type' => 'student_life',
                'content_id' => 'Kehidupan mahasiswa Informatika meliputi praktikum, organisasi mahasiswa, seminar, workshop, kompetisi, proyek mahasiswa, kegiatan sosial, kegiatan keagamaan, kolaborasi, dan pemanfaatan fasilitas. Konten detail perlu dilengkapi admin dengan dokumentasi resmi.',
                'content_en' => 'Informatics student life includes labs, student organizations, seminars, workshops, competitions, student projects, social activities, religious activities, collaboration, and facility use. Detailed content should be completed by admins with official documentation.',
            ],
            [
                'slug' => 'mbkm-magang',
                'slug_id' => 'mbkm-magang',
                'slug_en' => 'mbkm-internships',
                'title_id' => 'MBKM dan Magang',
                'title_en' => 'MBKM and Internships',
                'page_type' => 'mbkm',
                'content_id' => 'Informasi MBKM dan magang ditampilkan sebagai panduan awal. Prosedur, persyaratan, dokumen, mitra, dan kontak wajib diperbarui admin berdasarkan kebijakan resmi Prodi, Fakultas, dan Universitas.',
                'content_en' => 'MBKM and internship information is presented as an initial guide. Procedures, requirements, documents, partners, and contacts must be updated by admins based on official study program, faculty, and university policy.',
            ],
            [
                'slug' => 'pmb',
                'slug_id' => 'pmb',
                'slug_en' => 'admissions',
                'title_id' => 'Penerimaan Mahasiswa Baru',
                'title_en' => 'Admissions',
                'page_type' => 'admissions',
                'content_id' => 'Halaman PMB menyediakan informasi Program Studi Informatika, bidang keilmuan, Kurikulum OBE 2026, prospek karier, fasilitas, kehidupan mahasiswa, beasiswa, FAQ, dan tautan ke portal resmi bila sudah diverifikasi.',
                'content_en' => 'The admissions page provides information about the Informatics Study Program, fields of study, OBE 2026 curriculum, career prospects, facilities, student life, scholarships, FAQ, and a verified official portal link when available.',
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->updateOrInsert(
                ['slug' => $page['slug']],
                [
                    ...$page,
                    'status' => 'published',
                    'verification_status' => 'verified',
                    'translation_status' => 'complete',
                    'is_public' => true,
                    'published_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    private function seedFaqs($now): void
    {
        $faqs = [
            ['Jalur Masuk', 'Admission Pathways', 'Di mana informasi jalur penerimaan resmi tersedia?', 'Informasi jalur penerimaan resmi mengikuti kanal PMB UIN Sultan Maulana Hasanuddin Banten. Tautan akan ditampilkan setelah diverifikasi admin.', 'Where is official admissions pathway information available?', 'Official admissions pathway information follows UIN Sultan Maulana Hasanuddin Banten admissions channels. A link will be shown after admin verification.'],
            ['Kurikulum', 'Curriculum', 'Kurikulum apa yang digunakan?', 'Program Studi Informatika menyiapkan Kurikulum OBE 2026 dengan bidang AI, Data, Rekayasa Perangkat Lunak, Jaringan, dan Keamanan Siber.', 'What curriculum is used?', 'The Informatics Study Program prepares the OBE 2026 curriculum with AI, Data, Software Engineering, Networks, and Cybersecurity fields.'],
            ['Biaya', 'Tuition', 'Apakah informasi biaya ditampilkan di website ini?', 'Informasi biaya tidak ditampilkan sebelum ada sumber resmi yang diverifikasi. Calon mahasiswa perlu merujuk kanal PMB resmi.', 'Is tuition information shown on this website?', 'Tuition information is not shown before a verified official source is available. Prospective students should refer to official admissions channels.'],
            ['Kontak', 'Contact', 'Bagaimana menghubungi Program Studi?', 'Gunakan email informatika@uinbanten.ac.id atau formulir kontak pada halaman Kontak.', 'How can I contact the Study Program?', 'Use informatika@uinbanten.ac.id or the contact form on the Contact page.'],
        ];

        foreach ($faqs as $index => [$categoryId, $categoryEn, $questionId, $answerId, $questionEn, $answerEn]) {
            DB::table('faqs')->updateOrInsert(
                ['category' => 'admissions', 'question_id' => $questionId],
                [
                    'title_id' => $categoryId,
                    'title_en' => $categoryEn,
                    'slug' => Str::slug($questionId),
                    'question_en' => $questionEn,
                    'answer_id' => $answerId,
                    'answer_en' => $answerEn,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'is_featured' => true,
                    'verification_status' => 'verified',
                    'translation_status' => 'complete',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    private function seedAdmissions($now): void
    {
        DB::table('admission_paths')->updateOrInsert(
            ['slug_id' => 'jalur-penerimaan-resmi'],
            [
                'name_id' => 'Jalur Penerimaan Resmi',
                'name_en' => 'Official Admission Pathways',
                'slug_en' => 'official-admission-pathways',
                'description_id' => 'Nama jalur, tanggal, biaya, kuota, dan syarat belum dipublikasikan di website ini sampai admin memasukkan data dari sumber resmi.',
                'description_en' => 'Pathway names, dates, fees, quotas, and requirements are not published on this website until admins enter data from official sources.',
                'requirements_id' => null,
                'requirements_en' => null,
                'registration_url' => null,
                'starts_at' => null,
                'ends_at' => null,
                'status' => 'upcoming',
                'verification_status' => 'pending',
                'is_public' => false,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }

    private function seedDevelopmentSamples($now): void
    {
        if (! App::environment(['local', 'testing'])) {
            return;
        }

        DB::table('achievements')->updateOrInsert(
            ['slug_id' => 'demo-prestasi-mahasiswa'],
            [
                'title_id' => '[DEMO] Prestasi Mahasiswa',
                'title_en' => '[DEMO] Student Achievement',
                'slug' => 'demo-prestasi-mahasiswa',
                'slug_en' => 'demo-student-achievement',
                'achievement_type' => 'student',
                'recipient_name' => 'Mahasiswa Informatika',
                'student_identifiers_json' => json_encode(['nim_masked' => '****']),
                'level' => 'national',
                'organizer' => 'Demo',
                'year' => 2026,
                'rank_or_award_id' => 'Demo',
                'rank_or_award_en' => 'Demo',
                'description_id' => 'Data demo hanya untuk pengujian lokal.',
                'description_en' => 'Demo data for local testing only.',
                'verification_status' => 'verified',
                'translation_status' => 'complete',
                'is_featured' => true,
                'is_public' => true,
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }
}
