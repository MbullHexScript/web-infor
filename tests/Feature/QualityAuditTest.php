<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class QualityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_quality_dashboard_routes_are_available_for_admin(): void
    {
        $user = User::factory()->create();

        foreach ([
            '/admin/quality',
            '/admin/quality/content-verification',
            '/admin/quality/rps-coverage',
        ] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }

    public function test_phase_nine_audit_commands_run_without_mutating_content(): void
    {
        $this->artisan('translations:audit')->assertSuccessful();
        $this->artisan('db:audit-performance')->assertSuccessful();
        $this->artisan('quality:audit')->assertSuccessful();

        $this->assertTrue(File::exists(storage_path('logs/audits/translations-audit.json')));
        $this->assertTrue(File::exists(storage_path('logs/audits/database-performance-audit.json')));
        $this->assertTrue(File::exists(storage_path('logs/audits/quality-audit.json')));
    }
}
