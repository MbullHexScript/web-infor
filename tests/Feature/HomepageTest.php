<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\FoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(FoundationSeeder::class);
        Cache::flush();
    }

    public function test_homepage_id_can_be_opened(): void
    {
        $this->get('/id')
            ->assertOk()
            ->assertSee('PROGRAM STUDI INFORMATIKA');
    }

    public function test_homepage_en_can_be_opened(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('INFORMATICS STUDY PROGRAM');
    }

    public function test_only_active_sections_are_sent_to_homepage(): void
    {
        DB::table('homepage_sections')->where('key', 'about')->update(['is_active' => false]);
        Cache::forget('homepage.id');

        $this->get('/id')
            ->assertOk()
            ->assertDontSee('Tentang Informatika');
    }

    public function test_expired_announcements_are_not_displayed(): void
    {
        DB::table('announcements')->insert([
            'title_id' => 'Pengumuman kedaluwarsa',
            'title_en' => 'Expired announcement',
            'slug' => 'expired-announcement',
            'status' => 'published',
            'verification_status' => 'verified',
            'metadata' => json_encode([
                'priority' => 'urgent',
                'starts_at' => now()->subDays(4)->toDateString(),
                'expires_at' => now()->subDay()->toDateString(),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/id')
            ->assertOk()
            ->assertDontSee('Pengumuman kedaluwarsa');
    }

    public function test_inactive_statistics_are_not_displayed(): void
    {
        $this->get('/id')
            ->assertOk()
            ->assertDontSee('Mahasiswa Aktif');
    }

    public function test_only_verified_published_lecturers_are_displayed(): void
    {
        DB::table('lecturers')->insert([
            [
                'title_id' => 'Dosen Terverifikasi',
                'title_en' => 'Verified Lecturer',
                'slug' => 'dosen-terverifikasi',
                'status' => 'published',
                'verification_status' => 'verified',
                'metadata' => json_encode(['expertise_id' => 'Data Science', 'expertise_en' => 'Data Science']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_id' => 'Dosen Draft',
                'title_en' => 'Draft Lecturer',
                'slug' => 'dosen-draft',
                'status' => 'draft',
                'verification_status' => 'pending',
                'metadata' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get('/id')
            ->assertOk()
            ->assertSee('Dosen Terverifikasi')
            ->assertDontSee('Dosen Draft');
    }

    public function test_only_active_journals_are_displayed(): void
    {
        DB::table('journals')->where('slug', 'digimas')->update(['is_active' => false]);

        $this->get('/id')
            ->assertOk()
            ->assertSee('NEXUS')
            ->assertDontSee('DIGIMAS');
    }

    public function test_homepage_data_is_cached(): void
    {
        $this->assertFalse(Cache::has('homepage.id'));

        $this->get('/id')->assertOk();

        $this->assertTrue(Cache::has('homepage.id'));
    }

    public function test_admin_update_invalidates_homepage_cache(): void
    {
        $this->get('/id')->assertOk();
        $this->assertTrue(Cache::has('homepage.id'));

        $user = User::factory()->create();
        $sections = DB::table('homepage_sections')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (object $section): array => [
                'key' => $section->key,
                'title_id' => $section->title_id,
                'title_en' => $section->title_en,
                'subtitle_id' => $section->subtitle_id,
                'subtitle_en' => $section->subtitle_en,
                'background_style' => $section->background_style,
                'is_active' => (bool) $section->is_active,
                'sort_order' => $section->sort_order,
            ])
            ->all();

        $this->actingAs($user)
            ->patch(route('admin.homepage.sections.update'), ['sections' => $sections])
            ->assertRedirect();

        $this->assertFalse(Cache::has('homepage.id'));
        $this->assertFalse(Cache::has('homepage.en'));
    }

    public function test_quick_link_external_state_is_sent_safely(): void
    {
        $this->get('/id')
            ->assertOk()
            ->assertSee('&quot;isExternal&quot;:true', false);
    }
}
