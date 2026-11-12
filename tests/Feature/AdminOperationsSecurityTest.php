<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminOperationsSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_operations_routes_are_protected_and_available(): void
    {
        $this->get('/admin/media')->assertRedirect('/admin/login');

        $user = User::factory()->create();
        foreach ([
            '/admin',
            '/admin/media',
            '/admin/seo',
            '/admin/redirects',
            '/admin/settings',
            '/admin/settings/navigation',
            '/admin/settings/social-links',
            '/admin/audit-log',
            '/admin/account',
            '/admin/operations',
            '/admin/quality',
            '/admin/quality/content-verification',
            '/admin/quality/rps-coverage',
        ] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }

    public function test_media_upload_accepts_real_image_and_logs_audit(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/media', [
            'file' => $this->tinyPngUpload('lab.png'),
            'type' => 'image',
        ])->assertRedirect();

        $this->assertDatabaseHas('media', ['original_name' => 'lab.png', 'mime_type' => 'image/png']);
        $this->assertDatabaseHas('admin_activity_logs', ['action' => 'upload', 'module' => 'media']);
    }

    public function test_upload_hardening_rejects_php_fake_image_and_invalid_pdf(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/media', [
            'file' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
        ])->assertSessionHasErrors('file');

        $this->actingAs($user)->post('/admin/media', [
            'file' => UploadedFile::fake()->create('fake.jpg', 1, 'image/jpeg'),
            'type' => 'image',
        ])->assertSessionHasErrors('file');

        $this->actingAs($user)->post('/admin/media', [
            'file' => UploadedFile::fake()->create('broken.pdf', 1, 'application/pdf'),
            'type' => 'pdf',
        ])->assertSessionHasErrors('file');
    }

    public function test_used_media_cannot_be_hard_deleted(): void
    {
        $user = User::factory()->create();
        $mediaId = DB::table('media')->insertGetId([
            'title_id' => 'Used',
            'slug' => 'used',
            'original_name' => 'used.png',
            'stored_name' => 'used.png',
            'path' => 'media/used.png',
            'disk' => 'public',
            'mime_type' => 'image/png',
            'extension' => 'png',
            'file_size' => 100,
            'checksum' => 'abc',
            'status' => 'uploaded',
            'verification_status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('media_usages')->insert([
            'media_id' => $mediaId,
            'mediable_type' => 'news',
            'mediable_id' => 1,
            'field_name' => 'featured_image',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)->delete('/admin/media/'.$mediaId)->assertStatus(422);
    }

    public function test_redirect_validation_and_runtime_redirect(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/redirects', [
            'old_path' => '/lama',
            'new_path' => '/id',
            'redirect_code' => 301,
        ])->assertRedirect();

        $this->actingAs($user)->post('/admin/redirects', [
            'old_path' => '/same',
            'new_path' => '/same',
            'redirect_code' => 301,
        ])->assertSessionHasErrors('new_path');

        $this->actingAs($user)->post('/admin/redirects', [
            'old_path' => '/bad',
            'new_path' => 'javascript:alert(1)',
            'redirect_code' => 301,
        ])->assertSessionHasErrors('new_path');

        $this->get('/lama')->assertRedirect('/id');
        $this->assertDatabaseHas('legacy_redirects', ['old_path' => '/lama', 'hit_count' => 1]);
    }

    public function test_security_headers_admin_noindex_and_health(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Content-Security-Policy');

        $this->get('/health')->assertOk()->assertJsonPath('status', 'ok');
    }

    public function test_sitemap_backup_cleanup_and_audit_commands(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();
        $this->artisan('sitemap:validate')->assertSuccessful();
        $this->artisan('seo:audit')->assertSuccessful();
        $this->artisan('redirects:audit')->assertSuccessful();
        $this->artisan('links:audit')->assertSuccessful();
        $this->artisan('media:audit')->assertSuccessful();
        $this->artisan('media:find-orphans')->assertSuccessful();
        $this->artisan('media:cleanup --dry-run')->assertSuccessful();
        $this->artisan('backup:run')->assertSuccessful();
        $this->artisan('backup:verify')->assertSuccessful();
        $this->artisan('backup:list')->assertSuccessful();
        $this->artisan('app:cleanup --dry-run')->assertSuccessful();
    }

    public function test_two_factor_required_for_enabled_admin_and_recovery_codes(): void
    {
        $user = User::factory()->create([
            'two_factor_secret' => app('pragmarx.google2fa')->generateSecretKey(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($user)->get('/admin')->assertRedirect(route('two-factor.challenge'));

        $plain = User::factory()->create();
        $this->actingAs($plain)->post('/admin/account/recovery-codes')->assertRedirect();
        $this->assertNotNull($plain->fresh()->two_factor_recovery_codes);
        $this->assertDatabaseMissing('admin_activity_logs', ['new_values_json' => $plain->fresh()->two_factor_recovery_codes]);
    }

    private function tinyPngUpload(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
