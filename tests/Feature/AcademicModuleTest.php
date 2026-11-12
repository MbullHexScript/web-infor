<?php

namespace Tests\Feature;

use App\Services\CurriculumWorkbookImporter;
use Database\Seeders\AcademicSeeder;
use Database\Seeders\FoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AcademicModuleTest extends TestCase
{
    use RefreshDatabase;

    private string $workbook;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workbook = config('academic.curriculum_import_path');
    }

    public function test_curriculum_inspect_reads_workbook(): void
    {
        $payload = app(CurriculumWorkbookImporter::class)->inspect($this->workbook);

        $this->assertSame(65, $payload['summary']['course_count']);
        $this->assertSame(144, $payload['summary']['student_total_credits']);
        $this->assertSame(3, $payload['summary']['specializations_count']);
        $this->assertSame(4, $payload['summary']['profiles_count']);
        $this->assertSame([], $payload['summary']['errors']);
    }

    public function test_dry_run_does_not_write_curriculum_data(): void
    {
        app(CurriculumWorkbookImporter::class)->import($this->workbook, dryRun: true);

        $this->assertDatabaseCount('curricula', 0);
        $this->assertDatabaseCount('courses', 0);
        $this->assertDatabaseCount('curriculum_imports', 1);
    }

    public function test_import_commit_creates_curriculum_and_is_idempotent(): void
    {
        $importer = app(CurriculumWorkbookImporter::class);
        $importer->import($this->workbook);
        $importer->import($this->workbook, updateExisting: true);

        $this->assertDatabaseCount('curricula', 1);
        $this->assertDatabaseCount('specializations', 3);
        $this->assertDatabaseCount('graduate_profiles', 4);
        $this->assertDatabaseCount('courses', 65);
        $this->assertSame(144, (int) DB::table('curricula')->value('total_credits'));
    }

    public function test_public_curriculum_and_courses_are_available(): void
    {
        $this->seed([FoundationSeeder::class, AcademicSeeder::class]);

        $this->get('/id/akademik/kurikulum')
            ->assertOk()
            ->assertSee('Kurikulum OBE 2026');

        $this->get('/id/akademik/mata-kuliah?semester=1')
            ->assertOk()
            ->assertSee('Algoritma dan Pemrograman I')
            ->assertSee('student_package_credits');
    }

    public function test_course_detail_and_specialization_routes_work(): void
    {
        $this->seed([FoundationSeeder::class, AcademicSeeder::class]);

        $course = DB::table('courses')->where('code', 'G03261402')->first();
        $specialization = DB::table('specializations')->where('code', 'PM02')->first();

        $this->get('/id/akademik/mata-kuliah/'.$course->slug_id)
            ->assertOk()
            ->assertSee($course->name_id);

        $this->get('/id/akademik/peminatan/'.$specialization->slug_id)
            ->assertOk()
            ->assertSee($specialization->name_id);
    }

    public function test_rps_repository_hides_draft_documents(): void
    {
        $this->seed([FoundationSeeder::class, AcademicSeeder::class]);
        $course = DB::table('courses')->first();

        DB::table('rps_documents')->insert([
            'course_id' => $course->id,
            'curriculum_id' => $course->curriculum_id,
            'title_id' => 'RPS Draft',
            'title_en' => 'Draft Course Plan',
            'slug' => 'rps-draft',
            'status' => 'draft',
            'verification_status' => 'verified',
            'is_public' => false,
            'is_current' => true,
            'file_size' => 100,
            'download_count' => 0,
            'metadata' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/id/akademik/rps')
            ->assertOk()
            ->assertDontSee('RPS Draft');
    }
}
