<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResearchSeeder extends Seeder
{
    public function run(): void
    {
        $this->journals();
        $this->expertiseGroups();
        $this->localDemoContent();
    }

    private function journals(): void
    {
        $journals = [
            [
                'code' => 'NEXUS',
                'name' => 'NEXUS',
                'full_name' => 'NEXUS - International Journal of Informatics and Intelligent Systems',
                'tagline' => 'Connecting Intelligence, Innovation, and Computing.',
                'description_id' => 'Jurnal ilmiah yang berfokus pada penelitian dan pengembangan di bidang informatika, sistem cerdas, kecerdasan buatan, data science, rekayasa perangkat lunak, keamanan siber, jaringan komputer, dan teknologi komputasi baru.',
                'description_en' => 'A scholarly journal focusing on research and development in informatics, intelligent systems, artificial intelligence, data science, software engineering, cybersecurity, computer networks, and emerging computing technologies.',
                'scope' => ['Artificial Intelligence', 'Machine Learning', 'Deep Learning', 'Natural Language Processing', 'Computer Vision', 'Data Science', 'Software Engineering', 'Cybersecurity', 'Computer Networks', 'Intelligent Systems', 'Emerging Computing'],
                'accent_style' => 'nexus',
                'sort_order' => 1,
            ],
            [
                'code' => 'DIGIMAS',
                'name' => 'DIGIMAS',
                'full_name' => 'DIGIMAS - Journal of Digital Innovation and Smart Society',
                'tagline' => 'Digital Innovation for Sustainable Society.',
                'description_id' => 'Jurnal ilmiah yang membahas inovasi digital dan penerapannya dalam membangun masyarakat cerdas, pelayanan publik, pendidikan, kesehatan, bisnis, pemerintahan, dan transformasi digital yang berkelanjutan.',
                'description_en' => 'A scholarly journal addressing digital innovation and its application in developing smart societies, public services, education, healthcare, business, governance, and sustainable digital transformation.',
                'scope' => ['Information Systems', 'Smart Society', 'Smart City', 'Smart Village', 'Digital Transformation', 'Educational Technology', 'Health Informatics', 'E-Government', 'Decision Support Systems', 'Geographic Information Systems', 'Digital Business', 'Applied Computing'],
                'accent_style' => 'digimas',
                'sort_order' => 2,
            ],
            [
                'code' => 'ABDIMATEK',
                'name' => 'ABDIMATEK',
                'full_name' => 'ABDIMATEK - Journal of Community Technology and Digital Empowerment',
                'tagline' => 'Technology for Community Empowerment.',
                'description_id' => 'Jurnal pengabdian kepada masyarakat yang mempublikasikan penerapan teknologi, literasi digital, inovasi tepat guna, pemberdayaan UMKM, smart village, pendidikan teknologi, dan transformasi digital untuk meningkatkan kapasitas masyarakat.',
                'description_en' => 'A community engagement journal publishing technology applications, digital literacy, appropriate innovation, MSME empowerment, smart village initiatives, technology education, and digital transformation for community capacity building.',
                'scope' => ['Community Technology', 'Digital Literacy', 'MSME Digital Empowerment', 'Smart Village', 'Appropriate Technology', 'Artificial Intelligence for Society', 'Cybersecurity Awareness', 'Educational Technology Outreach', 'Digital Public Services', 'Community Information Systems', 'Technology Training', 'STEM Community Education'],
                'accent_style' => 'abdimatek',
                'sort_order' => 3,
            ],
        ];

        foreach ($journals as $journal) {
            DB::table('journals')->updateOrInsert(
                ['slug' => Str::slug($journal['name'])],
                [
                    'code' => $journal['code'],
                    'name' => $journal['name'],
                    'full_name' => $journal['full_name'],
                    'slug' => Str::slug($journal['name']),
                    'slug_id' => Str::slug($journal['name']),
                    'slug_en' => Str::slug($journal['name']),
                    'tagline' => $journal['tagline'],
                    'tagline_id' => $journal['tagline'],
                    'tagline_en' => $journal['tagline'],
                    'description_id' => $journal['description_id'],
                    'description_en' => $journal['description_en'],
                    'scope_id' => implode("\n", $journal['scope']),
                    'scope_en' => implode("\n", $journal['scope']),
                    'publisher' => 'Program Studi Informatika, Fakultas Sains dan Teknologi, UIN Sultan Maulana Hasanuddin Banten',
                    'url' => null,
                    'ojs_url' => null,
                    'print_issn' => null,
                    'electronic_issn' => null,
                    'editor_in_chief' => null,
                    'accreditation_status' => null,
                    'indexing_json' => null,
                    'accent_style' => $journal['accent_style'],
                    'status_label' => 'preparation',
                    'status' => 'preparing',
                    'verification_status' => 'pending',
                    'is_active' => true,
                    'sort_order' => $journal['sort_order'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function expertiseGroups(): void
    {
        $groups = [
            ['Software Engineering and Intelligent Applications', 'Rekayasa Perangkat Lunak dan Aplikasi Cerdas'],
            ['Data Science and Artificial Intelligence', 'Data Science dan Kecerdasan Buatan'],
            ['Cybersecurity and Computer Networks', 'Keamanan Siber dan Jaringan Komputer'],
            ['Internet of Things and Smart Systems', 'Internet of Things dan Sistem Cerdas'],
            ['Digital Innovation for Society', 'Inovasi Digital untuk Masyarakat'],
        ];

        foreach ($groups as $index => [$en, $id]) {
            DB::table('expertise_groups')->updateOrInsert(
                ['slug_id' => Str::slug($id)],
                [
                    'name_id' => $id,
                    'name_en' => $en,
                    'title_id' => $id,
                    'title_en' => $en,
                    'slug' => Str::slug($id),
                    'slug_en' => Str::slug($en),
                    'description_id' => 'Kelompok keahlian awal. Aktifkan setelah disetujui dan dilengkapi admin.',
                    'description_en' => 'Initial expertise group. Activate after admin approval and completion.',
                    'research_focus_id' => 'Menunggu kurasi fokus riset resmi.',
                    'research_focus_en' => 'Official research focus is pending curation.',
                    'icon' => ['Code2', 'Brain', 'ShieldCheck', 'Cpu', 'HeartHandshake'][$index],
                    'sort_order' => $index + 1,
                    'is_active' => false,
                    'status' => 'draft',
                    'verification_status' => 'pending',
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

        $groupId = DB::table('expertise_groups')->where('slug_id', 'data-science-dan-kecerdasan-buatan')->value('id');
        if ($groupId) {
            DB::table('expertise_groups')->where('id', $groupId)->update(['is_active' => true, 'status' => 'published', 'verification_status' => 'verified']);
        }

        DB::table('research_projects')->updateOrInsert(
            ['slug_id' => 'demo-riset-ai-pembelajaran'],
            [
                'title_id' => '[DEMO] Riset AI untuk Pembelajaran Adaptif',
                'title_en' => '[DEMO] AI Research for Adaptive Learning',
                'slug' => 'demo-riset-ai-pembelajaran',
                'slug_en' => 'demo-ai-research-for-adaptive-learning',
                'abstract_id' => 'Data demo lokal untuk menguji modul riset. Jangan gunakan sebagai klaim penelitian resmi.',
                'abstract_en' => 'Local demo data for testing the research module. Do not use as an official research claim.',
                'year' => 2026,
                'lead_researcher_name' => 'Admin Program Studi',
                'team_members_json' => json_encode([['name' => 'Tim Demo', 'role' => 'Member']], JSON_UNESCAPED_UNICODE),
                'expertise_group_id' => $groupId,
                'output_id' => 'Prototipe dan artikel demo.',
                'output_en' => 'Prototype and demo article.',
                'status' => 'completed',
                'verification_status' => 'verified',
                'translation_status' => 'complete',
                'is_featured' => true,
                'is_public' => true,
                'published_at' => now()->subDay(),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        DB::table('publications')->updateOrInsert(
            ['doi' => '10.0000/demo.2026.001'],
            [
                'title' => '[DEMO] Adaptive Learning Prototype in Informatics Education',
                'authors' => 'Admin Program Studi; Tim Demo',
                'publication_type' => 'journal',
                'journal_or_conference' => 'Demo Journal',
                'year' => 2026,
                'url' => 'https://example.com/demo-publication',
                'verification_status' => 'verified',
                'is_featured' => true,
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
