<?php

namespace Tests\Feature;

use App\Services\SintesaIntegrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SintesaIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.sintesa.url', 'https://sintesa.test/integration.php');
        config()->set('services.sintesa.token', 'test-secret');
    }

    public function test_catalog_dry_run_reads_sintesa_without_writing(): void
    {
        $this->fakeCatalog();

        $report = app(SintesaIntegrationService::class)->sync(true, 'catalog');

        $this->assertSame(['curricula' => 1, 'courses' => 1], $report['catalog']);
        $this->assertDatabaseCount('curricula', 0);
        $this->assertDatabaseCount('courses', 0);
        Http::assertSent(fn (Request $request): bool => $request->hasHeader('X-SINTESA-API-KEY', 'test-secret'));
    }

    public function test_catalog_sync_reuses_same_year_and_preserves_editorial_metadata(): void
    {
        $this->fakeCatalog();
        $curriculumId = DB::table('curricula')->insertGetId([
            'code' => 'LOCAL-2026',
            'name_id' => 'Kurikulum Lokal',
            'year' => 2026,
            'metadata' => json_encode(['workbook' => 'curriculum.xlsx']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('courses')->insert([
            'curriculum_id' => $curriculumId,
            'code' => 'IF101',
            'name_id' => 'Nama Lama',
            'description_id' => 'Deskripsi editorial tetap dipertahankan.',
            'metadata' => json_encode(['editorial' => true]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        app(SintesaIntegrationService::class)->sync(false, 'catalog');

        $this->assertDatabaseCount('curricula', 1);
        $this->assertDatabaseHas('curricula', ['id' => $curriculumId, 'code' => 'INF-2026']);
        $this->assertDatabaseCount('courses', 1);
        $this->assertDatabaseHas('courses', [
            'curriculum_id' => $curriculumId,
            'code' => 'IF101',
            'name_id' => 'Algoritma',
            'description_id' => 'Deskripsi editorial tetap dipertahankan.',
        ]);
        $this->assertStringContainsString('curriculum.xlsx', (string) DB::table('curricula')->value('metadata'));
        $this->assertStringContainsString('SINTESA', (string) DB::table('courses')->value('metadata'));
    }

    public function test_lecturer_sync_matches_legacy_name_and_preserves_photo_and_relationships(): void
    {
        Http::fake(fn () => Http::response([
            'data' => [[
                'id' => '42',
                'sourceCode' => 'INF-42',
                'nidn' => '0011223344',
                'name' => 'Dosen Informatika',
                'email' => 'dosen@uinbanten.ac.id',
                'academicRank' => 'Lektor',
                'lecturerType' => 'Dosen Tetap',
                'status' => 'Aktif',
                'expertiseSummary' => 'Data Science',
                'researchInterests' => ['data'],
            ]],
            'meta' => ['lastPage' => 1],
        ], 200));
        $lecturerId = DB::table('lecturers')->insertGetId([
            'full_name' => 'Dosen Informatika',
            'slug' => 'dosen-informatika',
            'photo_id' => 'lecturers/legacy/dosen.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('management_positions')->insert([
            'lecturer_id' => $lecturerId,
            'title_id' => 'Ketua Program Studi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        app(SintesaIntegrationService::class)->sync(false, 'lecturers');

        $this->assertDatabaseCount('lecturers', 1);
        $this->assertDatabaseHas('lecturers', [
            'id' => $lecturerId,
            'nidn' => '0011223344',
            'photo_id' => 'lecturers/legacy/dosen.jpg',
            'verification_status' => 'verified',
        ]);
        $this->assertDatabaseHas('management_positions', ['lecturer_id' => $lecturerId]);
    }

    private function fakeCatalog(): void
    {
        Http::fake(fn () => Http::response([
            'data' => [
                'curricula' => [[
                    'code' => 'INF-2026',
                    'name' => 'Kurikulum Informatika 2026',
                    'curriculum_year' => 2026,
                    'total_sks' => 144,
                    'effective_from_cohort' => 2026,
                    'status' => 'Aktif',
                ]],
                'courses' => [[
                    'course_code' => 'IF101',
                    'course_name' => 'Algoritma',
                    'sks' => 3,
                    'semester' => 1,
                    'course_type' => 'Wajib',
                    'status' => 'Aktif',
                    'curriculum_code' => 'INF-2026',
                ]],
            ],
        ], 200));
    }
}
