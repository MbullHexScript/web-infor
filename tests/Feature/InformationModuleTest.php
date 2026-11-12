<?php

namespace Tests\Feature;

use App\Services\LegacyContentImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InformationModuleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_information_public_pages_can_be_opened(): void
    {
        foreach ([
            '/id/informasi',
            '/en/information',
            '/id/informasi/berita',
            '/en/information/news',
            '/id/informasi/pengumuman',
            '/en/information/announcements',
            '/id/informasi/agenda',
            '/en/information/events',
            '/id/informasi/galeri',
            '/en/information/gallery',
            '/id/informasi/unduhan',
            '/en/information/downloads',
            '/id/informasi/faq',
            '/en/information/faq',
            '/id/pencarian?q=algoritma',
            '/en/search?q=algorithm',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_news_visibility_filter_search_and_detail(): void
    {
        $categoryId = DB::table('news_categories')->insertGetId([
            'name_id' => 'Akademik',
            'name_en' => 'Academic',
            'title_id' => 'Akademik',
            'title_en' => 'Academic',
            'slug' => 'akademik-test',
            'slug_id' => 'akademik-test',
            'slug_en' => 'academic-test',
            'is_active' => true,
            'status' => 'published',
            'verification_status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('news')->insert([
            $this->newsRow($categoryId, 'Published News', 'published-news', now()->subDay()),
            $this->newsRow($categoryId, 'Draft News', 'draft-news', now()->subDay(), 'draft'),
            $this->newsRow($categoryId, 'Scheduled News', 'scheduled-news', now()->addDay(), 'scheduled'),
        ]);

        $this->get('/id/informasi/berita?q=Published&category=akademik-test')
            ->assertOk()
            ->assertSee('Published News')
            ->assertDontSee('Draft News')
            ->assertDontSee('Scheduled News');

        $this->get('/id/informasi/berita/published-news')
            ->assertOk()
            ->assertSee('Published News')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_announcements_active_expired_and_priority(): void
    {
        DB::table('announcements')->insert([
            $this->announcementRow('Urgent Active', 'urgent-active', 'urgent', now()->subDay(), now()->addDay()),
            $this->announcementRow('Expired', 'expired', 'important', now()->subDays(5), now()->subDay()),
        ]);

        $this->get('/id/informasi/pengumuman')
            ->assertOk()
            ->assertSee('Urgent Active')
            ->assertDontSee('Expired');

        $this->get('/id/informasi/pengumuman/expired')
            ->assertOk()
            ->assertSee('&quot;expired&quot;:true', false);
    }

    public function test_events_status_and_ics(): void
    {
        $eventId = DB::table('events')->insertGetId($this->eventRow('Workshop', 'workshop', now()->addDay(), now()->addDay()->addHours(2)));
        DB::table('events')->insert($this->eventRow('Cancelled', 'cancelled', now()->addDay(), now()->addDay()->addHours(2), 'cancelled'));

        $this->get('/id/informasi/agenda?status=upcoming')->assertOk()->assertSee('Workshop');
        $this->get('/events/'.$eventId.'/ics')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->assertSee('BEGIN:VCALENDAR');
    }

    public function test_gallery_downloads_faq_and_global_search(): void
    {
        Storage::disk('public')->put('documents/test.pdf', 'PDF');
        $categoryId = DB::table('document_categories')->insertGetId([
            'name_id' => 'Kurikulum',
            'name_en' => 'Curriculum',
            'title_id' => 'Kurikulum',
            'title_en' => 'Curriculum',
            'slug' => 'kurikulum-test',
            'slug_id' => 'kurikulum-test',
            'slug_en' => 'curriculum-test',
            'is_active' => true,
            'status' => 'published',
            'verification_status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('faqs')->insert([
            'category' => 'Kurikulum',
            'title_id' => 'Apa itu kurikulum OBE?',
            'title_en' => 'What is OBE curriculum?',
            'question_id' => 'Apa itu kurikulum OBE?',
            'question_en' => 'What is OBE curriculum?',
            'answer_id' => 'Kurikulum berbasis luaran.',
            'answer_en' => 'Outcome-based curriculum.',
            'sort_order' => 1,
            'is_active' => true,
            'is_featured' => true,
            'status' => 'published',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $documentId = DB::table('documents')->insertGetId([
            'category_id' => $categoryId,
            'title_id' => 'Dokumen Algoritma',
            'title_en' => 'Algorithm Document',
            'slug' => 'dokumen-algoritma',
            'slug_id' => 'dokumen-algoritma',
            'slug_en' => 'algorithm-document',
            'description_id' => 'Dokumen publik.',
            'description_en' => 'Public document.',
            'file_id' => 'documents/test.pdf',
            'file_name' => 'test.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 3,
            'file_type' => 'pdf',
            'year' => 2026,
            'document_status' => 'current',
            'is_public' => true,
            'status' => 'published',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/id/informasi/unduhan?q=Algoritma')->assertOk()->assertSee('Dokumen Algoritma');
        $this->get('/documents/'.$documentId.'/download')->assertOk();
        $this->assertSame(1, DB::table('documents')->where('id', $documentId)->value('download_count'));
        $this->get('/id/informasi/faq?q=kurikulum')->assertOk()->assertSee('Kurikulum');
        $this->get('/id/pencarian?q=Algoritma')->assertOk()->assertSee('Dokumen Algoritma');
    }

    public function test_legacy_import_dry_run_is_safe_and_commit_is_idempotent(): void
    {
        Http::fake([
            'informatika.fsainsuinbanten.my.id/*' => Http::response('<html><a href="https://informatika.fsainsuinbanten.my.id/2026/04/06/artificial-intelligence-mengubah-cara-mahasiswa-mengembangkan-software/">Artificial Intelligence Mengubah Cara Mahasiswa Mengembangkan Software</a></html>'),
        ]);

        $importer = app(LegacyContentImporter::class);
        $dryRun = $importer->import('news', dryRun: true);
        $this->assertTrue($dryRun['dry_run']);
        $this->assertDatabaseCount('content_migrations', 0);

        DB::table('content_migrations')->insert([
            'source_url' => 'https://informatika.fsainsuinbanten.my.id/sample/',
            'content_type' => 'news',
            'target_path' => '/id/informasi/berita/sample',
            'verification_status' => 'pending',
            'translation_status' => 'pending',
            'media_migration_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('content_migrations', ['source_url' => 'https://informatika.fsainsuinbanten.my.id/sample/']);
    }

    private function newsRow(int $categoryId, string $title, string $slug, mixed $publishedAt, string $status = 'published'): array
    {
        return [
            'category_id' => $categoryId,
            'title_id' => $title,
            'title_en' => $title,
            'slug' => $slug,
            'slug_id' => $slug,
            'slug_en' => $slug.'-en',
            'excerpt_id' => 'Ringkasan '.$title,
            'excerpt_en' => 'Excerpt '.$title,
            'content_id' => '<p>Isi berita aman.</p><script>alert(1)</script>',
            'content_en' => '<p>Safe content.</p>',
            'status' => $status,
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'published_at' => $publishedAt,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function announcementRow(string $title, string $slug, string $priority, mixed $startsAt, mixed $expiresAt): array
    {
        return [
            'title_id' => $title,
            'title_en' => $title,
            'slug' => $slug,
            'slug_id' => $slug,
            'slug_en' => $slug.'-en',
            'content_id' => '<p>Isi pengumuman.</p>',
            'content_en' => '<p>Announcement body.</p>',
            'excerpt_id' => 'Ringkasan',
            'priority' => $priority,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'status' => 'published',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function eventRow(string $title, string $slug, mixed $startAt, mixed $endAt, ?string $override = null): array
    {
        return [
            'title_id' => $title,
            'title_en' => $title,
            'slug' => $slug,
            'slug_id' => $slug,
            'slug_en' => $slug.'-en',
            'description_id' => 'Deskripsi agenda.',
            'description_en' => 'Event description.',
            'start_at' => $startAt,
            'end_at' => $endAt,
            'timezone' => 'Asia/Jakarta',
            'location_id' => 'Kampus',
            'location_en' => 'Campus',
            'organizer_id' => 'Prodi',
            'organizer_en' => 'Study Program',
            'status_override' => $override,
            'status' => 'published',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'is_public' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
