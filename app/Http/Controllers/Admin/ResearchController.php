<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LegacyResearchImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ResearchController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Admin/ResearchDashboard', [
            'metrics' => [
                'activeResearch' => DB::table('research_projects')->where('status', 'ongoing')->count(),
                'completedResearch' => DB::table('research_projects')->where('status', 'completed')->count(),
                'communityServices' => DB::table('community_services')->count(),
                'publications' => DB::table('publications')->count(),
                'products' => DB::table('digital_products')->count(),
                'intellectualProperties' => DB::table('intellectual_properties')->count(),
                'expertiseGroups' => DB::table('expertise_groups')->count(),
                'journals' => DB::table('journals')->count(),
                'pendingVerification' => $this->pendingVerification(),
                'withoutTranslation' => DB::table('research_projects')->where('translation_status', '!=', 'complete')->count(),
            ],
            'research' => DB::table('research_projects')->orderByDesc('updated_at')->limit(10)->get(),
            'services' => DB::table('community_services')->orderByDesc('updated_at')->limit(10)->get(),
            'publications' => DB::table('publications')->orderByDesc('year')->limit(10)->get(),
            'products' => DB::table('digital_products')->orderByDesc('updated_at')->limit(10)->get(),
            'journals' => DB::table('journals')->orderBy('sort_order')->get(),
            'migrations' => DB::table('content_migrations')->where('content_type', 'like', 'research:%')->orderByDesc('id')->limit(10)->get(),
        ]);
    }

    public function inspectLegacy(LegacyResearchImporter $importer): RedirectResponse
    {
        $report = $importer->import(null, dryRun: false);

        return back()->with('status', 'Inspect riset legacy selesai: '.$report['created_or_updated'].' catatan migrasi.');
    }

    public function importLegacy(LegacyResearchImporter $importer): RedirectResponse
    {
        $report = $importer->import(null, dryRun: false);

        return back()->with('status', 'Import riset legacy selesai: '.$report['created_or_updated'].' catatan pending.');
    }

    private function pendingVerification(): int
    {
        return collect(['research_projects', 'community_services', 'publications', 'digital_products', 'intellectual_properties', 'expertise_groups', 'journals'])
            ->sum(fn (string $table): int => DB::table($table)->where('verification_status', '!=', 'verified')->count());
    }
}
