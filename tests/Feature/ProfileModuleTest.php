<?php

namespace Tests\Feature;

use App\Services\LegacyLecturerImporter;
use Database\Seeders\AcademicSeeder;
use Database\Seeders\FoundationSeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProfileModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([FoundationSeeder::class, AcademicSeeder::class, ProfileSeeder::class]);
    }

    public function test_profile_pages_can_be_opened_in_id_and_en(): void
    {
        $this->get('/id/profil')
            ->assertOk()
            ->assertSee('Profil Program Studi Informatika');

        $this->get('/en/profile')
            ->assertOk()
            ->assertSee('Informatics Study Program Profile');
    }

    public function test_about_history_vision_and_head_message_pages_are_available(): void
    {
        $this->get('/id/profil/tentang')->assertOk()->assertSee('Tentang Informatika');
        $this->get('/id/profil/sejarah')->assertOk()->assertSee('Keputusan Menteri');
        $this->get('/id/profil/visi-misi-tujuan')->assertOk()->assertSee('Visi');
        $this->get('/id/profil/sambutan')->assertOk()->assertSee('Selamat datang');
    }

    public function test_lecturer_directory_search_and_detail_work(): void
    {
        $this->get('/id/profil/dosen?q=Ahmad')
            ->assertOk()
            ->assertSee('Ahmad Tabrani')
            ->assertDontSee('Nama Tidak Ada');

        $lecturer = DB::table('lecturers')->where('slug', 'ahmad-tabrani')->first();

        $this->get('/id/profil/dosen/'.$lecturer->slug)
            ->assertOk()
            ->assertSee($lecturer->full_name);
    }

    public function test_management_staff_graduate_profiles_accreditation_and_partners_work(): void
    {
        $this->get('/id/profil/struktur-pengelola')->assertOk()->assertSee('Ketua Program Studi');
        $this->get('/id/profil/tenaga-kependidikan')->assertOk()->assertSee('&quot;staff&quot;:[]', false);
        $this->get('/id/profil/profil-lulusan')->assertOk()->assertSee('Software Engineer');
        $this->get('/id/profil/akreditasi')->assertOk()->assertSee('&quot;current&quot;:null', false);
        $this->get('/id/profil/kerja-sama')->assertOk()->assertSee('Bank Indonesia');
    }

    public function test_legacy_lecturer_import_dry_run_is_safe_and_idempotent(): void
    {
        $before = DB::table('lecturers')->count();

        $dryRun = app(LegacyLecturerImporter::class)->import(dryRun: true, downloadPhotos: false);
        $this->assertSame($before, DB::table('lecturers')->count());

        $commit = app(LegacyLecturerImporter::class)->import(dryRun: false, downloadPhotos: false);
        $again = app(LegacyLecturerImporter::class)->import(dryRun: false, downloadPhotos: false);

        $this->assertSame(8, $dryRun['count']);
        $this->assertSame(0, $again['created']);
        $this->assertSame(8, $commit['updated']);
    }
}
