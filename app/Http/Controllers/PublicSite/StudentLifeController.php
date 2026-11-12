<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class StudentLifeController extends Controller
{
    public function landing(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/StudentLifeLandingPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Kemahasiswaan', 'Student Life'),
            'achievements' => $this->achievementsQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->achievementResource($row, $locale))->all(),
            'scholarships' => $this->scholarshipsQuery()->whereIn('status', ['open', 'upcoming'])->limit(3)->get()->map(fn ($row) => $this->scholarshipResource($row, $locale))->all(),
            'competitions' => $this->competitionsQuery()->whereIn('status', ['upcoming', 'registration_open'])->limit(3)->get()->map(fn ($row) => $this->competitionResource($row, $locale))->all(),
            'alumni' => $this->alumniQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->alumniResource($row, $locale))->all(),
            'facilities' => $this->facilitiesQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->facilityResource($row, $locale))->all(),
            'hmps' => $this->hmpsData($locale),
        ]);
    }

    public function campusLife(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/CampusLifePage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Kehidupan Mahasiswa', 'Campus Life'),
            'page' => $this->pageData('student_life', $locale),
            'news' => $this->publishedNewsQuery()->where('is_featured', true)->limit(4)->get(),
            'events' => DB::table('events')->where('is_public', true)->where('verification_status', 'verified')->limit(4)->get(),
            'achievements' => $this->achievementsQuery()->limit(3)->get()->map(fn ($row) => $this->achievementResource($row, $locale))->all(),
        ]);
    }

    public function achievements(Request $request, string $locale): Response
    {
        $query = $this->achievementsQuery();
        $this->applySearch($query, $request, ['title_id', 'title_en', 'recipient_name', 'organizer', 'rank_or_award_id', 'rank_or_award_en']);
        if ($request->filled('year')) {
            $query->where('year', (int) $request->input('year'));
        }
        if ($request->filled('level')) {
            $query->where('level', $request->string('level'));
        }
        if ($request->filled('type')) {
            $query->where('achievement_type', $request->string('type'));
        }

        return Inertia::render('Public/StudentLife/AchievementsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Prestasi', 'Achievements'),
            'filters' => $request->only(['q', 'year', 'level', 'type']),
            'featured' => ($featured = $this->achievementsQuery()->where('is_featured', true)->first()) ? $this->achievementResource($featured, $locale) : null,
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->achievementResource($row, $locale)),
            'stats' => [
                'verified' => $this->achievementsQuery()->count(),
                'national' => $this->achievementsQuery()->where('level', 'national')->count(),
                'international' => $this->achievementsQuery()->where('level', 'international')->count(),
            ],
        ]);
    }

    public function achievementDetail(string $slug, string $locale): Response
    {
        $row = $this->achievementsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/StudentLife/AchievementDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'item' => $this->achievementResource($row, $locale, true),
            'related' => $this->achievementsQuery()->where('id', '!=', $row->id)->where('level', $row->level)->limit(3)->get()->map(fn ($item) => $this->achievementResource($item, $locale))->all(),
        ]);
    }

    public function scholarships(Request $request, string $locale): Response
    {
        $query = $this->scholarshipsQuery();
        $this->applySearch($query, $request, ['name_id', 'name_en', 'provider_id', 'provider_en', 'description_id', 'description_en']);
        if ($request->filled('provider')) {
            $query->where($locale === 'en' ? 'provider_en' : 'provider_id', 'like', '%'.$this->clean($request->string('provider')).'%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return Inertia::render('Public/StudentLife/ScholarshipsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Beasiswa', 'Scholarships'),
            'filters' => $request->only(['q', 'provider', 'status']),
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->scholarshipResource($row, $locale)),
        ]);
    }

    public function scholarshipDetail(string $slug, string $locale): Response
    {
        $row = $this->scholarshipsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/StudentLife/ScholarshipDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->name_id, $row->name_en ?: $row->name_id),
            'item' => $this->scholarshipResource($row, $locale, true),
        ]);
    }

    public function competitions(Request $request, string $locale): Response
    {
        $query = $this->competitionsQuery();
        $this->applySearch($query, $request, ['title_id', 'title_en', 'organizer', 'description_id', 'description_en']);
        if ($request->filled('year')) {
            $query->whereYear('event_date', (int) $request->input('year'));
        }
        if ($request->filled('level')) {
            $query->where('level', $request->string('level'));
        }
        if ($request->filled('type')) {
            $query->where('competition_type', $request->string('type'));
        }

        return Inertia::render('Public/StudentLife/CompetitionsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Kompetisi', 'Competitions'),
            'filters' => $request->only(['q', 'year', 'level', 'type']),
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->competitionResource($row, $locale)),
        ]);
    }

    public function competitionDetail(string $slug, string $locale): Response
    {
        $row = $this->competitionsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/StudentLife/CompetitionDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'item' => $this->competitionResource($row, $locale, true),
        ]);
    }

    public function mbkm(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/MbkmPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'MBKM dan Magang', 'MBKM and Internships'),
            'page' => $this->pageData('mbkm', $locale),
            'partners' => DB::table('partners')->where('verification_status', 'verified')->limit(8)->get(),
            'documents' => DB::table('documents')->where('is_public', true)->where('verification_status', 'verified')->where('file_type', 'mbkm')->limit(8)->get(),
            'faqs' => $this->faqData('mbkm', $locale),
        ]);
    }

    public function alumni(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/AlumniPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Alumni', 'Alumni'),
            'items' => $this->alumniQuery()->paginate(9)->through(fn ($row) => $this->alumniResource($row, $locale)),
        ]);
    }

    public function alumniDetail(string $slug, string $locale): Response
    {
        $row = $this->alumniQuery()->where('slug', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/StudentLife/AlumniStoryPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->alumni_name, $row->alumni_name),
            'item' => $this->alumniResource($row, $locale, true),
        ]);
    }

    public function hmps(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/HmpsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'HMPS Informatika', 'Informatics Student Association'),
            'hmps' => $this->hmpsData($locale),
            'news' => $this->publishedNewsQuery()->limit(4)->get(),
        ]);
    }

    public function facilities(Request $request, string $locale): Response
    {
        $query = $this->facilitiesQuery();
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        return Inertia::render('Public/StudentLife/FacilitiesPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Fasilitas', 'Facilities'),
            'filters' => $request->only(['category']),
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->facilityResource($row, $locale)),
        ]);
    }

    public function facilityDetail(string $slug, string $locale): Response
    {
        $row = $this->facilitiesQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/StudentLife/FacilityDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->name_id, $row->name_en ?: $row->name_id),
            'item' => $this->facilityResource($row, $locale, true),
        ]);
    }

    public function admissions(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/AdmissionLandingPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Penerimaan Mahasiswa Baru', 'Admissions'),
            'page' => $this->pageData('admissions', $locale),
            'paths' => DB::table('admission_paths')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderBy('sort_order')->get()->map(fn ($row) => $this->admissionPathResource($row, $locale))->all(),
            'faqs' => $this->faqData('admissions', $locale),
            'facilities' => $this->facilitiesQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->facilityResource($row, $locale))->all(),
            'achievements' => $this->achievementsQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->achievementResource($row, $locale))->all(),
            'portal' => SiteValue::get('admissions.portal'),
        ]);
    }

    public function contact(string $locale): Response
    {
        return Inertia::render('Public/StudentLife/ContactPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Kontak', 'Contact'),
            'contact' => SiteValue::get('site.contact.public'),
            'faqs' => $this->faqData('contact', $locale),
        ]);
    }

    public function storeContact(Request $request, string $locale): RedirectResponse
    {
        $key = 'contact-form:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['email' => __('Too many attempts. Please try again later.')]);
        }
        RateLimiter::hit($key, 300);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'category' => ['required', 'in:academic,admissions,rps,partnership,research,student_life,general'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'size:0'],
        ]);

        $messageId = DB::table('contact_messages')->insertGetId([
            'name' => strip_tags($validated['name']),
            'email' => mb_strtolower($validated['email']),
            'phone' => isset($validated['phone']) ? strip_tags($validated['phone']) : null,
            'category' => $validated['category'],
            'subject' => strip_tags($validated['subject']),
            'message' => strip_tags($validated['message']),
            'locale' => $locale,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'status' => 'new',
            'verification_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::raw("Pesan kontak baru #{$messageId}: ".$validated['subject'], function ($mail) {
                $mail->to('informatika@uinbanten.ac.id')->subject('Pesan kontak website Informatika');
            });
        } catch (\Throwable) {
            report('Contact message stored; mail delivery unavailable.');
        }

        return back()->with('status', $locale === 'en' ? 'Message received.' : 'Pesan berhasil diterima.');
    }

    private function publishedNewsQuery()
    {
        return DB::table('news')
            ->where('status', 'published')
            ->where('verification_status', 'verified')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereNull('deleted_at')
            ->orderByDesc('published_at');
    }

    private function achievementsQuery()
    {
        return DB::table('achievements')->where('is_public', true)->where('verification_status', 'verified')->whereNotNull('published_at')->whereNull('deleted_at')->orderByDesc('year');
    }

    private function scholarshipsQuery()
    {
        return DB::table('scholarships')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderByRaw("case status when 'open' then 1 when 'upcoming' then 2 else 3 end")->orderBy('ends_at');
    }

    private function competitionsQuery()
    {
        return DB::table('competitions')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderBy('event_date');
    }

    private function alumniQuery()
    {
        return DB::table('alumni_stories')->where('is_public', true)->where('consent_verified', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderByDesc('graduation_year');
    }

    private function facilitiesQuery()
    {
        return DB::table('facilities')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderBy('sort_order');
    }

    private function applySearch($query, Request $request, array $columns): void
    {
        if (! $request->filled('q')) {
            return;
        }

        $term = $this->clean($request->string('q'));
        $query->where(function ($builder) use ($columns, $term) {
            foreach ($columns as $column) {
                $builder->orWhere($column, 'like', '%'.$term.'%');
            }
        });
    }

    private function achievementResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'type' => $row->achievement_type,
            'recipient' => $row->recipient_name,
            'level' => $row->level,
            'organizer' => $row->organizer,
            'year' => $row->year,
            'eventDate' => $row->event_date,
            'location' => $locale === 'en' ? ($row->location_en ?: $row->location_id) : $row->location_id,
            'award' => $locale === 'en' ? ($row->rank_or_award_en ?: $row->rank_or_award_id) : $row->rank_or_award_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'certificateUrl' => $detail && $row->certificate_is_public ? $row->certificate_id : null,
            'sourceUrl' => $this->safeUrl($row->source_url),
            'url' => ($locale === 'en' ? '/en/student-life/achievements/' : '/id/kemahasiswaan/prestasi/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function scholarshipResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id,
            'provider' => $locale === 'en' ? ($row->provider_en ?: $row->provider_id) : $row->provider_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'requirements' => $detail ? ($locale === 'en' ? ($row->requirements_en ?: $row->requirements_id) : $row->requirements_id) : null,
            'benefits' => $detail ? ($locale === 'en' ? ($row->benefits_en ?: $row->benefits_id) : $row->benefits_id) : null,
            'status' => $this->timeStatus($row->status, $row->starts_at, $row->ends_at, ['open', 'upcoming', 'closed']),
            'startsAt' => $row->starts_at,
            'endsAt' => $row->ends_at,
            'registrationUrl' => $this->safeUrl($row->registration_url),
            'contactInfo' => $detail ? $row->contact_info : null,
            'url' => ($locale === 'en' ? '/en/student-life/scholarships/' : '/id/kemahasiswaan/beasiswa/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function competitionResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'organizer' => $row->organizer,
            'level' => $row->level,
            'type' => $row->competition_type,
            'status' => $this->timeStatus($row->status, $row->starts_at, $row->ends_at, ['registration_open', 'upcoming', 'registration_closed']),
            'startsAt' => $row->starts_at,
            'endsAt' => $row->ends_at,
            'eventDate' => $row->event_date,
            'location' => $locale === 'en' ? ($row->location_en ?: $row->location_id) : $row->location_id,
            'registrationUrl' => $this->safeUrl($row->registration_url),
            'poster' => $detail ? $row->poster_id : null,
            'url' => ($locale === 'en' ? '/en/student-life/competitions/' : '/id/kemahasiswaan/kompetisi/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function alumniResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'name' => $row->alumni_name,
            'slug' => $row->slug,
            'graduationYear' => $row->graduation_year,
            'position' => $locale === 'en' ? ($row->current_position_en ?: $row->current_position_id) : $row->current_position_id,
            'company' => $row->company,
            'industry' => $locale === 'en' ? ($row->industry_en ?: $row->industry_id) : $row->industry_id,
            'story' => $detail ? ($locale === 'en' ? ($row->story_en ?: $row->story_id) : $row->story_id) : null,
            'quote' => $locale === 'en' ? ($row->quote_en ?: $row->quote_id) : $row->quote_id,
            'linkedinUrl' => $this->safeUrl($row->linkedin_url),
            'personalWebsiteUrl' => $this->safeUrl($row->personal_website_url),
            'url' => ($locale === 'en' ? '/en/student-life/alumni/' : '/id/kemahasiswaan/alumni/').$row->slug,
        ];
    }

    private function facilityResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id,
            'category' => $row->category,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'location' => $locale === 'en' ? ($row->location_en ?: $row->location_id) : $row->location_id,
            'capacity' => $row->capacity,
            'features' => preg_split('/\r?\n/', (string) ($locale === 'en' ? ($row->features_en ?: $row->features_id) : $row->features_id), -1, PREG_SPLIT_NO_EMPTY),
            'url' => ($locale === 'en' ? '/en/facilities/' : '/id/fasilitas/').($locale === 'en' ? $row->slug_en : $row->slug_id),
            'detail' => $detail,
        ];
    }

    private function admissionPathResource(object $row, string $locale): array
    {
        return [
            'id' => $row->id,
            'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'requirements' => $locale === 'en' ? ($row->requirements_en ?: $row->requirements_id) : $row->requirements_id,
            'status' => $this->timeStatus($row->status, $row->starts_at, $row->ends_at, ['open', 'upcoming', 'closed']),
            'registrationUrl' => $this->safeUrl($row->registration_url),
            'startsAt' => $row->starts_at,
            'endsAt' => $row->ends_at,
        ];
    }

    private function pageData(string $type, string $locale): ?array
    {
        $row = DB::table('pages')->where('page_type', $type)->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->first();

        return $row ? [
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'content' => $locale === 'en' ? ($row->content_en ?: $row->content_id) : $row->content_id,
        ] : null;
    }

    private function faqData(string $category, string $locale): array
    {
        return DB::table('faqs')->where('category', $category)->where('is_active', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderBy('sort_order')->get()->map(fn ($row) => [
            'question' => $locale === 'en' ? ($row->question_en ?: $row->question_id) : $row->question_id,
            'answer' => $locale === 'en' ? ($row->answer_en ?: $row->answer_id) : $row->answer_id,
        ])->all();
    }

    private function hmpsData(string $locale): array
    {
        $value = SiteValue::get('student_life.hmps');

        return [
            'name' => $locale === 'en' ? ($value['name_en'] ?? 'Informatics Student Association') : ($value['name_id'] ?? 'Himpunan Mahasiswa Program Studi Informatika'),
            'description' => $locale === 'en' ? ($value['description_en'] ?? null) : ($value['description_id'] ?? null),
            'url' => $this->safeUrl($value['url'] ?? null) ?? 'https://hmps-pink.vercel.app/',
        ];
    }

    private function timeStatus(?string $status, ?string $startsAt, ?string $endsAt, array $labels): string
    {
        if ($status && ! in_array($status, ['auto', 'draft'], true)) {
            return $status;
        }

        $now = now();
        if ($startsAt && $now->lt($startsAt)) {
            return $labels[1];
        }
        if ($endsAt && $now->gt($endsAt)) {
            return $labels[2];
        }

        return $labels[0];
    }

    private function clean($value): string
    {
        return trim(Str::limit(strip_tags((string) $value), 100, ''));
    }

    private function safeUrl(?string $url): ?string
    {
        return $url && preg_match('/^https?:\/\//i', $url) === 1 ? $url : null;
    }

    private function seo(string $locale, string $titleId, string $titleEn): array
    {
        return [
            'title' => ($locale === 'en' ? $titleEn : $titleId).' | Program Studi Informatika',
            'description' => $locale === 'en'
                ? 'Student life, admissions, facilities, alumni, and contact information for the Informatics Study Program.'
                : 'Kemahasiswaan, PMB, fasilitas, alumni, dan kontak Program Studi Informatika.',
        ];
    }
}

class SiteValue
{
    /**
     * @return array<string, mixed>
     */
    public static function get(string $key): array
    {
        $value = DB::table('site_settings')->where('key', $key)->value('value');

        return $value ? json_decode($value, true) ?: [] : [];
    }
}
