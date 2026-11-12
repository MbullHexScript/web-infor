<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LegacyStudentLifeImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentLifeController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Admin/StudentLifeDashboard', [
            'metrics' => [
                'achievements' => DB::table('achievements')->count(),
                'pendingAchievements' => DB::table('achievements')->where('verification_status', '!=', 'verified')->count(),
                'activeScholarships' => DB::table('scholarships')->where('status', 'open')->count(),
                'activeCompetitions' => DB::table('competitions')->whereIn('status', ['upcoming', 'registration_open'])->count(),
                'featuredAlumni' => DB::table('alumni_stories')->where('is_featured', true)->count(),
                'withoutTranslation' => $this->withoutTranslation(),
                'withoutPhoto' => DB::table('achievements')->whereNull('photo_id')->count() + DB::table('alumni_stories')->whereNull('photo_id')->count(),
                'needsVerification' => $this->needsVerification(),
            ],
            'achievements' => DB::table('achievements')->orderByDesc('updated_at')->limit(8)->get(),
            'scholarships' => DB::table('scholarships')->orderByDesc('updated_at')->limit(8)->get(),
            'competitions' => DB::table('competitions')->orderByDesc('updated_at')->limit(8)->get(),
            'alumni' => DB::table('alumni_stories')->orderByDesc('updated_at')->limit(8)->get(),
            'migrations' => DB::table('content_migrations')->where('content_type', 'like', 'student-life:%')->orderByDesc('id')->limit(10)->get(),
        ]);
    }

    public function facilities(): Response
    {
        return Inertia::render('Admin/StudentLifeDashboard', [
            'mode' => 'facilities',
            'metrics' => [
                'facilities' => DB::table('facilities')->count(),
                'publicFacilities' => DB::table('facilities')->where('is_public', true)->count(),
                'pendingFacilities' => DB::table('facilities')->where('verification_status', '!=', 'verified')->count(),
            ],
            'facilities' => DB::table('facilities')->orderBy('sort_order')->get(),
        ]);
    }

    public function admissions(): Response
    {
        return Inertia::render('Admin/StudentLifeDashboard', [
            'mode' => 'admissions',
            'metrics' => [
                'paths' => DB::table('admission_paths')->count(),
                'publicPaths' => DB::table('admission_paths')->where('is_public', true)->count(),
                'faq' => DB::table('faqs')->where('category', 'admissions')->count(),
            ],
            'paths' => DB::table('admission_paths')->orderBy('sort_order')->get(),
            'faqs' => DB::table('faqs')->where('category', 'admissions')->orderBy('sort_order')->get(),
        ]);
    }

    public function contact(Request $request): Response
    {
        $query = DB::table('contact_messages')->whereNull('deleted_at')->orderByDesc('created_at');
        if ($request->filled('q')) {
            $term = trim(strip_tags((string) $request->input('q')));
            $query->where(fn ($builder) => $builder->where('name', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')->orWhere('subject', 'like', '%'.$term.'%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return Inertia::render('Admin/ContactInbox', [
            'metrics' => [
                'new' => DB::table('contact_messages')->where('status', 'new')->count(),
                'read' => DB::table('contact_messages')->where('status', 'read')->count(),
                'replied' => DB::table('contact_messages')->where('status', 'replied')->count(),
                'archived' => DB::table('contact_messages')->where('status', 'archived')->count(),
                'spam' => DB::table('contact_messages')->where('status', 'spam')->count(),
            ],
            'filters' => $request->only(['q', 'status']),
            'items' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function updateMessageStatus(int $message, string $status): RedirectResponse
    {
        abort_unless(in_array($status, ['read', 'replied', 'archived', 'spam'], true), 404);

        $timestamps = [
            'status' => $status,
            'updated_at' => now(),
        ];
        if ($status === 'read') {
            $timestamps['read_at'] = now();
        }
        if ($status === 'replied') {
            $timestamps['replied_at'] = now();
        }
        if ($status === 'archived') {
            $timestamps['archived_at'] = now();
        }
        if ($status === 'spam') {
            $timestamps['spam_score'] = 100;
        }

        DB::table('contact_messages')->where('id', $message)->update($timestamps);

        return back()->with('status', 'Status pesan diperbarui.');
    }

    public function deleteMessage(int $message): RedirectResponse
    {
        DB::table('contact_messages')->where('id', $message)->update(['deleted_at' => now(), 'updated_at' => now()]);

        return back()->with('status', 'Pesan dihapus secara soft delete.');
    }

    public function exportMessages(): StreamedResponse
    {
        $rows = DB::table('contact_messages')->whereNull('deleted_at')->orderByDesc('created_at')->get(['created_at', 'name', 'email', 'category', 'subject', 'status']);

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['created_at', 'name', 'email', 'category', 'subject', 'status']);
            foreach ($rows as $row) {
                fputcsv($handle, (array) $row);
            }
            fclose($handle);
        }, 'contact-messages.csv', ['Content-Type' => 'text/csv']);
    }

    public function inspectLegacy(LegacyStudentLifeImporter $importer): RedirectResponse
    {
        $report = $importer->inspect();

        return back()->with('status', 'Inspect kemahasiswaan legacy selesai: '.count($report['items']).' item.');
    }

    public function importLegacy(Request $request, LegacyStudentLifeImporter $importer): RedirectResponse
    {
        $report = $importer->import($request->string('type', 'all'), (bool) $request->boolean('dry_run'));

        return back()->with('status', 'Import kemahasiswaan selesai: '.$report['created_or_updated'].' item, duplicate '.$report['duplicates'].'.');
    }

    private function needsVerification(): int
    {
        return collect(['achievements', 'scholarships', 'competitions', 'alumni_stories', 'facilities', 'admission_paths'])
            ->sum(fn (string $table): int => DB::table($table)->where('verification_status', '!=', 'verified')->count());
    }

    private function withoutTranslation(): int
    {
        return collect(['achievements', 'scholarships', 'competitions', 'alumni_stories', 'facilities'])
            ->sum(fn (string $table): int => DB::table($table)->where('translation_status', '!=', 'complete')->count());
    }
}
