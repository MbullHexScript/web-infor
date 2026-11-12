<?php

namespace Tests\Feature;

use App\Services\LegacyResearchImporter;
use Database\Seeders\ResearchSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ResearchModuleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_research_public_routes_are_available(): void
    {
        foreach ([
            '/id/riset', '/en/research',
            '/id/riset/penelitian', '/en/research/projects',
            '/id/riset/pengabdian', '/en/research/community-service',
            '/id/riset/publikasi', '/en/research/publications',
            '/id/riset/produk', '/en/research/products',
            '/id/riset/hki', '/en/research/intellectual-property',
            '/id/riset/kelompok-keahlian', '/en/research/expertise-groups',
            '/id/riset/rumah-jurnal', '/en/research/journals',
            '/id/riset/kerja-sama', '/en/research/partnerships',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_research_visibility_filter_and_detail(): void
    {
        $groupId = DB::table('expertise_groups')->insertGetId($this->groupRow());
        DB::table('research_projects')->insert([
            $this->researchRow($groupId, 'Published Research', 'published-research', 'verified', true),
            $this->researchRow($groupId, 'Draft Research', 'draft-research', 'verified', false, 'draft'),
            $this->researchRow($groupId, 'Unverified Research', 'unverified-research', 'pending', true),
        ]);

        $this->get('/id/riset/penelitian?year=2026&group=data-ai-test')
            ->assertOk()
            ->assertSee('Published Research')
            ->assertDontSee('Draft Research')
            ->assertDontSee('Unverified Research');

        $this->get('/id/riset/penelitian/published-research')
            ->assertOk()
            ->assertSee('Published Research')
            ->assertSee('Luaran demo');
    }

    public function test_publications_search_and_citation_exports(): void
    {
        $id = DB::table('publications')->insertGetId([
            'title' => 'Adaptive Algorithm Publication',
            'authors' => 'A. Researcher; B. Author',
            'publication_type' => 'journal',
            'journal_or_conference' => 'Demo Journal',
            'year' => 2026,
            'doi' => '10.1000/demo.2026.1',
            'verification_status' => 'verified',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/id/riset/publikasi?q=Algorithm&type=journal')
            ->assertOk()
            ->assertSee('Adaptive Algorithm Publication');

        $this->get('/research/publications/'.$id.'/citation/bibtex')->assertOk()->assertSee('@article');
        $this->get('/research/publications/'.$id.'/citation/ris')->assertOk()->assertSee('TY  - JOUR');
    }

    public function test_journals_seed_preparing_and_detail_are_safe(): void
    {
        $this->seed(ResearchSeeder::class);

        $this->assertDatabaseHas('journals', ['code' => 'NEXUS', 'status' => 'preparing', 'print_issn' => null]);
        $this->assertDatabaseHas('journals', ['code' => 'DIGIMAS', 'status' => 'preparing']);
        $this->assertDatabaseHas('journals', ['code' => 'ABDIMATEK', 'status' => 'preparing']);

        $this->get('/id/riset/rumah-jurnal/nexus')
            ->assertOk()
            ->assertSee('&quot;status&quot;:&quot;preparing&quot;', false)
            ->assertSee('&quot;ojsUrl&quot;:null', false);
    }

    public function test_products_ip_and_community_service_visibility(): void
    {
        DB::table('community_services')->insert($this->serviceRow());
        DB::table('digital_products')->insert($this->productRow());
        DB::table('intellectual_properties')->insert($this->ipRow());

        $this->get('/id/riset/pengabdian?location=Serang')->assertOk()->assertSee('Pengabdian Demo');
        $this->get('/id/riset/produk?type=web_application')->assertOk()->assertSee('Produk Demo');
        $this->get('/id/riset/hki?type=copyright')->assertOk()->assertSee('HKI Demo');
    }

    public function test_legacy_research_dry_run_is_safe(): void
    {
        Http::fake([
            '*' => Http::response('<a href="https://informatika.fsainsuinbanten.my.id/pengabdian-demo/">Pengabdian Teknologi Masyarakat</a>'),
        ]);

        $report = app(LegacyResearchImporter::class)->import('community-service', dryRun: true);
        $this->assertTrue($report['dry_run']);
        $this->assertDatabaseMissing('content_migrations', ['content_type' => 'research:community-service']);
    }

    private function groupRow(): array
    {
        return [
            'name_id' => 'Data AI Test',
            'name_en' => 'Data AI Test',
            'title_id' => 'Data AI Test',
            'title_en' => 'Data AI Test',
            'slug' => 'data-ai-test',
            'slug_id' => 'data-ai-test',
            'slug_en' => 'data-ai-test',
            'description_id' => 'Fokus data.',
            'description_en' => 'Data focus.',
            'research_focus_id' => 'AI',
            'research_focus_en' => 'AI',
            'is_active' => true,
            'status' => 'published',
            'verification_status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function researchRow(int $groupId, string $title, string $slug, string $verification, bool $public, string $status = 'completed'): array
    {
        return [
            'title_id' => $title,
            'title_en' => $title,
            'slug' => $slug,
            'slug_id' => $slug,
            'slug_en' => $slug.'-en',
            'abstract_id' => 'Abstrak demo',
            'abstract_en' => 'Demo abstract',
            'year' => 2026,
            'lead_researcher_name' => 'Ketua Demo',
            'team_members_json' => json_encode([['name' => 'Anggota Demo', 'role' => 'Member']]),
            'expertise_group_id' => $groupId,
            'output_id' => 'Luaran demo',
            'output_en' => 'Demo output',
            'status' => $status,
            'verification_status' => $verification,
            'translation_status' => 'complete',
            'is_public' => $public,
            'published_at' => $public ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function serviceRow(): array
    {
        return [
            'title_id' => 'Pengabdian Demo',
            'title_en' => 'Demo Community Service',
            'slug' => 'pengabdian-demo',
            'slug_id' => 'pengabdian-demo',
            'slug_en' => 'demo-community-service',
            'description_id' => 'Deskripsi.',
            'description_en' => 'Description.',
            'year' => 2026,
            'location_id' => 'Serang',
            'location_en' => 'Serang',
            'lead_person_name' => 'Ketua',
            'status' => 'completed',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'is_public' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function productRow(): array
    {
        return [
            'title_id' => 'Produk Demo',
            'title_en' => 'Demo Product',
            'slug' => 'produk-demo',
            'slug_id' => 'produk-demo',
            'slug_en' => 'demo-product',
            'description_id' => 'Produk.',
            'description_en' => 'Product.',
            'product_type' => 'web_application',
            'year' => 2026,
            'technologies_json' => json_encode(['Laravel', 'React']),
            'status' => 'active',
            'verification_status' => 'verified',
            'is_public' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function ipRow(): array
    {
        return [
            'title' => 'HKI Demo',
            'type' => 'copyright',
            'inventors' => 'Inventor Demo',
            'registration_date' => '2026-01-01',
            'verification_status' => 'verified',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
