<?php

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoundationSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site.identity' => [
                'name_id' => 'Program Studi Informatika',
                'name_en' => 'Informatics Study Program',
                'faculty_id' => 'Fakultas Sains dan Teknologi',
                'university_id' => 'UIN Sultan Maulana Hasanuddin Banten',
                'tagline' => 'Empowering Digital Innovation with Islamic Values',
                'email' => 'informatika@uinbanten.ac.id',
                'domain' => 'https://informatika.uinbanten.ac.id',
            ],
            'site.links' => [
                'uin' => 'https://uinbanten.ac.id/',
                'faculty' => 'https://fsainsuinbanten.my.id/',
                'siakad' => '#',
                'elearning' => '#',
                'library' => '#',
                'pmb' => 'https://pmb.uinbanten.ac.id/',
                'hmps' => 'https://hmps-pink.vercel.app/',
            ],
            'site.contact' => [
                'address_id' => 'Alamat resmi dapat diperbarui melalui dashboard admin.',
                'address_en' => 'The official address can be updated from the admin dashboard.',
                'service_hours_id' => 'Jam pelayanan belum diverifikasi.',
                'service_hours_en' => 'Service hours are pending verification.',
            ],
            'homepage.hero' => [
                'label_id' => 'FAKULTAS SAINS DAN TEKNOLOGI',
                'label_en' => 'FACULTY OF SCIENCE AND TECHNOLOGY',
                'university_id' => 'UIN SULTAN MAULANA HASANUDDIN BANTEN',
                'university_en' => 'SULTAN MAULANA HASANUDDIN STATE ISLAMIC UNIVERSITY BANTEN',
                'heading_id' => 'PROGRAM STUDI INFORMATIKA',
                'heading_en' => 'INFORMATICS STUDY PROGRAM',
                'tagline' => 'Empowering Digital Innovation with Islamic Values',
                'description_id' => 'Membangun generasi profesional di bidang kecerdasan buatan, rekayasa perangkat lunak, data science, jaringan komputer, dan keamanan siber dengan landasan nilai-nilai keislaman.',
                'description_en' => 'Preparing future-ready professionals in artificial intelligence, software engineering, data science, computer networks, and cybersecurity through education grounded in Islamic values.',
            ],
            'homepage.study_fields' => [
                ['key' => 'ai', 'name_id' => 'Artificial Intelligence', 'name_en' => 'Artificial Intelligence', 'description_id' => 'Pembelajaran sistem cerdas, machine learning, dan solusi komputasi adaptif.', 'description_en' => 'Intelligent systems, machine learning, and adaptive computing solutions.', 'icon' => 'Sparkles', 'is_active' => true, 'sort_order' => 1],
                ['key' => 'data-science', 'name_id' => 'Data Science', 'name_en' => 'Data Science', 'description_id' => 'Analisis data, visualisasi, dan pengambilan keputusan berbasis bukti.', 'description_en' => 'Data analysis, visualization, and evidence-based decision making.', 'icon' => 'Database', 'is_active' => true, 'sort_order' => 2],
                ['key' => 'software-engineering', 'name_id' => 'Software Engineering', 'name_en' => 'Software Engineering', 'description_id' => 'Perancangan, pengembangan, dan pemeliharaan perangkat lunak berkualitas.', 'description_en' => 'Designing, building, and maintaining reliable software systems.', 'icon' => 'Code2', 'is_active' => true, 'sort_order' => 3],
                ['key' => 'cybersecurity', 'name_id' => 'Cybersecurity', 'name_en' => 'Cybersecurity', 'description_id' => 'Keamanan sistem, resiliensi siber, dan tata kelola risiko digital.', 'description_en' => 'System security, cyber resilience, and digital risk governance.', 'icon' => 'ShieldCheck', 'is_active' => true, 'sort_order' => 4],
                ['key' => 'computer-network', 'name_id' => 'Computer Network', 'name_en' => 'Computer Network', 'description_id' => 'Jaringan komputer, infrastruktur digital, dan layanan terhubung.', 'description_en' => 'Computer networks, digital infrastructure, and connected services.', 'icon' => 'Network', 'is_active' => true, 'sort_order' => 5],
                ['key' => 'iot', 'name_id' => 'Internet of Things', 'name_en' => 'Internet of Things', 'description_id' => 'Integrasi sensor, perangkat, dan sistem cerdas untuk dunia nyata.', 'description_en' => 'Integrating sensors, devices, and smart systems for real-world use.', 'icon' => 'Cpu', 'is_active' => true, 'sort_order' => 6],
            ],
            'homepage.curriculum' => [
                'total_sks' => 144,
                'semesters' => 8,
                'specializations' => [
                    ['name_id' => 'RPL dan Aplikasi Cerdas', 'name_en' => 'Software Engineering and Intelligent Applications'],
                    ['name_id' => 'Data dan AI', 'name_en' => 'Data and AI'],
                    ['name_id' => 'Cybersecurity', 'name_en' => 'Cybersecurity'],
                ],
                'source' => 'Draf Kurikulum OBE 2026',
            ],
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'json', 'group' => 'site', 'is_public' => true],
            );
        }

        $journals = [
            ['name' => 'NEXUS', 'slug' => 'nexus', 'full_name' => 'International Journal of Informatics and Intelligent Systems', 'tagline' => 'Connecting Intelligence, Innovation, and Computing.', 'scope_id' => 'Informatika, sistem cerdas, komputasi, dan inovasi digital.', 'scope_en' => 'Informatics, intelligent systems, computing, and digital innovation.', 'sort_order' => 1],
            ['name' => 'DIGIMAS', 'slug' => 'digimas', 'full_name' => 'Journal of Digital Innovation and Smart Society', 'tagline' => 'Digital Innovation for Sustainable Society.', 'scope_id' => 'Inovasi digital, masyarakat cerdas, dan transformasi berkelanjutan.', 'scope_en' => 'Digital innovation, smart society, and sustainable transformation.', 'sort_order' => 2],
            ['name' => 'ABDIMATEK', 'slug' => 'abdimatek', 'full_name' => 'Journal of Community Technology and Digital Empowerment', 'tagline' => 'Technology for Community Empowerment.', 'scope_id' => 'Teknologi tepat guna, pemberdayaan masyarakat, dan literasi digital.', 'scope_en' => 'Applied technology, community empowerment, and digital literacy.', 'sort_order' => 3],
        ];

        foreach ($journals as $journal) {
            Journal::updateOrCreate(
                ['slug' => $journal['slug']],
                [
                    'name' => $journal['name'],
                    'full_name' => $journal['full_name'],
                    'tagline' => $journal['tagline'],
                    'description_id' => 'Placeholder jurnal resmi. Lengkapi dan verifikasi melalui dashboard admin.',
                    'description_en' => 'Official journal placeholder. Complete and verify it from the admin dashboard.',
                    'scope_id' => $journal['scope_id'],
                    'scope_en' => $journal['scope_en'],
                    'verification_status' => 'pending',
                    'status_label' => 'preparation',
                    'is_active' => true,
                    'sort_order' => $journal['sort_order'],
                ],
            );
        }

        DB::table('statistics')->whereNull('key')->delete();
        DB::table('quick_links')->whereNull('key')->delete();

        DB::table('statistics')->upsert([
            ['key' => 'active_students', 'label_id' => 'Mahasiswa Aktif', 'label_en' => 'Active Students', 'value' => null, 'suffix' => null, 'source_note' => 'Menunggu verifikasi admin.', 'verification_status' => 'pending', 'is_active' => false, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'lecturers', 'label_id' => 'Dosen', 'label_en' => 'Lecturers', 'value' => null, 'suffix' => null, 'source_note' => 'Menunggu verifikasi admin.', 'verification_status' => 'pending', 'is_active' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'achievements', 'label_id' => 'Prestasi', 'label_en' => 'Achievements', 'value' => null, 'suffix' => null, 'source_note' => 'Menunggu verifikasi admin.', 'verification_status' => 'pending', 'is_active' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'publications', 'label_id' => 'Publikasi', 'label_en' => 'Publications', 'value' => null, 'suffix' => null, 'source_note' => 'Menunggu verifikasi admin.', 'verification_status' => 'pending', 'is_active' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'partners', 'label_id' => 'Mitra', 'label_en' => 'Partners', 'value' => null, 'suffix' => null, 'source_note' => 'Menunggu verifikasi admin.', 'verification_status' => 'pending', 'is_active' => false, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'digital_products', 'label_id' => 'Produk Digital', 'label_en' => 'Digital Products', 'value' => null, 'suffix' => null, 'source_note' => 'Menunggu verifikasi admin.', 'verification_status' => 'pending', 'is_active' => false, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ], ['key'], ['label_id', 'label_en', 'value', 'suffix', 'source_note', 'verification_status', 'is_active', 'sort_order', 'updated_at']);

        DB::table('quick_links')->upsert([
            ['key' => 'siakad', 'label_id' => 'SIAKAD', 'label_en' => 'SIAKAD', 'url' => '#', 'icon' => 'GraduationCap', 'is_external' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'schedule', 'label_id' => 'Jadwal Kuliah', 'label_en' => 'Class Schedule', 'url' => '/id/akademik/jadwal-perkuliahan', 'icon' => 'CalendarDays', 'is_external' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'calendar', 'label_id' => 'Kalender Akademik', 'label_en' => 'Academic Calendar', 'url' => '/id/akademik/kalender-akademik', 'icon' => 'CalendarCheck', 'is_external' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'curriculum', 'label_id' => 'Kurikulum', 'label_en' => 'Curriculum', 'url' => '/id/akademik/kurikulum', 'icon' => 'BookOpen', 'is_external' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'rps', 'label_id' => 'RPS', 'label_en' => 'Course Plans', 'url' => '/id/akademik/rps', 'icon' => 'FileText', 'is_external' => false, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'downloads', 'label_id' => 'Pusat Unduhan', 'label_en' => 'Download Center', 'url' => '/id/informasi/unduhan', 'icon' => 'Download', 'is_external' => false, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ], ['key'], ['label_id', 'label_en', 'url', 'icon', 'is_external', 'sort_order', 'updated_at']);

        DB::table('homepage_sections')->upsert($this->homepageSections(), ['key'], ['title_id', 'title_en', 'subtitle_id', 'subtitle_en', 'background_style', 'is_active', 'sort_order', 'updated_at']);

        DB::table('announcements')->updateOrInsert(
            ['slug' => 'kurikulum-obe-2026-dipersiapkan'],
            [
                'title_id' => 'Kurikulum OBE 2026 sedang dipersiapkan untuk publikasi bertahap',
                'title_en' => 'The OBE 2026 curriculum is being prepared for phased publication',
                'status' => 'published',
                'verification_status' => 'pending',
                'metadata' => json_encode([
                    'priority' => 'important',
                    'featured' => true,
                    'starts_at' => now()->subDay()->toDateString(),
                    'expires_at' => now()->addMonths(3)->toDateString(),
                ]),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function homepageSections(): array
    {
        $sections = [
            ['hero', 'Beranda', 'Home', null, null],
            ['announcements', 'Pengumuman Penting', 'Important Announcements', 'Informasi prioritas dari Program Studi Informatika.', 'Priority updates from the Informatics Study Program.'],
            ['quick_links', 'Akses Cepat', 'Quick Links', 'Jalur singkat menuju layanan dan informasi akademik.', 'Fast access to academic services and information.'],
            ['about', 'Tentang Informatika', 'About Informatics', 'Membangun Amanah Digital untuk Kemaslahatan.', 'Building Trusted Digital Innovation for Society.'],
            ['statistics', 'Statistik Program Studi', 'Study Program Statistics', 'Ditampilkan setelah data diverifikasi oleh admin.', 'Shown after the data is verified by the admin.'],
            ['study_fields', 'Bidang Keilmuan', 'Fields of Study', 'Enam fokus keilmuan yang membentuk kompetensi lulusan.', 'Six knowledge areas shaping graduate competencies.'],
            ['curriculum', 'Kurikulum OBE 2026', 'OBE 2026 Curriculum', 'Struktur pembelajaran 144 SKS, delapan semester, dan tiga peminatan.', 'A 144-credit learning structure across eight semesters and three specializations.'],
            ['head_message', 'Sambutan Ketua Program Studi', 'Head of Study Program Message', 'Pesan akademik untuk masa depan digital yang berintegritas.', 'An academic message for an ethical digital future.'],
            ['lecturers', 'Dosen Informatika', 'Informatics Lecturers', 'Profil dosen akan tampil setelah diverifikasi admin.', 'Lecturer profiles appear after admin verification.'],
            ['research', 'Riset dan Inovasi', 'Research and Innovation', 'Karya riset, pengabdian, publikasi, produk digital, dan HKI.', 'Research, community service, publications, digital products, and IP.'],
            ['journals', 'Rumah Jurnal', 'Journal Hub', 'Tiga jurnal yang disiapkan untuk ekosistem akademik Informatika.', 'Three journals prepared for the Informatics academic ecosystem.'],
            ['news_information', 'Berita, Pengumuman, dan Agenda', 'News, Announcements, and Events', 'Informasi publik Program Studi Informatika.', 'Public information from the Informatics Study Program.'],
            ['achievements', 'Prestasi Mahasiswa', 'Student Achievements', 'Prestasi terverifikasi akan ditampilkan di sini.', 'Verified achievements will be shown here.'],
            ['facilities', 'Fasilitas', 'Facilities', 'Fasilitas unggulan akan tampil setelah data dan foto tersedia.', 'Featured facilities appear after data and photos are available.'],
            ['hmps', 'HMPS Informatika', 'Informatics Student Association', 'Ruang mahasiswa untuk bertumbuh, berkarya, dan berkolaborasi.', 'A student platform to grow, create, and collaborate.'],
            ['admission_cta', 'Penerimaan Mahasiswa Baru', 'Admissions', 'Temukan pengalaman belajar Informatika di UIN SMH Banten.', 'Discover Informatics learning at UIN SMH Banten.'],
        ];

        return array_map(fn (array $section, int $index): array => [
            'key' => $section[0],
            'title_id' => $section[1],
            'title_en' => $section[2],
            'subtitle_id' => $section[3],
            'subtitle_en' => $section[4],
            'background_style' => 'default',
            'is_active' => true,
            'sort_order' => $index + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $sections, array_keys($sections));
    }
}
