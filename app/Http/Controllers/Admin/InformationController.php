<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LegacyContentImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InformationController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Admin/InformationDashboard', [
            'metrics' => [
                'news' => DB::table('news')->count(),
                'publishedNews' => DB::table('news')->where('status', 'published')->count(),
                'announcements' => DB::table('announcements')->count(),
                'activeAnnouncements' => DB::table('announcements')->where('status', 'published')->where('starts_at', '<=', now())->where(function ($query) {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })->count(),
                'events' => DB::table('events')->count(),
                'albums' => DB::table('albums')->count(),
                'documents' => DB::table('documents')->count(),
                'faqs' => DB::table('faqs')->count(),
                'pendingMigrations' => DB::table('content_migrations')->where('verification_status', 'pending')->count(),
            ],
            'news' => DB::table('news')->leftJoin('news_categories', 'news.category_id', '=', 'news_categories.id')->select('news.*', 'news_categories.name_id as category')->orderByDesc('news.updated_at')->limit(12)->get(),
            'announcements' => DB::table('announcements')->orderByDesc('updated_at')->limit(8)->get(),
            'events' => DB::table('events')->orderByDesc('start_at')->limit(8)->get(),
            'albums' => DB::table('albums')->orderByDesc('event_date')->limit(8)->get(),
            'documents' => DB::table('documents')->orderByDesc('updated_at')->limit(8)->get(),
            'faqs' => DB::table('faqs')->orderBy('sort_order')->limit(12)->get(),
            'migrations' => DB::table('content_migrations')->orderByDesc('id')->limit(12)->get(),
            'routes' => [
                'news' => '/admin/news',
                'announcements' => '/admin/announcements',
                'events' => '/admin/events',
                'gallery' => '/admin/gallery',
                'documents' => '/admin/documents',
                'faqs' => '/admin/faqs',
            ],
        ]);
    }

    public function inspectLegacy(LegacyContentImporter $importer): RedirectResponse
    {
        $report = $importer->inspect();

        foreach ($report['items'] as $item) {
            DB::table('content_migrations')->updateOrInsert(
                ['source_url' => $item['source_url']],
                [
                    'content_type' => $item['type'],
                    'target_path' => $item['target_url'],
                    'verification_status' => $item['verification_status'],
                    'translation_status' => $item['translation_status'],
                    'media_migration_status' => $item['media'] === 'none detected' ? 'not_required' : 'pending',
                    'revision_notes' => $item['notes'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }

        return back()->with('status', 'Inspect legacy selesai: '.count($report['items']).' item, '.count($report['rejected']).' ditolak.');
    }

    public function importLegacy(LegacyContentImporter $importer): RedirectResponse
    {
        $report = $importer->import(null, dryRun: false);

        return back()->with('status', 'Import legacy selesai: '.$report['created_or_updated'].' catatan, '.$report['redirects'].' redirect.');
    }
}
