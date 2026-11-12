<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function landing(string $locale): Response
    {
        return Inertia::render('Public/Profile/ProfileLandingPage', $this->base($locale) + [
            'summary' => [
                'about' => $this->pageRecord('profile.about'),
                'history' => $this->pageRecord('profile.history'),
                'vision' => $this->pageRecord('profile.vision'),
                'headMessage' => $this->pageRecord('profile.head-message'),
                'lecturersCount' => $this->lecturersQuery()->count(),
                'partnersCount' => $this->partnersQuery()->count(),
            ],
        ]);
    }

    public function page(string $page, string $locale): Response
    {
        $key = match ($page) {
            'tentang', 'about' => 'profile.about',
            'sejarah', 'history' => 'profile.history',
            'visi-misi-tujuan', 'vision-mission-objectives' => 'profile.vision',
            'sambutan', 'head-message' => 'profile.head-message',
            default => abort(404),
        };

        $component = match ($key) {
            'profile.history' => 'Public/Profile/HistoryPage',
            'profile.vision' => 'Public/Profile/VisionMissionPage',
            'profile.head-message' => 'Public/Profile/HeadMessagePage',
            default => 'Public/Profile/AboutPage',
        };

        return Inertia::render($component, $this->base($locale) + [
            'page' => $this->pageRecord($key),
            'management' => $this->managementList(),
        ]);
    }

    public function management(string $locale): Response
    {
        return Inertia::render('Public/Profile/ManagementPage', $this->base($locale) + [
            'positions' => $this->managementList(),
        ]);
    }

    public function lecturers(Request $request, string $locale): Response
    {
        $query = $this->lecturersQuery();
        if ($request->filled('q')) {
            $q = '%'.$request->string('q')->toString().'%';
            $query->where(fn ($builder) => $builder->where('full_name', 'like', $q)->orWhere('academic_title', 'like', $q)->orWhere('expertise_id', 'like', $q));
        }
        if ($request->filled('position')) {
            $query->where('academic_position_id', $request->query('position'));
        }

        return Inertia::render('Public/Profile/LecturersDirectory', $this->base($locale) + [
            'lecturers' => $query->orderBy('sort_order')->paginate(12)->withQueryString(),
            'filters' => $request->only(['q', 'position']),
            'expertiseAreas' => DB::table('expertise_areas')->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function lecturerDetail(string $slug, string $locale): Response
    {
        $lecturer = $this->lecturersQuery()->where('slug', $slug)->first();
        abort_unless($lecturer, 404);

        return Inertia::render('Public/Profile/LecturerDetail', $this->base($locale) + [
            'lecturer' => $lecturer,
            'educations' => DB::table('lecturer_educations')->where('lecturer_id', $lecturer->id)->orderBy('sort_order')->get(),
            'courses' => DB::table('courses')->where('verification_status', 'verified')->orderBy('semester')->limit(6)->get(['code', 'name_id', 'name_en', 'semester']),
        ]);
    }

    public function staff(string $locale): Response
    {
        return Inertia::render('Public/Profile/StaffPage', $this->base($locale) + [
            'staff' => DB::table('staff')->where('is_active', true)->where('verification_status', 'verified')->orderBy('sort_order')->get(),
        ]);
    }

    public function graduateProfiles(string $locale): Response
    {
        $curriculumId = DB::table('curricula')->where('code', 'OBE-2026-S1-INF')->value('id');

        return Inertia::render('Public/Profile/GraduateProfilesPage', $this->base($locale) + [
            'profiles' => DB::table('graduate_profiles')->where('curriculum_id', $curriculumId)->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function accreditation(string $locale): Response
    {
        return Inertia::render('Public/Profile/AccreditationPage', $this->base($locale) + [
            'current' => DB::table('accreditations')->where('is_current', true)->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->first(),
            'history' => DB::table('accreditations')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderByDesc('valid_until')->get(),
        ]);
    }

    public function partnerships(Request $request, string $locale): Response
    {
        $query = $this->partnersQuery();
        if ($request->filled('type')) {
            $query->where('partner_type', $request->query('type'));
        }

        return Inertia::render('Public/Profile/PartnershipsPage', $this->base($locale) + [
            'partners' => $query->orderBy('sort_order')->get(),
            'types' => DB::table('partners')->where('is_active', true)->where('verification_status', 'needs_review')->distinct()->pluck('partner_type')->filter()->values(),
            'filters' => $request->only(['type']),
        ]);
    }

    private function pageRecord(string $key): ?object
    {
        return DB::table('pages')->where('key', $key)->where('status', 'published')->where('is_active', true)->first();
    }

    private function managementList(): array
    {
        return DB::table('management_positions')
            ->leftJoin('lecturers', 'management_positions.lecturer_id', '=', 'lecturers.id')
            ->where('management_positions.is_active', true)
            ->whereIn('management_positions.verification_status', $this->publicVerificationStatuses())
            ->select('management_positions.*', 'lecturers.full_name', 'lecturers.academic_title', 'lecturers.slug as lecturer_slug', 'lecturers.photo_id')
            ->orderBy('management_positions.sort_order')
            ->get()
            ->toArray();
    }

    private function lecturersQuery(): Builder
    {
        return DB::table('lecturers')
            ->where('is_active', true)
            ->where('status', 'published')
            ->whereIn('verification_status', $this->publicVerificationStatuses())
            ->whereNull('deleted_at');
    }

    private function partnersQuery(): Builder
    {
        return DB::table('partners')
            ->where('is_active', true)
            ->where('status', 'published')
            ->whereIn('verification_status', ['verified', 'needs_review']);
    }

    /**
     * @return array<int, string>
     */
    private function publicVerificationStatuses(): array
    {
        return app()->environment('production') ? ['verified'] : ['verified', 'needs_review'];
    }

    /**
     * @return array<string, mixed>
     */
    private function base(string $locale): array
    {
        return [
            'locale' => $locale,
            'seo' => [
                'title' => $locale === 'en' ? 'Informatics Study Program Profile' : 'Profil Program Studi Informatika',
                'description' => $locale === 'en' ? 'Profile of the Informatics Study Program.' : 'Profil Program Studi Informatika.',
            ],
        ];
    }
}
