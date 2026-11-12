<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QualityAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class QualityController extends Controller
{
    public function index(QualityAuditService $audit): Response
    {
        return Inertia::render('Admin/QualityDashboardPage', [
            'title' => 'Quality Dashboard',
            'description' => 'Ringkasan audit non-destruktif untuk UAT dan release candidate.',
            'summary' => $audit->summary(),
            'items' => [],
            'mode' => 'summary',
        ]);
    }

    public function contentVerification(Request $request, QualityAuditService $audit): Response
    {
        $module = $request->string('module')->toString();
        $items = collect($audit->masterInventoryRows())
            ->when($module !== '', fn ($rows) => $rows->where('Modul', $module))
            ->filter(fn ($row) => $row['Status verifikasi'] !== 'verified')
            ->take(100)
            ->values();

        return Inertia::render('Admin/QualityDashboardPage', [
            'title' => 'Content Verification',
            'description' => 'Daftar konten yang masih perlu verifikasi admin.',
            'summary' => $audit->summary(),
            'items' => $items,
            'mode' => 'content',
            'filters' => ['module' => $module],
        ]);
    }

    public function rpsCoverage(QualityAuditService $audit): Response
    {
        $missing = [];
        if (Schema::hasTable('courses') && Schema::hasTable('rps_documents')) {
            $missing = DB::table('courses')
                ->leftJoin('rps_documents', 'courses.id', '=', 'rps_documents.course_id')
                ->whereNull('rps_documents.id')
                ->orderBy('courses.semester')
                ->orderBy('courses.code')
                ->limit(100)
                ->get(['courses.code', 'courses.name_id', 'courses.name_en', 'courses.semester', 'courses.credits'])
                ->map(fn ($row) => (array) $row)
                ->all();
        }

        return Inertia::render('Admin/QualityDashboardPage', [
            'title' => 'RPS Coverage',
            'description' => 'Coverage RPS per mata kuliah untuk persiapan UAT.',
            'summary' => $audit->summary(),
            'items' => $missing,
            'mode' => 'rps',
        ]);
    }
}
