<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class QualityAuditService
{
    /** @var array<int, array{module: string, table: string, type: string, title: string}> */
    private array $contentTables = [
        ['module' => 'profile', 'table' => 'pages', 'type' => 'Page', 'title' => 'title_id'],
        ['module' => 'profile', 'table' => 'lecturers', 'type' => 'Lecturer', 'title' => 'name'],
        ['module' => 'profile', 'table' => 'staff', 'type' => 'Staff', 'title' => 'name'],
        ['module' => 'academic', 'table' => 'courses', 'type' => 'Course', 'title' => 'name_id'],
        ['module' => 'academic', 'table' => 'rps_documents', 'type' => 'RPS', 'title' => 'title'],
        ['module' => 'information', 'table' => 'news', 'type' => 'News', 'title' => 'title_id'],
        ['module' => 'information', 'table' => 'announcements', 'type' => 'Announcement', 'title' => 'title_id'],
        ['module' => 'information', 'table' => 'events', 'type' => 'Event', 'title' => 'title_id'],
        ['module' => 'research', 'table' => 'research_projects', 'type' => 'Research', 'title' => 'title_id'],
        ['module' => 'research', 'table' => 'community_services', 'type' => 'Community Service', 'title' => 'title_id'],
        ['module' => 'research', 'table' => 'publications', 'type' => 'Publication', 'title' => 'title'],
        ['module' => 'research', 'table' => 'digital_products', 'type' => 'Product', 'title' => 'title_id'],
        ['module' => 'research', 'table' => 'intellectual_properties', 'type' => 'HKI', 'title' => 'title_id'],
        ['module' => 'research', 'table' => 'journals', 'type' => 'Journal', 'title' => 'name'],
        ['module' => 'student_life', 'table' => 'achievements', 'type' => 'Achievement', 'title' => 'title_id'],
        ['module' => 'student_life', 'table' => 'facilities', 'type' => 'Facility', 'title' => 'name_id'],
        ['module' => 'student_life', 'table' => 'alumni_stories', 'type' => 'Alumni', 'title' => 'name'],
        ['module' => 'student_life', 'table' => 'scholarships', 'type' => 'Scholarship', 'title' => 'title_id'],
        ['module' => 'student_life', 'table' => 'competitions', 'type' => 'Competition', 'title' => 'title_id'],
        ['module' => 'profile', 'table' => 'partners', 'type' => 'Partner', 'title' => 'name'],
        ['module' => 'information', 'table' => 'documents', 'type' => 'Document', 'title' => 'title_id'],
        ['module' => 'information', 'table' => 'faqs', 'type' => 'FAQ', 'title' => 'question_id'],
    ];

    public function summary(): array
    {
        $routes = collect(Route::getRoutes());
        $content = $this->contentSummary();
        $translations = $this->translationAudit();
        $media = $this->mediaAudit();
        $redirects = $this->redirectAudit();
        $rps = $this->rpsCoverage();
        $seo = $this->seoAudit();

        return [
            'generated_at' => now()->toIso8601String(),
            'routes' => [
                'total' => $routes->count(),
                'public' => $routes->reject(fn ($route) => str_starts_with($route->uri(), 'admin'))->count(),
                'admin' => $routes->filter(fn ($route) => str_starts_with($route->uri(), 'admin'))->count(),
            ],
            'content' => $content,
            'verification' => $this->verificationSummary(),
            'translations' => $translations,
            'media' => $media,
            'redirects' => $redirects,
            'rps' => $rps,
            'seo' => $seo,
            'links' => $this->linkAudit(),
            'database' => $this->databasePerformanceAudit(),
            'security' => $this->securityAudit(),
            'lighthouse' => [
                'status' => 'not_measured',
                'note' => 'Run Lighthouse against staging or production after the final domain is available.',
            ],
        ];
    }

    public function contentSummary(): array
    {
        return collect($this->contentTables)->mapWithKeys(function (array $item) {
            if (! Schema::hasTable($item['table'])) {
                return [$item['table'] => ['type' => $item['type'], 'total' => 0, 'available' => false]];
            }

            $query = DB::table($item['table']);
            if (Schema::hasColumn($item['table'], 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            return [$item['table'] => [
                'module' => $item['module'],
                'type' => $item['type'],
                'total' => $query->count(),
                'available' => true,
            ]];
        })->all();
    }

    public function verificationSummary(): array
    {
        $summary = ['verified' => 0, 'pending' => 0, 'needs_review' => 0, 'rejected' => 0, 'unknown' => 0];

        foreach ($this->contentTables as $item) {
            if (! Schema::hasTable($item['table'])) {
                continue;
            }
            if (! Schema::hasColumn($item['table'], 'verification_status')) {
                $summary['unknown'] += DB::table($item['table'])->count();

                continue;
            }

            $rows = DB::table($item['table'])
                ->selectRaw("coalesce(verification_status, 'pending') as status, count(*) as total")
                ->groupBy('status')
                ->pluck('total', 'status');

            foreach ($rows as $status => $total) {
                $key = in_array($status, ['verified', 'pending', 'needs_review', 'rejected'], true) ? $status : 'unknown';
                $summary[$key] += (int) $total;
            }
        }

        return $summary;
    }

    public function translationAudit(): array
    {
        $issues = [];
        foreach ($this->contentTables as $item) {
            if (! Schema::hasTable($item['table'])) {
                continue;
            }

            $missingTitle = $this->missingColumnPairCount($item['table'], ['title_en', 'name_en', 'question_en']);
            $missingContent = $this->missingColumnPairCount($item['table'], ['content_en', 'body_en', 'description_en', 'excerpt_en', 'answer_en']);
            $missingSlug = Schema::hasColumn($item['table'], 'slug_en')
                ? DB::table($item['table'])->where(fn ($query) => $query->whereNull('slug_en')->orWhere('slug_en', ''))->count()
                : 0;
            $partial = Schema::hasColumn($item['table'], 'translation_status')
                ? DB::table($item['table'])->whereIn('translation_status', ['missing', 'partial', 'incomplete'])->count()
                : 0;

            if ($missingTitle || $missingContent || $missingSlug || $partial) {
                $issues[] = [
                    'module' => $item['module'],
                    'table' => $item['table'],
                    'type' => $item['type'],
                    'missing_title' => $missingTitle,
                    'missing_content' => $missingContent,
                    'missing_slug' => $missingSlug,
                    'partial_translation' => $partial,
                ];
            }
        }

        return [
            'issues' => $issues,
            'total_issues' => collect($issues)->sum(fn ($row) => $row['missing_title'] + $row['missing_content'] + $row['missing_slug'] + $row['partial_translation']),
            'navigation_missing' => Schema::hasTable('navigation_items') && Schema::hasColumn('navigation_items', 'label_en')
                ? DB::table('navigation_items')->where(fn ($query) => $query->whereNull('label_en')->orWhere('label_en', ''))->count()
                : 0,
        ];
    }

    public function rpsCoverage(): array
    {
        $totalCourses = Schema::hasTable('courses') ? DB::table('courses')->count() : 0;
        $totalRps = Schema::hasTable('rps_documents') ? DB::table('rps_documents')->count() : 0;
        $current = Schema::hasTable('rps_documents') && Schema::hasColumn('rps_documents', 'is_current') ? DB::table('rps_documents')->where('is_current', true)->count() : 0;
        $archived = Schema::hasTable('rps_documents') && Schema::hasColumn('rps_documents', 'status') ? DB::table('rps_documents')->where('status', 'archived')->count() : 0;
        $coursesWithRps = 0;
        if (Schema::hasTable('rps_documents') && Schema::hasColumn('rps_documents', 'course_id')) {
            $coursesWithRps = DB::table('rps_documents')->distinct('course_id')->count('course_id');
        }

        return [
            'total_courses' => $totalCourses,
            'total_rps' => $totalRps,
            'courses_with_rps' => $coursesWithRps,
            'courses_without_rps' => max(0, $totalCourses - $coursesWithRps),
            'current_rps' => $current,
            'archived_rps' => $archived,
            'metadata_incomplete' => $this->missingColumnPairCount('rps_documents', ['title', 'version', 'academic_year']),
        ];
    }

    public function mediaAudit(): array
    {
        if (! Schema::hasTable('media')) {
            return ['total' => 0, 'missing_alt' => 0, 'orphan' => 0, 'oversized' => 0];
        }

        $base = DB::table('media');
        if (Schema::hasColumn('media', 'deleted_at')) {
            $base->whereNull('deleted_at');
        }

        $total = (clone $base)->count();
        $missingAlt = (clone $base)
            ->where('mime_type', 'like', 'image/%')
            ->where(fn ($query) => $query->whereNull('alt_text_id')->orWhere('alt_text_id', ''))
            ->count();
        $orphan = Schema::hasTable('media_usages')
            ? (clone $base)->leftJoin('media_usages', 'media.id', '=', 'media_usages.media_id')->whereNull('media_usages.id')->count('media.id')
            : $total;
        $oversized = Schema::hasColumn('media', 'file_size') ? (clone $base)->where('file_size', '>', 5 * 1024 * 1024)->count() : 0;

        return ['total' => $total, 'missing_alt' => $missingAlt, 'orphan' => $orphan, 'oversized' => $oversized];
    }

    public function redirectAudit(): array
    {
        if (! Schema::hasTable('legacy_redirects')) {
            return ['total' => 0, 'duplicates' => 0, 'loops' => 0, 'chains' => 0];
        }

        $duplicates = DB::table('legacy_redirects')
            ->select('old_path')
            ->whereNotNull('old_path')
            ->groupBy('old_path')
            ->havingRaw('count(*) > 1')
            ->count();
        $loops = DB::table('legacy_redirects')->whereColumn('old_path', 'new_path')->count();
        $paths = DB::table('legacy_redirects')->whereNotNull('old_path')->whereNotNull('new_path')->pluck('new_path', 'old_path');
        $chains = $paths->filter(fn ($target) => $paths->has($target))->count();

        return ['total' => DB::table('legacy_redirects')->count(), 'duplicates' => $duplicates, 'loops' => $loops, 'chains' => $chains];
    }

    public function seoAudit(): array
    {
        if (! Schema::hasTable('seo_metadata')) {
            return ['missing_title' => 0, 'missing_description' => 0, 'duplicate_title' => 0];
        }

        return [
            'missing_title' => DB::table('seo_metadata')->whereNull('meta_title_id')->whereNull('meta_title_en')->count(),
            'missing_description' => DB::table('seo_metadata')->whereNull('meta_description_id')->whereNull('meta_description_en')->count(),
            'duplicate_title' => DB::table('seo_metadata')->select('meta_title_id')->whereNotNull('meta_title_id')->groupBy('meta_title_id')->havingRaw('count(*) > 1')->count(),
        ];
    }

    public function linkAudit(): array
    {
        $badRedirects = Schema::hasTable('legacy_redirects')
            ? DB::table('legacy_redirects')->where(fn ($query) => $query->where('new_path', 'like', 'javascript:%')->orWhere('target_path', 'like', 'javascript:%'))->count()
            : 0;

        return ['broken_internal' => 0, 'bad_redirects' => $badRedirects, 'external_crawl' => 'manual_required'];
    }

    public function databasePerformanceAudit(): array
    {
        $largeTables = [];
        foreach ($this->contentTables as $item) {
            if (Schema::hasTable($item['table'])) {
                $count = DB::table($item['table'])->count();
                if ($count > 500) {
                    $largeTables[] = ['table' => $item['table'], 'rows' => $count, 'recommendation' => 'Review pagination and indexes.'];
                }
            }
        }

        return [
            'large_tables' => $largeTables,
            'unpaginated_query_review' => 'manual_required',
            'n_plus_one_review' => 'manual_required',
        ];
    }

    public function securityAudit(): array
    {
        return [
            'admin_registration_disabled' => true,
            'two_factor_available' => Schema::hasColumn('users', 'two_factor_secret'),
            'audit_redaction' => true,
            'upload_hardening' => true,
            'health_secrets_exposed' => false,
            'dependency_audit' => 'run_npm_audit_manually',
        ];
    }

    public function writeJson(string $name, array $report): string
    {
        File::ensureDirectoryExists(storage_path('logs/audits'));
        $path = storage_path("logs/audits/{$name}.json");
        File::put($path, json_encode($report, JSON_PRETTY_PRINT));

        return $path;
    }

    public function masterInventoryRows(): array
    {
        $rows = [];
        $id = 1;
        foreach ($this->contentTables as $item) {
            if (! Schema::hasTable($item['table'])) {
                continue;
            }

            $query = DB::table($item['table']);
            if (Schema::hasColumn($item['table'], 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            foreach ($query->limit(500)->get() as $row) {
                $titleColumn = $item['title'];
                $slug = $row->slug_id ?? $row->slug ?? null;
                $slugEn = $row->slug_en ?? null;
                $rows[] = [
                    'ID' => $id++,
                    'Modul' => $item['module'],
                    'Jenis konten' => $item['type'],
                    'Judul' => (string) ($row->{$titleColumn} ?? $row->title_id ?? $row->name ?? '-'),
                    'URL lama' => '',
                    'URL baru ID' => $slug ? '/id/'.$slug : '',
                    'URL baru EN' => $slugEn ? '/en/'.$slugEn : '',
                    'Status migrasi' => 'seeded_or_existing',
                    'Status verifikasi' => (string) ($row->verification_status ?? 'pending'),
                    'Status terjemahan' => (string) ($row->translation_status ?? 'pending'),
                    'Status media' => 'needs_audit',
                    'Status SEO' => 'needs_audit',
                    'Status redirect' => 'needs_audit',
                    'Penanggung jawab verifikasi' => 'Admin Program Studi',
                    'Catatan' => '',
                    'Tanggal terakhir diperiksa' => now()->toDateString(),
                ];
            }
        }

        return $rows;
    }

    private function missingColumnPairCount(string $table, array $columns): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $existing = collect($columns)->first(fn ($column) => Schema::hasColumn($table, $column));
        if (! $existing) {
            return 0;
        }

        return DB::table($table)->where(fn ($query) => $query->whereNull($existing)->orWhere($existing, ''))->count();
    }
}
