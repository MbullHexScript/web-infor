<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ResearchController extends Controller
{
    public function landing(string $locale): Response
    {
        return Inertia::render('Public/Research/ResearchLandingPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Riset dan Inovasi', 'Research and Innovation'),
            'research' => $this->researchQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->researchResource($row, $locale))->all(),
            'services' => $this->serviceQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->serviceResource($row, $locale))->all(),
            'publications' => $this->publicationQuery()->where('is_featured', true)->limit(5)->get()->map(fn ($row) => $this->publicationResource($row))->all(),
            'products' => $this->productQuery()->where('is_featured', true)->limit(3)->get()->map(fn ($row) => $this->productResource($row, $locale))->all(),
            'ips' => $this->ipQuery()->limit(5)->get()->map(fn ($row) => $this->ipResource($row, $locale))->all(),
            'groups' => $this->groupsQuery()->limit(5)->get()->map(fn ($row) => $this->groupResource($row, $locale))->all(),
            'journals' => $this->journalsQuery()->get()->map(fn ($row) => $this->journalResource($row, $locale))->all(),
            'partners' => $this->partnershipsData($locale),
        ]);
    }

    public function projects(Request $request, string $locale): Response
    {
        $query = $this->researchQuery()->leftJoin('expertise_groups', 'research_projects.expertise_group_id', '=', 'expertise_groups.id')->select('research_projects.*', 'expertise_groups.name_id as group_name_id', 'expertise_groups.name_en as group_name_en');
        $this->applyCommonFilters($query, $request, $locale, 'research_projects', ['title_id', 'title_en', 'abstract_id', 'abstract_en']);

        if ($request->filled('group')) {
            $query->where($locale === 'en' ? 'expertise_groups.slug_en' : 'expertise_groups.slug_id', $request->string('group'));
        }
        if ($request->filled('lecturer')) {
            $query->where('lead_lecturer_id', (int) $request->input('lecturer'));
        }

        return Inertia::render('Public/Research/ResearchProjectsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Penelitian Dosen', 'Research Projects'),
            'filters' => $request->only(['q', 'year', 'status', 'group', 'lecturer']),
            'groups' => $this->groupsQuery()->get()->map(fn ($row) => ['slug' => $locale === 'en' ? $row->slug_en : $row->slug_id, 'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id])->all(),
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->researchResource($row, $locale)),
        ]);
    }

    public function projectDetail(string $slug, string $locale): Response
    {
        $row = $this->researchQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Research/ResearchDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'item' => $this->researchResource($row, $locale, true),
            'related' => $this->researchQuery()->where('id', '!=', $row->id)->where('expertise_group_id', $row->expertise_group_id)->limit(3)->get()->map(fn ($item) => $this->researchResource($item, $locale))->all(),
        ]);
    }

    public function communityServices(Request $request, string $locale): Response
    {
        $query = $this->serviceQuery();
        $this->applyCommonFilters($query, $request, $locale, 'community_services', ['title_id', 'title_en', 'description_id', 'description_en']);
        if ($request->filled('location')) {
            $query->where($locale === 'en' ? 'location_en' : 'location_id', 'like', '%'.$this->clean($request->string('location')).'%');
        }

        return Inertia::render('Public/Research/CommunityServicesPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Pengabdian kepada Masyarakat', 'Community Service'),
            'filters' => $request->only(['q', 'year', 'status', 'location']),
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->serviceResource($row, $locale)),
        ]);
    }

    public function communityServiceDetail(string $slug, string $locale): Response
    {
        $row = $this->serviceQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Research/CommunityServiceDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'item' => $this->serviceResource($row, $locale, true),
        ]);
    }

    public function publications(Request $request, string $locale): Response
    {
        $query = $this->publicationQuery();
        if ($request->filled('q')) {
            $term = $this->clean($request->string('q'));
            $query->where(fn ($builder) => $builder->where('title', 'like', '%'.$term.'%')->orWhere('authors', 'like', '%'.$term.'%'));
        }
        if ($request->filled('year')) {
            $query->where('year', (int) $request->input('year'));
        }
        if ($request->filled('type')) {
            $query->where('publication_type', $request->string('type'));
        }

        return Inertia::render('Public/Research/PublicationsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Publikasi', 'Publications'),
            'filters' => $request->only(['q', 'year', 'type']),
            'items' => $query->paginate(12)->withQueryString()->through(fn ($row) => $this->publicationResource($row)),
        ]);
    }

    public function citation(int $id, string $format)
    {
        $row = $this->publicationQuery()->where('id', $id)->first();
        abort_unless($row && in_array($format, ['bibtex', 'ris'], true), 404);
        $body = $format === 'bibtex'
            ? "@article{publication{$row->id},\n  title={".$row->title."},\n  author={".$row->authors."},\n  year={".$row->year."}\n}\n"
            : "TY  - JOUR\nTI  - {$row->title}\nAU  - ".str_replace(';', "\nAU  -", (string) $row->authors)."\nPY  - {$row->year}\nER  -\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    public function products(Request $request, string $locale): Response
    {
        $query = $this->productQuery();
        $this->applyCommonFilters($query, $request, $locale, 'digital_products', ['title_id', 'title_en', 'description_id', 'description_en']);
        if ($request->filled('type')) {
            $query->where('product_type', $request->string('type'));
        }

        return Inertia::render('Public/Research/ProductsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Produk dan Aplikasi', 'Products and Applications'),
            'filters' => $request->only(['q', 'year', 'status', 'type']),
            'items' => $query->paginate(9)->withQueryString()->through(fn ($row) => $this->productResource($row, $locale)),
        ]);
    }

    public function productDetail(string $slug, string $locale): Response
    {
        $row = $this->productQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Research/ProductDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'item' => $this->productResource($row, $locale, true),
        ]);
    }

    public function intellectualProperties(Request $request, string $locale): Response
    {
        $query = $this->ipQuery();
        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$this->clean($request->string('q')).'%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('status')) {
            $query->where('ip_status', $request->string('status'));
        }
        if ($request->filled('year')) {
            $query->whereYear('registration_date', (int) $request->input('year'));
        }

        return Inertia::render('Public/Research/IntellectualPropertiesPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Hak Kekayaan Intelektual', 'Intellectual Property'),
            'filters' => $request->only(['q', 'type', 'status', 'year']),
            'items' => $query->paginate(10)->withQueryString()->through(fn ($row) => $this->ipResource($row, $locale)),
        ]);
    }

    public function expertiseGroups(string $locale): Response
    {
        return Inertia::render('Public/Research/ExpertiseGroupsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Kelompok Keahlian', 'Expertise Groups'),
            'items' => $this->groupsQuery()->get()->map(fn ($row) => $this->groupResource($row, $locale))->all(),
        ]);
    }

    public function expertiseGroupDetail(string $slug, string $locale): Response
    {
        $row = $this->groupsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Research/ExpertiseGroupDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->name_id, $row->name_en ?: $row->name_id),
            'item' => $this->groupResource($row, $locale, true),
            'research' => $this->researchQuery()->where('expertise_group_id', $row->id)->limit(6)->get()->map(fn ($item) => $this->researchResource($item, $locale))->all(),
            'publications' => $this->publicationQuery()->where('expertise_group_id', $row->id)->limit(6)->get()->map(fn ($item) => $this->publicationResource($item))->all(),
        ]);
    }

    public function journals(string $locale): Response
    {
        return Inertia::render('Public/Research/JournalsLandingPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Rumah Jurnal Informatika', 'Informatics Journal Hub'),
            'items' => $this->journalsQuery()->get()->map(fn ($row) => $this->journalResource($row, $locale))->all(),
        ]);
    }

    public function journalDetail(string $slug, string $locale): Response
    {
        $row = $this->journalsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Research/JournalDetailPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->name, $row->name),
            'item' => $this->journalResource($row, $locale, true),
        ]);
    }

    public function partnerships(string $locale): Response
    {
        return Inertia::render('Public/Research/ResearchPartnershipsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Kerja Sama Riset', 'Research Partnerships'),
            'items' => $this->partnershipsData($locale),
        ]);
    }

    private function researchQuery()
    {
        return DB::table('research_projects')->where('research_projects.is_public', true)->where('research_projects.verification_status', 'verified')->whereNotNull('research_projects.published_at')->whereNull('research_projects.deleted_at')->orderByDesc('research_projects.year');
    }

    private function serviceQuery()
    {
        return DB::table('community_services')->where('is_public', true)->where('verification_status', 'verified')->whereNotNull('published_at')->whereNull('deleted_at')->orderByDesc('year');
    }

    private function publicationQuery()
    {
        return DB::table('publications')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderByDesc('year');
    }

    private function productQuery()
    {
        return DB::table('digital_products')->where('is_public', true)->where('verification_status', 'verified')->whereNotNull('published_at')->whereNull('deleted_at')->orderByDesc('year');
    }

    private function ipQuery()
    {
        return DB::table('intellectual_properties')->where('is_public', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderByDesc('registration_date');
    }

    private function groupsQuery()
    {
        return DB::table('expertise_groups')->where('is_active', true)->where('verification_status', 'verified')->whereNull('deleted_at')->orderBy('sort_order');
    }

    private function journalsQuery()
    {
        return DB::table('journals')->where('is_active', true)->whereNull('deleted_at')->whereIn('status', ['preparing', 'active'])->orderBy('sort_order');
    }

    private function applyCommonFilters($query, Request $request, string $locale, string $table, array $searchColumns): void
    {
        if ($request->filled('q')) {
            $term = $this->clean($request->string('q'));
            $query->where(function ($builder) use ($searchColumns, $term, $table) {
                foreach ($searchColumns as $column) {
                    $builder->orWhere($table.'.'.$column, 'like', '%'.$term.'%');
                }
            });
        }
        if ($request->filled('year')) {
            $query->where($table.'.year', (int) $request->input('year'));
        }
        if ($request->filled('status')) {
            $query->where($table.'.status', $request->string('status'));
        }
    }

    private function researchResource(object $row, string $locale, bool $detail = false): array
    {
        $team = $row->team_members_json ? json_decode($row->team_members_json, true) : [];

        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'summary' => $locale === 'en' ? ($row->abstract_en ?: $row->abstract_id) : $row->abstract_id,
            'year' => $row->year,
            'status' => $row->status,
            'lead' => $row->lead_researcher_name,
            'team' => $detail ? $team : [],
            'group' => $locale === 'en' ? ($row->group_name_en ?? null) : ($row->group_name_id ?? null),
            'output' => $locale === 'en' ? ($row->output_en ?: $row->output_id) : $row->output_id,
            'externalUrl' => $this->safeUrl($row->external_url),
            'url' => ($locale === 'en' ? '/en/research/projects/' : '/id/riset/penelitian/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function serviceResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'summary' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'year' => $row->year,
            'status' => $row->status,
            'location' => $locale === 'en' ? ($row->location_en ?: $row->location_id) : $row->location_id,
            'lead' => $row->lead_person_name,
            'beneficiaries' => $locale === 'en' ? ($row->beneficiaries_en ?: $row->beneficiaries_id) : $row->beneficiaries_id,
            'outputs' => $detail ? ($locale === 'en' ? ($row->outputs_en ?: $row->outputs_id) : $row->outputs_id) : null,
            'url' => ($locale === 'en' ? '/en/research/community-service/' : '/id/riset/pengabdian/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function publicationResource(object $row): array
    {
        return [
            'id' => $row->id,
            'title' => $row->title,
            'authors' => $row->authors,
            'type' => $row->publication_type,
            'venue' => $row->journal_or_conference,
            'publisher' => $row->publisher,
            'year' => $row->year,
            'doi' => $row->doi,
            'url' => $this->safeUrl($row->url),
            'bibtexUrl' => '/research/publications/'.$row->id.'/citation/bibtex',
            'risUrl' => '/research/publications/'.$row->id.'/citation/ris',
        ];
    }

    private function productResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'summary' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'type' => $row->product_type,
            'year' => $row->year,
            'status' => $row->status,
            'technologies' => $row->technologies_json ? json_decode($row->technologies_json, true) : [],
            'demoUrl' => $this->safeUrl($row->demo_url),
            'repositoryUrl' => $detail ? $this->safeUrl($row->repository_url) : null,
            'documentationUrl' => $this->safeUrl($row->documentation_url),
            'url' => ($locale === 'en' ? '/en/research/products/' : '/id/riset/produk/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function ipResource(object $row, string $locale): array
    {
        return [
            'id' => $row->id,
            'title' => $row->title,
            'type' => $row->type,
            'registrationNumber' => $row->registration_number,
            'holder' => $row->holder,
            'inventors' => $row->inventors,
            'year' => $row->registration_date ? Str::substr($row->registration_date, 0, 4) : null,
            'status' => $row->ip_status,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
        ];
    }

    private function groupResource(object $row, string $locale, bool $detail = false): array
    {
        $members = $detail ? DB::table('expertise_group_lecturer')->leftJoin('lecturers', 'expertise_group_lecturer.lecturer_id', '=', 'lecturers.id')->where('expertise_group_id', $row->id)->orderBy('expertise_group_lecturer.sort_order')->get(['lecturers.title_id as name', 'expertise_group_lecturer.role'])->all() : [];

        return [
            'id' => $row->id,
            'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'focus' => $locale === 'en' ? ($row->research_focus_en ?: $row->research_focus_id) : $row->research_focus_id,
            'icon' => $row->icon,
            'members' => $members,
            'url' => ($locale === 'en' ? '/en/research/expertise-groups/' : '/id/riset/kelompok-keahlian/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function journalResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'code' => $row->code,
            'name' => $row->name,
            'fullName' => $row->full_name,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'tagline' => $locale === 'en' ? ($row->tagline_en ?: $row->tagline_id ?: $row->tagline) : ($row->tagline_id ?: $row->tagline),
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'scope' => preg_split('/\r?\n/', (string) ($locale === 'en' ? ($row->scope_en ?: $row->scope_id) : $row->scope_id), -1, PREG_SPLIT_NO_EMPTY),
            'publisher' => $row->publisher,
            'status' => $row->status,
            'verificationStatus' => $row->verification_status,
            'printIssn' => $detail ? $row->print_issn : null,
            'electronicIssn' => $detail ? $row->electronic_issn : null,
            'editor' => $detail ? $row->editor_in_chief : null,
            'ojsUrl' => $this->safeUrl($row->ojs_url),
            'authorGuidelinesUrl' => $this->safeUrl($row->author_guidelines_url),
            'articleTemplateUrl' => $this->safeUrl($row->article_template_url),
            'editorialEmail' => $row->editorial_email,
            'accentStyle' => $row->accent_style,
            'url' => ($locale === 'en' ? '/en/research/journals/' : '/id/riset/rumah-jurnal/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function partnershipsData(string $locale): array
    {
        return DB::table('research_partnerships')
            ->leftJoin('partners', 'research_partnerships.partner_id', '=', 'partners.id')
            ->where('research_partnerships.is_public', true)
            ->where('research_partnerships.verification_status', 'verified')
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
                'partner' => $row->title_id ?? $row->title_en,
                'scope' => $locale === 'en' ? ($row->scope_en ?: $row->scope_id) : $row->scope_id,
                'startDate' => $row->start_date,
                'endDate' => $row->end_date,
            ])
            ->all();
    }

    private function clean($value): string
    {
        return trim(Str::limit(strip_tags((string) $value), 80, ''));
    }

    private function safeUrl(?string $url): ?string
    {
        return $url && preg_match('/^https?:\/\//i', $url) === 1 ? $url : null;
    }

    private function seo(string $locale, string $titleId, string $titleEn): array
    {
        return [
            'title' => ($locale === 'en' ? $titleEn : $titleId).' | Program Studi Informatika',
            'description' => $locale === 'en' ? 'Research and innovation from the Informatics Study Program.' : 'Riset dan inovasi Program Studi Informatika.',
        ];
    }
}
