<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GlobalSearchService
{
    /**
     * @return array<string, mixed>
     */
    public function search(string $query, string $locale, ?string $type = null, int $page = 1): array
    {
        $term = trim(Str::limit(strip_tags($query), 80, ''));

        if (mb_strlen($term) < 2) {
            return [
                'query' => $term,
                'groups' => [],
                'pagination' => new LengthAwarePaginator([], 0, 10, $page),
            ];
        }

        $types = $type ? [$type] : ['pages', 'lecturers', 'courses', 'rps', 'news', 'announcements', 'events', 'documents', 'journals'];
        $groups = collect($types)
            ->mapWithKeys(fn (string $itemType): array => [$itemType => $this->searchType($itemType, $term, $locale)])
            ->filter(fn (Collection $items): bool => $items->isNotEmpty())
            ->all();

        $flattened = collect($groups)->flatMap(fn (Collection $items): Collection => $items)->values();
        $perPage = 10;
        $slice = $flattened->forPage($page, $perPage)->values();

        return [
            'query' => $term,
            'groups' => collect($groups)->map(fn (Collection $items): array => $items->take(5)->values()->all())->all(),
            'pagination' => new LengthAwarePaginator($slice, $flattened->count(), $perPage, $page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]),
        ];
    }

    private function searchType(string $type, string $term, string $locale): Collection
    {
        return match ($type) {
            'pages' => $this->tableResults('pages', $term, $locale, 'profile', 'slug', '/'.$locale.'/profil'),
            'lecturers' => $this->tableResults('lecturers', $term, $locale, 'lecturer', 'slug', $locale === 'en' ? '/en/profile/lecturers' : '/id/profil/dosen'),
            'courses' => $this->courseResults($term, $locale),
            'rps' => $this->rpsResults($term, $locale),
            'news' => $this->newsResults($term, $locale),
            'announcements' => $this->announcementResults($term, $locale),
            'events' => $this->eventResults($term, $locale),
            'documents' => $this->documentResults($term, $locale),
            'journals' => $this->tableResults('journals', $term, $locale, 'journal', 'slug', '/'.$locale.'/riset/rumah-jurnal'),
            default => collect(),
        };
    }

    private function tableResults(string $table, string $term, string $locale, string $type, string $slugColumn, string $baseUrl): Collection
    {
        $titleColumn = $locale === 'en' ? 'title_en' : 'title_id';

        return DB::table($table)
            ->where(function ($query) use ($term, $titleColumn) {
                $query->where($titleColumn, 'like', $term.'%')
                    ->orWhere($titleColumn, 'like', '%'.$term.'%');
            })
            ->where(function ($query) use ($table) {
                if (SchemaHelper::hasColumn($table, 'is_active')) {
                    $query->where('is_active', true);
                }
                if (SchemaHelper::hasColumn($table, 'status')) {
                    $query->where('status', 'published');
                }
            })
            ->limit(5)
            ->get()
            ->map(fn (object $row): array => $this->result($type, $row->{$titleColumn} ?: $row->title_id, null, $baseUrl.'/'.($row->{$slugColumn} ?? ''), $term));
    }

    private function courseResults(string $term, string $locale): Collection
    {
        return DB::table('courses')
            ->where('is_active', true)
            ->where(function ($query) use ($term, $locale) {
                $query->where('code', 'like', $term.'%')
                    ->orWhere($locale === 'en' ? 'name_en' : 'name_id', 'like', $term.'%')
                    ->orWhere($locale === 'en' ? 'name_en' : 'name_id', 'like', '%'.$term.'%');
            })
            ->limit(8)
            ->get()
            ->map(fn (object $row): array => $this->result('course', ($row->code.' - '.($locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id)), 'Semester '.$row->semester, ($locale === 'en' ? '/en/academics/courses/' : '/id/akademik/mata-kuliah/').($locale === 'en' ? $row->slug_en : $row->slug_id), $term));
    }

    private function rpsResults(string $term, string $locale): Collection
    {
        return DB::table('rps_documents')
            ->leftJoin('courses', 'rps_documents.course_id', '=', 'courses.id')
            ->where('rps_documents.status', 'published')
            ->where('rps_documents.is_public', true)
            ->where(function ($query) use ($term) {
                $query->where('rps_documents.title_id', 'like', $term.'%')
                    ->orWhere('rps_documents.title_id', 'like', '%'.$term.'%')
                    ->orWhere('courses.code', 'like', $term.'%');
            })
            ->limit(5)
            ->get(['rps_documents.*', 'courses.code'])
            ->map(fn (object $row): array => $this->result('rps', ($locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id), $row->code, ($locale === 'en' ? '/en/academics/course-plans/' : '/id/akademik/rps/').$row->id.'/preview', $term));
    }

    private function newsResults(string $term, string $locale): Collection
    {
        $title = $locale === 'en' ? 'title_en' : 'title_id';
        $excerpt = $locale === 'en' ? 'excerpt_en' : 'excerpt_id';
        $slug = $locale === 'en' ? 'slug_en' : 'slug_id';

        return DB::table('news')
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($query) use ($term, $title, $excerpt) {
                $query->where($title, 'like', $term.'%')
                    ->orWhere($title, 'like', '%'.$term.'%')
                    ->orWhere($excerpt, 'like', '%'.$term.'%');
            })
            ->limit(8)
            ->get()
            ->map(fn (object $row): array => $this->result('news', $row->{$title} ?: $row->title_id, $row->{$excerpt}, ($locale === 'en' ? '/en/information/news/' : '/id/informasi/berita/').$row->{$slug}, $term));
    }

    private function announcementResults(string $term, string $locale): Collection
    {
        $title = $locale === 'en' ? 'title_en' : 'title_id';
        $excerpt = $locale === 'en' ? 'excerpt_en' : 'excerpt_id';
        $slug = $locale === 'en' ? 'slug_en' : 'slug_id';

        return DB::table('announcements')
            ->where('status', 'published')
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($query) use ($term, $title, $excerpt) {
                $query->where($title, 'like', $term.'%')
                    ->orWhere($title, 'like', '%'.$term.'%')
                    ->orWhere($excerpt, 'like', '%'.$term.'%');
            })
            ->limit(5)
            ->get()
            ->map(fn (object $row): array => $this->result('announcement', $row->{$title} ?: $row->title_id, $row->{$excerpt}, ($locale === 'en' ? '/en/information/announcements/' : '/id/informasi/pengumuman/').$row->{$slug}, $term));
    }

    private function eventResults(string $term, string $locale): Collection
    {
        $title = $locale === 'en' ? 'title_en' : 'title_id';
        $description = $locale === 'en' ? 'description_en' : 'description_id';
        $slug = $locale === 'en' ? 'slug_en' : 'slug_id';

        return DB::table('events')
            ->where('status', 'published')
            ->where('is_public', true)
            ->where(function ($query) use ($term, $title, $description) {
                $query->where($title, 'like', $term.'%')
                    ->orWhere($title, 'like', '%'.$term.'%')
                    ->orWhere($description, 'like', '%'.$term.'%');
            })
            ->limit(5)
            ->get()
            ->map(fn (object $row): array => $this->result('event', $row->{$title} ?: $row->title_id, $row->{$description}, ($locale === 'en' ? '/en/information/events/' : '/id/informasi/agenda/').$row->{$slug}, $term));
    }

    private function documentResults(string $term, string $locale): Collection
    {
        $title = $locale === 'en' ? 'title_en' : 'title_id';
        $description = $locale === 'en' ? 'description_en' : 'description_id';

        return DB::table('documents')
            ->where('status', 'published')
            ->where('is_public', true)
            ->where(function ($query) use ($term, $title, $description) {
                $query->where($title, 'like', $term.'%')
                    ->orWhere($title, 'like', '%'.$term.'%')
                    ->orWhere($description, 'like', '%'.$term.'%');
            })
            ->limit(5)
            ->get()
            ->map(fn (object $row): array => $this->result('document', $row->{$title} ?: $row->title_id, $row->{$description}, '/documents/'.$row->id.'/download', $term));
    }

    private function result(string $type, string $title, ?string $excerpt, string $url, string $term): array
    {
        return [
            'type' => $type,
            'title' => $title,
            'excerpt' => Str::limit(strip_tags((string) $excerpt), 160),
            'url' => $url,
            'metadata' => ['matched' => $term],
        ];
    }
}

class SchemaHelper
{
    /** @var array<string, bool> */
    private static array $cache = [];

    public static function hasColumn(string $table, string $column): bool
    {
        $key = $table.'.'.$column;

        return self::$cache[$key] ??= Schema::hasColumn($table, $column);
    }
}
