<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CurriculumWorkbookImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AcademicController extends Controller
{
    public function dashboard(): Response
    {
        $curriculum = DB::table('curricula')->where('code', 'OBE-2026-S1-INF')->first();
        $courses = $curriculum ? DB::table('courses')->where('curriculum_id', $curriculum->id)->get() : collect();

        return Inertia::render('Admin/AcademicDashboard', [
            'curriculum' => $curriculum,
            'metrics' => [
                'courses' => $courses->count(),
                'credits' => $curriculum?->total_credits ?? 0,
                'unverified' => $courses->where('verification_status', '!=', 'verified')->count(),
                'withoutEnglishName' => $courses->whereNull('name_en')->count(),
                'withoutDescription' => $courses->whereNull('description_id')->count(),
                'withoutRps' => $courses->count() - DB::table('rps_documents')->where('is_current', true)->count(),
            ],
            'imports' => DB::table('curriculum_imports')->orderByDesc('id')->limit(8)->get(),
        ]);
    }

    public function imports(): Response
    {
        return Inertia::render('Admin/CurriculumImports', [
            'imports' => DB::table('curriculum_imports')->orderByDesc('id')->paginate(10),
            'defaultPath' => config('academic.curriculum_import_path'),
        ]);
    }

    public function inspectImport(Request $request, CurriculumWorkbookImporter $importer): RedirectResponse
    {
        $path = $request->string('path')->toString() ?: config('academic.curriculum_import_path');
        $result = $importer->import($path, dryRun: true, updateExisting: true);

        return back()->with('status', 'Inspect/dry-run selesai. Report #'.$result['import']->id);
    }

    public function commitImport(Request $request, CurriculumWorkbookImporter $importer): RedirectResponse
    {
        $path = $request->string('path')->toString() ?: config('academic.curriculum_import_path');
        $result = $importer->import($path, dryRun: false, updateExisting: true);

        return back()->with('status', 'Import selesai. Report #'.$result['import']->id);
    }

    public function rps(): Response
    {
        return Inertia::render('Admin/RpsIndex', [
            'courses' => DB::table('courses')->where('is_active', true)->orderBy('semester')->orderBy('code')->get(['id', 'code', 'name_id', 'semester']),
            'rpsDocuments' => DB::table('rps_documents')
                ->leftJoin('courses', 'rps_documents.course_id', '=', 'courses.id')
                ->select('rps_documents.*', 'courses.code', 'courses.name_id')
                ->orderByDesc('rps_documents.id')
                ->paginate(12),
        ]);
    }

    public function storeRps(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title_id' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'semester_type' => ['nullable', 'string', 'max:20'],
            'coordinator_name' => ['nullable', 'string', 'max:255'],
            'approved_date' => ['nullable', 'date'],
            'is_public' => ['nullable', 'boolean'],
            'is_current' => ['nullable', 'boolean'],
            'file' => ['required', 'file', 'mimetypes:application/pdf', 'max:20480'],
        ]);

        $course = DB::table('courses')->where('id', $validated['course_id'])->first();
        abort_unless($course, 404);

        $file = $request->file('file');
        $checksum = hash_file('sha256', $file->getRealPath());
        $path = $file->store('rps/'.now()->format('Y'), 'public');

        if ($request->boolean('is_current')) {
            DB::table('rps_documents')
                ->where('course_id', $course->id)
                ->where('curriculum_id', $course->curriculum_id)
                ->update(['is_current' => false]);
        }

        DB::table('rps_documents')->insert([
            'course_id' => $course->id,
            'curriculum_id' => $course->curriculum_id,
            'title_id' => $validated['title_id'],
            'title_en' => $validated['title_en'] ?? $validated['title_id'],
            'slug' => 'rps-'.$course->code.'-'.$validated['version'],
            'version' => $validated['version'],
            'academic_year' => $validated['academic_year'] ?? null,
            'semester_type' => $validated['semester_type'] ?? null,
            'coordinator_name' => $validated['coordinator_name'] ?? null,
            'approved_date' => $validated['approved_date'] ?? null,
            'file_id' => $path,
            'file_size' => $file->getSize(),
            'checksum' => $checksum,
            'download_count' => 0,
            'is_public' => $request->boolean('is_public'),
            'is_current' => $request->boolean('is_current'),
            'status' => $request->boolean('is_public') ? 'published' : 'draft',
            'verification_status' => 'verified',
            'published_at' => $request->boolean('is_public') ? now() : null,
            'metadata' => json_encode(['mime' => $file->getMimeType()], JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Storage::disk('public')->exists($path);

        return back()->with('status', 'RPS berhasil diunggah.');
    }
}
