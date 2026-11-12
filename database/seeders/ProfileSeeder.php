<?php

namespace Database\Seeders;

use App\Services\LegacyLecturerImporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ($this->pages() as $page) {
            $source = $page['source'] ?? 'phase-04-seed';
            unset($page['source']);

            DB::table('pages')->updateOrInsert(
                ['key' => $page['key']],
                array_merge($page, [
                    'title_id' => $page['title_id'],
                    'title_en' => $page['title_en'],
                    'slug' => $page['key'],
                    'status' => 'published',
                    'verification_status' => $page['verification_status'],
                    'is_active' => true,
                    'published_at' => $now,
                    'metadata' => json_encode(['source' => $source], JSON_UNESCAPED_UNICODE),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        foreach ($this->expertiseAreas() as $index => $area) {
            DB::table('expertise_areas')->updateOrInsert(
                ['slug' => $area['slug']],
                array_merge($area, ['sort_order' => $index + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]),
            );
        }

        DB::table('accreditations')->updateOrInsert(
            ['decision_number' => 'legacy-unverified-baik'],
            [
                'accreditation_body' => null,
                'level_or_grade' => 'Baik',
                'description_id' => 'Status akreditasi Baik ditemukan pada website lama, tetapi nomor keputusan dan masa berlaku perlu diverifikasi sebelum dipublikasikan.',
                'description_en' => 'Good accreditation status was found on the legacy website, but decision number and validity dates must be verified before publication.',
                'is_current' => false,
                'is_public' => false,
                'verification_status' => 'needs_review',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );

        foreach ($this->partners() as $index => $partner) {
            DB::table('partners')->updateOrInsert(
                ['slug' => Str::slug($partner['name'])],
                array_merge($partner, [
                    'title_id' => $partner['name'],
                    'title_en' => $partner['name'],
                    'status' => 'published',
                    'verification_status' => 'needs_review',
                    'is_active' => true,
                    'is_featured' => $index < 6,
                    'sort_order' => $index + 1,
                    'metadata' => json_encode(['legacy_source' => 'https://informatika.fsainsuinbanten.my.id/kerjasama/'], JSON_UNESCAPED_UNICODE),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }

        app(LegacyLecturerImporter::class)->import(dryRun: false, downloadPhotos: ! app()->environment('testing'));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function pages(): array
    {
        return [
            [
                'key' => 'profile.about',
                'title_id' => 'Tentang Informatika',
                'title_en' => 'About Informatics',
                'excerpt_id' => 'Program Studi Informatika mengembangkan pendidikan, penelitian, dan inovasi teknologi digital dengan nilai keislaman.',
                'excerpt_en' => 'The Informatics Study Program advances digital technology education, research, and innovation with Islamic values.',
                'content_id' => 'Program Studi Informatika Fakultas Sains dan Teknologi UIN Sultan Maulana Hasanuddin Banten mengembangkan pendidikan, penelitian, dan inovasi di bidang teknologi digital dengan mengintegrasikan kompetensi profesional, tanggung jawab etis, dan nilai-nilai keislaman.',
                'content_en' => 'The Informatics Study Program at the Faculty of Science and Technology, Sultan Maulana Hasanuddin State Islamic University Banten, advances education, research, and digital innovation by integrating professional competence, ethical responsibility, and Islamic values.',
                'verification_status' => 'verified',
            ],
            [
                'key' => 'profile.history',
                'title_id' => 'Sejarah Program Studi',
                'title_en' => 'Study Program History',
                'excerpt_id' => 'Program Studi Informatika memperoleh izin operasional berdasarkan Keputusan Menteri Nomor 400/E/O/2023 pada 2 Mei 2023.',
                'excerpt_en' => 'The Informatics Study Program received operational approval through Ministerial Decree No. 400/E/O/2023 on May 2, 2023.',
                'content_id' => 'Program Studi Informatika resmi memperoleh izin operasional berdasarkan Keputusan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi Republik Indonesia Nomor 400/E/O/2023 yang ditetapkan pada tanggal 2 Mei 2023. Data sejarah lain dari website lama digunakan sebagai draft dan perlu verifikasi lanjutan.',
                'content_en' => 'The Informatics Study Program received operational approval through Ministerial Decree No. 400/E/O/2023 dated May 2, 2023. Additional legacy history content is treated as draft material pending verification.',
                'verification_status' => 'verified',
                'source' => 'https://informatika.fsainsuinbanten.my.id/sejarah-informatika/',
            ],
            [
                'key' => 'profile.vision',
                'title_id' => 'Visi, Misi, dan Tujuan',
                'title_en' => 'Vision, Mission, and Objectives',
                'excerpt_id' => 'Unggul dalam pengembangan Teknologi Informasi Cerdas dan Teknologi Web inovatif berlandaskan nilai-nilai keislaman.',
                'excerpt_en' => 'Excellence in intelligent information technology and innovative web technology grounded in Islamic values.',
                'content_id' => 'Menjadi Program Studi Informatika yang unggul dalam pengembangan Teknologi Informasi Cerdas dan Teknologi Web inovatif berlandaskan nilai-nilai keislaman.',
                'content_en' => 'To become an Informatics Study Program excellent in developing intelligent information technology and innovative web technology grounded in Islamic values.',
                'verification_status' => 'needs_review',
                'source' => 'https://informatika.fsainsuinbanten.my.id/visi-dan-misi/',
            ],
            [
                'key' => 'profile.head-message',
                'title_id' => 'Sambutan Ketua Program Studi',
                'title_en' => 'Head of Study Program Message',
                'excerpt_id' => 'Selamat datang di Website Resmi Program Studi Informatika.',
                'excerpt_en' => 'Welcome to the official website of the Informatics Study Program.',
                'content_id' => "Assalamu'alaikum warahmatullahi wabarakatuh.\n\nSelamat datang di Website Resmi Program Studi Informatika, Fakultas Sains dan Teknologi, UIN Sultan Maulana Hasanuddin Banten.\n\nProgram Studi Informatika hadir sebagai ruang akademik untuk mengembangkan ilmu pengetahuan, keterampilan, inovasi, dan karakter dalam menghadapi transformasi digital. Melalui kurikulum berbasis Outcome-Based Education, mahasiswa dipersiapkan untuk memiliki kompetensi dalam rekayasa perangkat lunak, kecerdasan buatan dan analitik data, keamanan siber, jaringan komputer, serta berbagai bidang teknologi digital yang terus berkembang.\n\nWassalamu'alaikum warahmatullahi wabarakatuh.",
                'content_en' => "Welcome to the official website of the Informatics Study Program, Faculty of Science and Technology, UIN Sultan Maulana Hasanuddin Banten.\n\nThe Informatics Study Program is an academic home for developing knowledge, skills, innovation, and character in the era of digital transformation. Through an Outcome-Based Education curriculum, students are prepared to build competence in software engineering, artificial intelligence, data analytics, cybersecurity, computer networks, and other evolving areas of digital technology.",
                'verification_status' => 'verified',
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function expertiseAreas(): array
    {
        return [
            ['name_id' => 'Artificial Intelligence', 'name_en' => 'Artificial Intelligence', 'slug' => 'artificial-intelligence', 'icon' => 'BrainCircuit'],
            ['name_id' => 'Data Science', 'name_en' => 'Data Science', 'slug' => 'data-science', 'icon' => 'Database'],
            ['name_id' => 'Software Engineering', 'name_en' => 'Software Engineering', 'slug' => 'software-engineering', 'icon' => 'Code2'],
            ['name_id' => 'Cybersecurity', 'name_en' => 'Cybersecurity', 'slug' => 'cybersecurity', 'icon' => 'ShieldCheck'],
            ['name_id' => 'Computer Networks', 'name_en' => 'Computer Networks', 'slug' => 'computer-networks', 'icon' => 'Network'],
            ['name_id' => 'Internet of Things', 'name_en' => 'Internet of Things', 'slug' => 'internet-of-things', 'icon' => 'Cpu'],
            ['name_id' => 'Information Systems', 'name_en' => 'Information Systems', 'slug' => 'information-systems', 'icon' => 'Layers3'],
            ['name_id' => 'Educational Technology', 'name_en' => 'Educational Technology', 'slug' => 'educational-technology', 'icon' => 'GraduationCap'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function partners(): array
    {
        return [
            ['name' => 'Bank Indonesia', 'partner_type' => 'Pemerintah', 'scope_id' => 'Kerja sama tercatat pada website lama; detail dokumen perlu verifikasi.'],
            ['name' => 'PT Krakatau Steel', 'partner_type' => 'Industri', 'scope_id' => 'Krakatau Informasi Teknologi (K-IT).'],
            ['name' => 'Badan Meteorologi, Klimatologi dan Geofisika', 'partner_type' => 'Pemerintah', 'scope_id' => 'Tri Dharma Perguruan Tinggi dan pengembangan kapasitas SDM.'],
            ['name' => 'Dinas Energi dan Sumber Daya Mineral Provinsi Banten', 'partner_type' => 'Pemerintah', 'scope_id' => 'Tri Dharma Perguruan Tinggi.'],
            ['name' => 'Fakultas Biologi Universitas Gadjah Mada', 'partner_type' => 'Perguruan tinggi', 'scope_id' => 'MBKM, penelitian, dan pengabdian kepada masyarakat.'],
            ['name' => 'Asosiasi Pendidikan Tinggi Informatika dan Komputer', 'partner_type' => 'Organisasi profesi', 'scope_id' => 'Pelaksanaan program Tridharma Perguruan Tinggi.'],
        ];
    }
}
