<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AcademicController extends Controller
{
    public function curriculum(string $locale): Response
    {
        $curriculum = $this->currentCurriculum();

        return Inertia::render('Public/Academics/Curriculum', [
            'locale' => $locale,
            'curriculum' => $curriculum,
            'semesterTotals' => $this->semesterTotals($curriculum?->id),
            'specializations' => $this->specializationList(),
            'profiles' => $this->profiles($curriculum?->id),
            'seo' => $this->seo($locale, 'curriculum'),
        ]);
    }

    public function courses(Request $request, string $locale): Response
    {
        $curriculum = $this->currentCurriculum();
        $semester = max(1, min(8, (int) $request->query('semester', 1)));

        return Inertia::render('Public/Academics/Courses', [
            'locale' => $locale,
            'curriculum' => $curriculum,
            'activeSemester' => $semester,
            'semesterTotals' => $this->semesterTotals($curriculum?->id),
            'coursesBySemester' => $this->coursesBySemester($curriculum?->id),
            'specializations' => $this->specializationList(),
            'seo' => $this->seo($locale, 'courses'),
        ]);
    }

    public function courseDetail(string $slug, string $locale): Response
    {
        $course = DB::table('courses')
            ->leftJoin('specializations', 'courses.specialization_id', '=', 'specializations.id')
            ->where('courses.slug_id', $slug)
            ->orWhere('courses.slug_en', $slug)
            ->select('courses.*', 'specializations.name_id as specialization_name_id', 'specializations.name_en as specialization_name_en')
            ->first();

        abort_unless($course && $this->isPublicCourse($course), 404);

        $metadata = $this->metadata($course);
        $prerequisites = DB::table('course_prerequisites')
            ->join('courses', 'course_prerequisites.prerequisite_course_id', '=', 'courses.id')
            ->where('course_prerequisites.course_id', $course->id)
            ->select('courses.code', 'courses.name_id', 'courses.name_en', 'courses.slug_id', 'courses.slug_en')
            ->get();

        $dependents = DB::table('course_prerequisites')
            ->join('courses', 'course_prerequisites.course_id', '=', 'courses.id')
            ->where('course_prerequisites.prerequisite_course_id', $course->id)
            ->select('courses.code', 'courses.name_id', 'courses.name_en', 'courses.slug_id', 'courses.slug_en', 'courses.semester')
            ->limit(8)
            ->get();

        return Inertia::render('Public/Academics/CourseDetail', [
            'locale' => $locale,
            'course' => $this->coursePayload($course) + [
                'metadata' => $metadata,
                'prerequisites' => $prerequisites,
                'dependents' => $dependents,
                'rps' => $this->currentRps($course->id),
            ],
            'seo' => $this->seo($locale, 'course', $course->name_id),
        ]);
    }

    public function specializations(string $locale): Response
    {
        $curriculum = $this->currentCurriculum();

        return Inertia::render('Public/Academics/Specializations', [
            'locale' => $locale,
            'curriculum' => $curriculum,
            'specializations' => collect($this->specializationList())->map(fn (object $specialization): array => $this->specializationPayload($specialization))->values(),
            'seo' => $this->seo($locale, 'specializations'),
        ]);
    }

    public function specializationDetail(string $slug, string $locale): Response
    {
        $specialization = DB::table('specializations')
            ->where(fn ($query) => $query->where('slug_id', $slug)->orWhere('slug_en', $slug))
            ->where('is_active', true)
            ->first();

        abort_unless($specialization, 404);

        $courses = DB::table('courses')
            ->where('specialization_id', $specialization->id)
            ->where('is_active', true)
            ->where('verification_status', 'verified')
            ->orderBy('semester')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (object $course): array => $this->coursePayload($course));

        return Inertia::render('Public/Academics/SpecializationDetail', [
            'locale' => $locale,
            'specialization' => $this->specializationPayload($specialization),
            'courses' => $courses,
            'seo' => $this->seo($locale, 'specializations', $specialization->name_id),
        ]);
    }

    public function rps(Request $request, string $locale): Response
    {
        $curriculum = $this->currentCurriculum();
        $query = DB::table('courses')
            ->leftJoin('specializations', 'courses.specialization_id', '=', 'specializations.id')
            ->leftJoin('rps_documents', function ($join): void {
                $join->on('courses.id', '=', 'rps_documents.course_id')
                    ->where('rps_documents.is_public', true)
                    ->where('rps_documents.is_current', true)
                    ->where('rps_documents.status', 'published')
                    ->whereNull('rps_documents.deleted_at');
            })
            ->where('courses.curriculum_id', $curriculum?->id)
            ->where('courses.is_active', true)
            ->where('courses.verification_status', 'verified');

        if ($request->filled('q')) {
            $q = '%'.$request->string('q')->toString().'%';
            $query->where(fn ($builder) => $builder->where('courses.code', 'like', $q)->orWhere('courses.name_id', 'like', $q)->orWhere('courses.name_en', 'like', $q));
        }
        if ($request->filled('semester')) {
            $query->where('courses.semester', (int) $request->query('semester'));
        }
        if ($request->filled('type')) {
            $query->where('courses.course_type', $request->query('type'));
        }
        if ($request->filled('specialization')) {
            $query->where('specializations.code', $request->query('specialization'));
        }

        $items = $query
            ->select('courses.*', 'specializations.name_id as specialization_name_id', 'specializations.name_en as specialization_name_en', 'rps_documents.id as rps_id', 'rps_documents.version', 'rps_documents.academic_year', 'rps_documents.file_size', 'rps_documents.updated_at as rps_updated_at')
            ->orderBy('courses.semester')
            ->orderBy('courses.sort_order')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Public/Academics/RpsRepository', [
            'locale' => $locale,
            'curriculum' => $curriculum,
            'filters' => $request->only(['q', 'semester', 'type', 'specialization']),
            'specializations' => $this->specializationList(),
            'items' => $items,
            'seo' => $this->seo($locale, 'rps'),
        ]);
    }

    public function rpsPreview(int $id, string $locale): Response
    {
        $rps = $this->publicRps($id);

        return Inertia::render('Public/Academics/RpsPreview', [
            'locale' => $locale,
            'rps' => $rps,
            'seo' => $this->seo($locale, 'rps', $rps->title_id),
        ]);
    }

    public function rpsDownload(int $id): StreamedResponse
    {
        $rps = $this->publicRps($id);
        DB::table('rps_documents')->where('id', $id)->increment('download_count');

        return Storage::disk('public')->download($rps->file_id, basename($rps->file_id));
    }

    public function academicCalendar(string $locale): Response
    {
        return Inertia::render('Public/Academics/AcademicCalendar', [
            'locale' => $locale,
            'items' => $this->documentLike('academic_calendars'),
            'seo' => $this->seo($locale, 'calendar'),
        ]);
    }

    public function classSchedule(string $locale): Response
    {
        return Inertia::render('Public/Academics/ClassSchedule', [
            'locale' => $locale,
            'items' => $this->documentLike('class_schedules'),
            'seo' => $this->seo($locale, 'schedule'),
        ]);
    }

    public function documents(string $locale): Response
    {
        return Inertia::render('Public/Academics/AcademicDocuments', [
            'locale' => $locale,
            'categories' => DB::table('document_categories')->where('status', 'published')->orderBy('title_id')->get(),
            'documents' => $this->documentLike('documents'),
            'seo' => $this->seo($locale, 'documents'),
        ]);
    }

    private function currentCurriculum(): ?object
    {
        return DB::table('curricula')
            ->where('is_active', true)
            ->where('status', 'published')
            ->where('verification_status', 'verified')
            ->whereNotNull('published_at')
            ->orderByDesc('year')
            ->first();
    }

    private function isPublicCourse(object $course): bool
    {
        return (bool) $course->is_active && $course->status === 'published' && $course->verification_status === 'verified';
    }

    private function coursesBySemester(?int $curriculumId): array
    {
        if (! $curriculumId) {
            return [];
        }

        return DB::table('courses')
            ->leftJoin('specializations', 'courses.specialization_id', '=', 'specializations.id')
            ->where('courses.curriculum_id', $curriculumId)
            ->where('courses.is_active', true)
            ->where('courses.status', 'published')
            ->where('courses.verification_status', 'verified')
            ->select('courses.*', 'specializations.name_id as specialization_name_id', 'specializations.name_en as specialization_name_en')
            ->orderBy('courses.semester')
            ->orderBy('courses.sort_order')
            ->get()
            ->map(fn (object $course): array => $this->coursePayload($course))
            ->groupBy('semester')
            ->toArray();
    }

    private function semesterTotals(?int $curriculumId): array
    {
        if (! $curriculumId) {
            return [];
        }

        $courses = DB::table('courses')->where('curriculum_id', $curriculumId)->where('is_active', true)->where('verification_status', 'verified')->get();

        return collect(range(1, 8))->mapWithKeys(function (int $semester) use ($courses): array {
            $semesterCourses = $courses->where('semester', $semester);
            $mandatory = $semesterCourses->where('course_type', 'mandatory')->sum('credits');
            $elective = $semesterCourses->where('course_type', 'elective')->sum('credits');
            $package = $mandatory + ($elective > 0 ? 3 : 0);

            return [$semester => [
                'course_count' => $semesterCourses->count(),
                'mandatory_credits' => $mandatory,
                'elective_credits' => $elective,
                'student_package_credits' => $package,
            ]];
        })->all();
    }

    private function profiles(?int $curriculumId): array
    {
        if (! $curriculumId) {
            return [];
        }

        return DB::table('graduate_profiles')
            ->where('curriculum_id', $curriculumId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    private function specializationList(): array
    {
        return DB::table('specializations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    private function coursePayload(object $course): array
    {
        return [
            'id' => $course->id,
            'code' => $course->code,
            'name_id' => $course->name_id,
            'name_en' => $course->name_en,
            'slug_id' => $course->slug_id,
            'slug_en' => $course->slug_en,
            'semester' => $course->semester,
            'credits' => $course->credits,
            'course_type' => $course->course_type,
            'category' => $course->category,
            'description_id' => $course->description_id,
            'description_en' => $course->description_en,
            'learning_materials_id' => $course->learning_materials_id,
            'software_tools_id' => $course->software_tools_id,
            'specialization_name_id' => $course->specialization_name_id ?? null,
            'specialization_name_en' => $course->specialization_name_en ?? null,
            'metadata' => $this->metadata($course),
        ];
    }

    private function specializationPayload(object $specialization): array
    {
        return (array) $specialization + [
            'course_count' => DB::table('courses')->where('specialization_id', $specialization->id)->where('is_active', true)->count(),
        ];
    }

    private function currentRps(int $courseId): ?object
    {
        return DB::table('rps_documents')
            ->where('course_id', $courseId)
            ->where('is_public', true)
            ->where('is_current', true)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->first();
    }

    private function publicRps(int $id): object
    {
        $rps = DB::table('rps_documents')
            ->join('courses', 'rps_documents.course_id', '=', 'courses.id')
            ->where('rps_documents.id', $id)
            ->where('rps_documents.is_public', true)
            ->where('rps_documents.status', 'published')
            ->whereNull('rps_documents.deleted_at')
            ->select('rps_documents.*', 'courses.code', 'courses.name_id as course_name_id', 'courses.name_en as course_name_en', 'courses.semester', 'courses.credits')
            ->first();

        abort_unless($rps && $rps->file_id && Storage::disk('public')->exists($rps->file_id), 404);

        return $rps;
    }

    private function documentLike(string $table): array
    {
        return DB::table($table)
            ->where('status', 'published')
            ->where('verification_status', 'verified')
            ->orderByDesc('updated_at')
            ->get()
            ->toArray();
    }

    private function metadata(object $record): array
    {
        return $record->metadata ? json_decode((string) $record->metadata, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    private function seo(string $locale, string $page, ?string $suffix = null): array
    {
        $titles = [
            'curriculum' => $locale === 'en' ? 'OBE Curriculum 2026 | Informatics UIN Sultan Maulana Hasanuddin Banten' : 'Kurikulum OBE 2026 | Informatika UIN Sultan Maulana Hasanuddin Banten',
            'courses' => $locale === 'en' ? 'Course Structure | OBE Curriculum 2026' : 'Struktur Mata Kuliah Informatika | Kurikulum OBE 2026',
            'rps' => $locale === 'en' ? 'Course Plan Repository | Informatics UIN SMH Banten' : 'Repositori RPS Informatika | UIN Sultan Maulana Hasanuddin Banten',
        ];

        return [
            'title' => ($suffix ? $suffix.' | ' : '').($titles[$page] ?? 'Akademik Informatika'),
            'description' => $locale === 'en' ? 'Academic information for the Informatics Study Program.' : 'Informasi akademik Program Studi Informatika.',
        ];
    }
}
