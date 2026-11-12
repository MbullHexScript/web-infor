<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LegacyStudentLifeImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentLifeModuleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_student_life_public_routes_are_available(): void
    {
        foreach ([
            '/id/kemahasiswaan', '/en/student-life',
            '/id/kemahasiswaan/kehidupan-mahasiswa', '/en/student-life/campus-life',
            '/id/kemahasiswaan/prestasi', '/en/student-life/achievements',
            '/id/kemahasiswaan/beasiswa', '/en/student-life/scholarships',
            '/id/kemahasiswaan/kompetisi', '/en/student-life/competitions',
            '/id/kemahasiswaan/mbkm-magang', '/en/student-life/mbkm-internships',
            '/id/kemahasiswaan/alumni', '/en/student-life/alumni',
            '/id/kemahasiswaan/hmps', '/en/student-life/student-association',
            '/id/fasilitas', '/en/facilities',
            '/id/pmb', '/en/admissions',
            '/id/kontak', '/en/contact',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_achievement_visibility_filters_and_privacy(): void
    {
        DB::table('achievements')->insert([
            $this->achievementRow('Prestasi Nasional Demo', 'prestasi-nasional-demo', 'verified', true, true),
            $this->achievementRow('Prestasi Pending', 'prestasi-pending', 'pending', true, true),
            $this->achievementRow('Prestasi Private', 'prestasi-private', 'verified', false, true),
        ]);

        $this->get('/id/kemahasiswaan/prestasi?level=national&year=2026&q=Nasional')
            ->assertOk()
            ->assertSee('Prestasi Nasional Demo')
            ->assertDontSee('Prestasi Pending')
            ->assertDontSee('Prestasi Private')
            ->assertDontSee('1234567890')
            ->assertDontSee('certificate-private.pdf');

        $this->get('/id/kemahasiswaan/prestasi/prestasi-nasional-demo')
            ->assertOk()
            ->assertSee('Prestasi Nasional Demo')
            ->assertDontSee('certificate-private.pdf');
    }

    public function test_scholarship_competition_facility_and_alumni_rules(): void
    {
        DB::table('scholarships')->insert($this->scholarshipRow());
        DB::table('competitions')->insert($this->competitionRow());
        DB::table('facilities')->insert($this->facilityRow());
        DB::table('alumni_stories')->insert([
            $this->alumniRow('Alumni Consent', 'alumni-consent', true),
            $this->alumniRow('Alumni No Consent', 'alumni-no-consent', false),
        ]);

        $this->get('/id/kemahasiswaan/beasiswa?status=open')->assertOk()->assertSee('Beasiswa Demo');
        $this->get('/id/kemahasiswaan/kompetisi?level=national')->assertOk()->assertSee('Kompetisi Demo');
        $this->get('/id/fasilitas?category=Laboratory')->assertOk()->assertSee('Lab Demo');
        $this->get('/id/kemahasiswaan/alumni')
            ->assertOk()
            ->assertSee('Alumni Consent')
            ->assertDontSee('Alumni No Consent')
            ->assertDontSee('08123456789');
    }

    public function test_contact_form_stores_message_and_mail_failure_is_safe(): void
    {
        Mail::shouldReceive('raw')->andThrow(new \RuntimeException('mail down'));

        $this->post('/id/kontak', [
            'name' => 'Pengirim Demo',
            'email' => 'sender@example.test',
            'phone' => '081234',
            'category' => 'admissions',
            'subject' => 'Pertanyaan PMB',
            'message' => 'Saya ingin bertanya tentang informasi PMB yang resmi dan terverifikasi.',
            'consent' => '1',
            'website' => '',
        ])->assertSessionHas('status');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'sender@example.test',
            'category' => 'admissions',
            'status' => 'new',
        ]);
    }

    public function test_contact_honeypot_invalid_email_and_admin_inbox_status(): void
    {
        $this->post('/id/kontak', [
            'name' => 'Bot',
            'email' => 'not-an-email',
            'category' => 'general',
            'subject' => 'Spam',
            'message' => 'Pendek.',
            'consent' => '1',
            'website' => 'filled',
        ])->assertSessionHasErrors(['email', 'message', 'website']);

        $messageId = DB::table('contact_messages')->insertGetId([
            'name' => 'Admin Test',
            'email' => 'admin-test@example.test',
            'category' => 'general',
            'subject' => 'Test',
            'message' => 'Isi pesan admin test.',
            'locale' => 'id',
            'status' => 'new',
            'verification_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::factory()->create(['two_factor_confirmed_at' => now()]);

        $this->actingAs($user)->get('/admin/contact')->assertOk()->assertSee('Test');
        $this->actingAs($user)->post('/admin/contact/messages/'.$messageId.'/read')->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['id' => $messageId, 'status' => 'read']);
        $this->actingAs($user)->post('/admin/contact/messages/'.$messageId.'/spam')->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['id' => $messageId, 'status' => 'spam', 'spam_score' => 100]);
    }

    public function test_legacy_student_life_dry_run_is_safe_and_import_idempotent(): void
    {
        $importer = app(LegacyStudentLifeImporter::class);

        $dryRun = $importer->import('achievements', dryRun: true);
        $this->assertTrue($dryRun['dry_run']);
        $this->assertDatabaseMissing('content_migrations', ['content_type' => 'student-life:achievements']);

        $first = $importer->import('achievements', dryRun: false);
        $second = $importer->import('achievements', dryRun: false);

        $this->assertSame(1, $first['created_or_updated']);
        $this->assertSame(1, $second['duplicates']);
        $this->assertDatabaseHas('content_migrations', ['content_type' => 'student-life:achievements']);
    }

    private function achievementRow(string $title, string $slug, string $verification, bool $public, bool $published): array
    {
        return [
            'title_id' => $title,
            'title_en' => $title,
            'slug' => $slug,
            'slug_id' => $slug,
            'slug_en' => $slug.'-en',
            'achievement_type' => 'student',
            'recipient_name' => 'Tim Mahasiswa',
            'student_identifiers_json' => json_encode(['nim' => '1234567890']),
            'level' => 'national',
            'organizer' => 'Demo Organizer',
            'year' => 2026,
            'rank_or_award_id' => 'Juara Demo',
            'rank_or_award_en' => 'Demo Winner',
            'description_id' => 'Deskripsi prestasi.',
            'description_en' => 'Achievement description.',
            'certificate_id' => 'certificate-private.pdf',
            'certificate_is_public' => false,
            'status' => 'published',
            'verification_status' => $verification,
            'translation_status' => 'complete',
            'is_featured' => true,
            'is_public' => $public,
            'published_at' => $published ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function scholarshipRow(): array
    {
        return [
            'name_id' => 'Beasiswa Demo',
            'name_en' => 'Demo Scholarship',
            'slug' => 'beasiswa-demo',
            'slug_id' => 'beasiswa-demo',
            'slug_en' => 'demo-scholarship',
            'provider_id' => 'Provider Demo',
            'provider_en' => 'Demo Provider',
            'description_id' => 'Deskripsi beasiswa.',
            'description_en' => 'Scholarship description.',
            'requirements_id' => 'Persyaratan resmi.',
            'status' => 'open',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function competitionRow(): array
    {
        return [
            'title_id' => 'Kompetisi Demo',
            'title_en' => 'Demo Competition',
            'slug' => 'kompetisi-demo',
            'slug_id' => 'kompetisi-demo',
            'slug_en' => 'demo-competition',
            'description_id' => 'Deskripsi kompetisi.',
            'description_en' => 'Competition description.',
            'organizer' => 'Organizer Demo',
            'level' => 'national',
            'status' => 'registration_open',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function facilityRow(): array
    {
        return [
            'name_id' => 'Lab Demo',
            'name_en' => 'Demo Lab',
            'slug' => 'lab-demo',
            'slug_id' => 'lab-demo',
            'slug_en' => 'demo-lab',
            'category' => 'Laboratory',
            'description_id' => 'Fasilitas demo.',
            'description_en' => 'Demo facility.',
            'location_id' => 'Gedung Demo',
            'location_en' => 'Demo Building',
            'features_id' => 'Fitur demo',
            'features_en' => 'Demo feature',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function alumniRow(string $name, string $slug, bool $consent): array
    {
        return [
            'alumni_name' => $name,
            'slug' => $slug,
            'graduation_year' => 2026,
            'current_position_id' => 'Developer',
            'company' => 'Perusahaan Demo',
            'story_id' => 'Cerita alumni.',
            'quote_id' => 'Kutipan alumni.',
            'metadata' => json_encode(['phone' => '08123456789']),
            'consent_verified' => $consent,
            'status' => 'published',
            'verification_status' => 'verified',
            'translation_status' => 'complete',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
