<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(HealthCheckService $health): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'publishedNews' => DB::table('news')->where('status', 'published')->where('is_public', true)->count(),
                'draftNews' => DB::table('news')->where('status', 'draft')->count(),
                'activeAnnouncements' => DB::table('announcements')->where('status', 'published')->count(),
                'upcomingEvents' => DB::table('events')->where('start_at', '>=', now())->count(),
                'activeLecturers' => DB::table('lecturers')->where('is_active', true)->count(),
                'activeCourses' => DB::table('courses')->where('is_active', true)->count(),
                'publishedRps' => DB::table('rps_documents')->where('is_public', true)->count(),
                'missingRps' => max(DB::table('courses')->where('is_active', true)->count() - DB::table('rps_documents')->where('is_public', true)->count(), 0),
                'research' => DB::table('research_projects')->count(),
                'achievements' => DB::table('achievements')->count(),
                'newMessages' => DB::table('contact_messages')->where('status', 'new')->count(),
                'mediaStorageMb' => round(((int) DB::table('media')->whereNull('deleted_at')->sum(DB::raw('coalesce(file_size, size, 0)'))) / 1024 / 1024, 2),
                'incompleteTranslations' => $this->translationCount(),
                'pendingVerification' => $this->pendingVerification(),
                'redirectWarnings' => DB::table('legacy_redirects')->whereNull('deleted_at')->whereColumn('old_path', 'new_path')->count(),
                'lastBackup' => DB::table('backup_records')->where('status', 'completed')->max('finished_at'),
            ],
            'activity' => DB::table('admin_activity_logs')->orderByDesc('created_at')->limit(8)->get(),
            'latestContent' => DB::table('news')->orderByDesc('updated_at')->limit(5)->get(['title_id', 'status', 'updated_at']),
            'upcomingEvents' => DB::table('events')->where('start_at', '>=', now())->orderBy('start_at')->limit(5)->get(['title_id', 'start_at', 'status']),
            'newMessages' => DB::table('contact_messages')->where('status', 'new')->orderByDesc('created_at')->limit(5)->get(['name', 'subject', 'created_at']),
            'needsVerification' => $this->verificationRows(),
            'security' => [
                'twoFactorUsers' => DB::table('users')->whereNotNull('two_factor_confirmed_at')->count(),
                'adminNoindex' => true,
                'csp' => true,
                'storageWritable' => File::isWritable(storage_path()),
                'health' => $health->check()['status'],
            ],
        ]);
    }

    private function translationCount(): int
    {
        return collect(['news', 'announcements', 'events', 'documents', 'research_projects', 'achievements'])
            ->sum(fn (string $table): int => DB::table($table)->where('translation_status', '!=', 'complete')->count());
    }

    private function pendingVerification(): int
    {
        return collect(['news', 'announcements', 'events', 'documents', 'lecturers', 'research_projects', 'achievements', 'media'])
            ->sum(fn (string $table): int => DB::table($table)->where('verification_status', '!=', 'verified')->count());
    }

    private function verificationRows(): array
    {
        return collect(['news', 'lecturers', 'research_projects', 'achievements', 'media'])
            ->flatMap(fn (string $table) => DB::table($table)->where('verification_status', '!=', 'verified')->limit(3)->get(['id', 'title_id', 'verification_status'])->map(fn ($row) => [
                'module' => $table,
                'id' => $row->id,
                'title' => $row->title_id,
                'status' => $row->verification_status,
            ]))
            ->values()
            ->all();
    }
}
