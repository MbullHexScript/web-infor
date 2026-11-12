<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LegacyLecturerImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Admin/ProfileDashboard', [
            'metrics' => [
                'lecturers' => DB::table('lecturers')->where('is_active', true)->count(),
                'pendingLecturers' => DB::table('lecturers')->where('verification_status', '!=', 'verified')->count(),
                'withoutPhoto' => DB::table('lecturers')->whereNull('photo_id')->count(),
                'withoutEnglishBio' => DB::table('lecturers')->whereNull('biography_en')->count(),
                'managementPositions' => DB::table('management_positions')->where('is_active', true)->count(),
                'partners' => DB::table('partners')->where('is_active', true)->count(),
            ],
            'lecturers' => DB::table('lecturers')->orderBy('sort_order')->get(),
            'pages' => DB::table('pages')->where('key', 'like', 'profile.%')->orderBy('key')->get(),
            'accreditation' => DB::table('accreditations')->orderByDesc('id')->first(),
        ]);
    }

    public function importLecturers(LegacyLecturerImporter $importer): RedirectResponse
    {
        $report = $importer->import(dryRun: false, downloadPhotos: true);

        return back()->with('status', 'Import dosen legacy selesai: '.$report['created'].' baru, '.$report['updated'].' diperbarui.');
    }
}
