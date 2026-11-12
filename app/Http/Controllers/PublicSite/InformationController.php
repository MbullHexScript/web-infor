<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Services\GlobalSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class InformationController extends Controller
{
    public function landing(string $locale): Response
    {
        return Inertia::render('Public/Information/InformationLandingPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Informasi Program Studi', 'Study Program Information'),
            'news' => $this->newsQuery()->limit(3)->get()->map(fn (object $row): array => $this->newsResource($row, $locale))->all(),
            'announcements' => $this->announcementsQuery(activeOnly: true)->limit(4)->get()->map(fn (object $row): array => $this->announcementResource($row, $locale))->all(),
            'events' => $this->eventsQuery()->orderBy('start_at')->limit(4)->get()->map(fn (object $row): array => $this->eventResource($row, $locale))->all(),
            'albums' => $this->albumsQuery()->limit(3)->get()->map(fn (object $row): array => $this->albumResource($row, $locale))->all(),
            'documents' => $this->documentsQuery()->limit(5)->get()->map(fn (object $row): array => $this->documentResource($row, $locale))->all(),
            'faqs' => $this->faqsQuery()->where('is_featured', true)->limit(5)->get()->map(fn (object $row): array => $this->faqResource($row, $locale))->all(),
        ]);
    }

    public function news(Request $request, string $locale): Response
    {
        $query = $this->newsQuery()->leftJoin('news_categories', 'news.category_id', '=', 'news_categories.id')->select('news.*', 'news_categories.name_id as category_name_id', 'news_categories.name_en as category_name_en', 'news_categories.slug_id as category_slug_id', 'news_categories.slug_en as category_slug_en');

        if ($request->filled('category')) {
            $query->where($locale === 'en' ? 'news_categories.slug_en' : 'news_categories.slug_id', $request->string('category'));
        }
        if ($request->filled('year')) {
            $query->whereYear('news.published_at', (int) $request->input('year'));
        }
        if ($request->filled('q')) {
            $term = $this->cleanSearch($request->string('q')->toString());
            $query->where(function ($builder) use ($term, $locale) {
                $builder->where($locale === 'en' ? 'news.title_en' : 'news.title_id', 'like', $term.'%')
                    ->orWhere($locale === 'en' ? 'news.title_en' : 'news.title_id', 'like', '%'.$term.'%')
                    ->orWhere($locale === 'en' ? 'news.excerpt_en' : 'news.excerpt_id', 'like', '%'.$term.'%');
            });
        }

        $items = $query->paginate(9)->withQueryString();

        return Inertia::render('Public/Information/NewsIndex', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Berita', 'News'),
            'filters' => $request->only(['category', 'year', 'q']),
            'categories' => $this->categories($locale),
            'years' => DB::table('news')
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->pluck('published_at')
                ->map(fn (string $date): int => Carbon::parse($date)->year)
                ->unique()
                ->values(),
            'featured' => $this->newsQuery()->where('is_featured', true)->first()?->id,
            'news' => $items->through(fn (object $row): array => $this->newsResource($row, $locale)),
        ]);
    }

    public function newsDetail(string $slug, string $locale): Response
    {
        $row = $this->newsQuery()
            ->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)
            ->first();
        abort_unless($row, 404);

        if (! session()->has('news_viewed_'.$row->id)) {
            DB::table('news')->where('id', $row->id)->increment('view_count');
            session()->put('news_viewed_'.$row->id, now()->timestamp);
        }

        $related = $this->newsQuery()
            ->where('id', '!=', $row->id)
            ->where('category_id', $row->category_id)
            ->limit(3)
            ->get()
            ->map(fn (object $item): array => $this->newsResource($item, $locale))
            ->all();

        return Inertia::render('Public/Information/NewsDetail', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'article' => $this->newsResource($row, $locale, true),
            'related' => $related,
        ]);
    }

    public function announcements(Request $request, string $locale): Response
    {
        $query = $this->announcementsQuery(activeOnly: ! $request->boolean('archive'));
        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }
        if ($request->filled('year')) {
            $query->whereYear('published_at', (int) $request->input('year'));
        }
        if ($request->filled('q')) {
            $term = $this->cleanSearch($request->string('q')->toString());
            $query->where($locale === 'en' ? 'title_en' : 'title_id', 'like', '%'.$term.'%');
        }

        return Inertia::render('Public/Information/AnnouncementsIndex', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Pengumuman', 'Announcements'),
            'filters' => $request->only(['priority', 'year', 'q', 'archive']),
            'announcements' => $query->paginate(10)->withQueryString()->through(fn (object $row): array => $this->announcementResource($row, $locale)),
        ]);
    }

    public function announcementDetail(string $slug, string $locale): Response
    {
        $row = $this->announcementsQuery(activeOnly: false)->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Information/AnnouncementDetail', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'announcement' => $this->announcementResource($row, $locale, true),
            'related' => $this->announcementsQuery(false)->where('id', '!=', $row->id)->limit(3)->get()->map(fn (object $item): array => $this->announcementResource($item, $locale))->all(),
        ]);
    }

    public function events(Request $request, string $locale): Response
    {
        $query = $this->eventsQuery();
        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            $query = $this->filterEventStatus($query, $status);
        }
        if ($request->filled('year')) {
            $query->whereYear('start_at', (int) $request->input('year'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('start_at', (int) $request->input('month'));
        }
        if ($request->filled('q')) {
            $term = $this->cleanSearch($request->string('q')->toString());
            $query->where($locale === 'en' ? 'title_en' : 'title_id', 'like', '%'.$term.'%');
        }

        return Inertia::render('Public/Information/EventsIndex', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Agenda', 'Events'),
            'filters' => $request->only(['status', 'year', 'month', 'q', 'view']),
            'events' => $query->paginate(10)->withQueryString()->through(fn (object $row): array => $this->eventResource($row, $locale)),
        ]);
    }

    public function eventDetail(string $slug, string $locale): Response
    {
        $row = $this->eventsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($row, 404);

        return Inertia::render('Public/Information/EventDetail', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $row->title_id, $row->title_en ?: $row->title_id),
            'event' => $this->eventResource($row, $locale, true),
            'related' => $this->eventsQuery()->where('id', '!=', $row->id)->limit(3)->get()->map(fn (object $item): array => $this->eventResource($item, $locale))->all(),
        ]);
    }

    public function eventIcs(int $id): HttpResponse
    {
        $event = $this->eventsQuery()->where('id', $id)->first();
        abort_unless($event, 404);

        $ics = implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Informatika UIN SMH Banten//Information Module//ID',
            'BEGIN:VEVENT',
            'UID:event-'.$event->id.'@informatika.uinbanten.ac.id',
            'DTSTAMP:'.now('UTC')->format('Ymd\THis\Z'),
            'DTSTART:'.Carbon::parse($event->start_at)->utc()->format('Ymd\THis\Z'),
            'DTEND:'.Carbon::parse($event->end_at)->utc()->format('Ymd\THis\Z'),
            'SUMMARY:'.$this->escapeIcs($event->title_id),
            'DESCRIPTION:'.$this->escapeIcs(strip_tags((string) $event->description_id)),
            'LOCATION:'.$this->escapeIcs((string) $event->location_id),
            'END:VEVENT',
            'END:VCALENDAR',
            '',
        ]);

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="agenda-informatika-'.$event->id.'.ics"',
        ]);
    }

    public function gallery(Request $request, string $locale): Response
    {
        $query = $this->albumsQuery();
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }
        if ($request->filled('year')) {
            $query->whereYear('event_date', (int) $request->input('year'));
        }
        if ($request->filled('q')) {
            $term = $this->cleanSearch($request->string('q')->toString());
            $query->where($locale === 'en' ? 'title_en' : 'title_id', 'like', '%'.$term.'%');
        }

        return Inertia::render('Public/Information/GalleryIndex', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Galeri', 'Gallery'),
            'filters' => $request->only(['category', 'year', 'q']),
            'albums' => $query->paginate(9)->withQueryString()->through(fn (object $row): array => $this->albumResource($row, $locale)),
        ]);
    }

    public function galleryDetail(string $slug, string $locale): Response
    {
        $album = $this->albumsQuery()->where($locale === 'en' ? 'slug_en' : 'slug_id', $slug)->first();
        abort_unless($album, 404);

        $media = DB::table('album_media')
            ->leftJoin('media', 'album_media.media_id', '=', 'media.id')
            ->where('album_media.album_id', $album->id)
            ->orderBy('album_media.sort_order')
            ->get()
            ->map(fn (object $row): array => [
                'id' => $row->media_id,
                'type' => str_starts_with((string) $row->mime_type, 'video') || $row->provider ? 'video' : 'image',
                'url' => $row->external_url ?: ($row->path ? Storage::url($row->path) : '/assets/brand/logo-informatika-512.webp'),
                'alt' => $locale === 'en' ? ($row->alt_en ?: $row->alt_id) : $row->alt_id,
                'caption' => $locale === 'en' ? ($row->caption_en ?: $row->caption_id) : $row->caption_id,
                'provider' => $row->provider,
            ])
            ->all();

        return Inertia::render('Public/Information/GalleryDetail', [
            'locale' => $locale,
            'seo' => $this->seo($locale, $album->title_id, $album->title_en ?: $album->title_id),
            'album' => $this->albumResource($album, $locale, true),
            'media' => $media,
        ]);
    }

    public function downloads(Request $request, string $locale): Response
    {
        $query = $this->documentsQuery()->leftJoin('document_categories', 'documents.category_id', '=', 'document_categories.id')->select('documents.*', 'document_categories.name_id as category_name_id', 'document_categories.name_en as category_name_en', 'document_categories.slug_id as category_slug_id', 'document_categories.slug_en as category_slug_en');

        if ($request->filled('category')) {
            $query->where($locale === 'en' ? 'document_categories.slug_en' : 'document_categories.slug_id', $request->string('category'));
        }
        if ($request->filled('year')) {
            $query->where('documents.year', (int) $request->input('year'));
        }
        if ($request->filled('status')) {
            $query->where('documents.document_status', $request->string('status'));
        }
        if ($request->filled('file_type')) {
            $query->where('documents.file_type', $request->string('file_type'));
        }
        if ($request->filled('q')) {
            $term = $this->cleanSearch($request->string('q')->toString());
            $query->where($locale === 'en' ? 'documents.title_en' : 'documents.title_id', 'like', '%'.$term.'%');
        }

        return Inertia::render('Public/Information/DownloadsPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Pusat Unduhan', 'Download Center'),
            'filters' => $request->only(['category', 'year', 'status', 'file_type', 'q']),
            'categories' => DB::table('document_categories')->where('is_active', true)->orderBy('sort_order')->get()->map(fn (object $row): array => [
                'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
                'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id,
            ])->all(),
            'documents' => $query->paginate(10)->withQueryString()->through(fn (object $row): array => $this->documentResource($row, $locale)),
        ]);
    }

    public function downloadDocument(int $id)
    {
        $document = $this->documentsQuery()->where('id', $id)->first();
        abort_unless($document && $document->file_id && ! str_contains($document->file_id, '..'), 404);
        abort_unless(Storage::disk('public')->exists($document->file_id), 404);

        DB::table('documents')->where('id', $document->id)->increment('download_count');

        return Storage::disk('public')->download($document->file_id, Str::slug($document->title_id).'.'.($document->file_type ?: 'pdf'), [
            'Content-Type' => $document->mime_type ?: 'application/octet-stream',
        ]);
    }

    public function faq(Request $request, string $locale): Response
    {
        $query = $this->faqsQuery();
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }
        if ($request->filled('q')) {
            $term = $this->cleanSearch($request->string('q')->toString());
            $query->where($locale === 'en' ? 'question_en' : 'question_id', 'like', '%'.$term.'%');
        }

        return Inertia::render('Public/Information/FaqPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'FAQ', 'FAQ'),
            'filters' => $request->only(['category', 'q']),
            'categories' => $this->faqsQuery()
                ->reorder('category')
                ->select('category')
                ->distinct()
                ->pluck('category'),
            'faqs' => $query->get()->map(fn (object $row): array => $this->faqResource($row, $locale))->all(),
        ]);
    }

    public function search(Request $request, string $locale, GlobalSearchService $search): Response
    {
        $query = $request->string('q')->toString();
        abort_if(mb_strlen($query) > 80, 422);

        return Inertia::render('Public/Information/GlobalSearchPage', [
            'locale' => $locale,
            'seo' => $this->seo($locale, 'Pencarian', 'Search'),
            'filters' => $request->only(['q', 'type']),
            'results' => $search->search($query, $locale, $request->string('type')->toString() ?: null, max(1, (int) $request->input('page', 1))),
        ]);
    }

    private function newsQuery()
    {
        return DB::table('news')
            ->where('news.status', 'published')
            ->where('news.published_at', '<=', now())
            ->whereNull('news.deleted_at')
            ->orderByDesc('news.is_pinned')
            ->orderByDesc('news.published_at');
    }

    private function announcementsQuery(bool $activeOnly)
    {
        $query = DB::table('announcements')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderByDesc('is_pinned')
            ->orderByRaw("case priority when 'urgent' then 1 when 'important' then 2 else 3 end")
            ->orderByDesc('published_at');

        if ($activeOnly) {
            $query->where('starts_at', '<=', now())
                ->where(function ($builder) {
                    $builder->whereNull('expires_at')->orWhere('expires_at', '>', now());
                });
        }

        return $query;
    }

    private function eventsQuery()
    {
        return DB::table('events')
            ->where('status', 'published')
            ->where('is_public', true)
            ->whereNull('deleted_at')
            ->orderBy('start_at');
    }

    private function albumsQuery()
    {
        return DB::table('albums')
            ->where('status', 'published')
            ->where('is_public', true)
            ->whereNull('deleted_at')
            ->orderByDesc('is_featured')
            ->orderByDesc('event_date');
    }

    private function documentsQuery()
    {
        return DB::table('documents')
            ->where('documents.status', 'published')
            ->where('documents.is_public', true)
            ->whereNull('documents.deleted_at')
            ->orderByDesc('documents.published_at');
    }

    private function faqsQuery()
    {
        return DB::table('faqs')
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order');
    }

    private function filterEventStatus($query, string $status)
    {
        $now = now();

        return match ($status) {
            'upcoming' => $query->where('start_at', '>', $now)->whereNull('status_override'),
            'ongoing' => $query->where('start_at', '<=', $now)->where('end_at', '>=', $now)->whereNull('status_override'),
            'completed' => $query->where('end_at', '<', $now)->whereNull('status_override'),
            'cancelled' => $query->where('status_override', 'cancelled'),
            default => $query,
        };
    }

    private function newsResource(object $row, string $locale, bool $detail = false): array
    {
        $title = $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id;
        $content = $locale === 'en' ? ($row->content_en ?: $row->content_id) : $row->content_id;

        return [
            'id' => $row->id,
            'title' => $title,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'excerpt' => $locale === 'en' ? ($row->excerpt_en ?: $row->excerpt_id) : $row->excerpt_id,
            'content' => $detail ? $this->sanitizeHtml((string) $content) : null,
            'category' => $row->category_name_id ?? null,
            'publishedAt' => $row->published_at,
            'author' => $row->author_name,
            'sourceName' => $row->source_name,
            'sourceUrl' => $this->safeUrl($row->source_url),
            'featuredImage' => $row->featured_image_id,
            'isFeatured' => (bool) $row->is_featured,
            'readingTime' => max(1, (int) ceil(str_word_count(strip_tags((string) $content)) / 220)),
            'viewCount' => $row->view_count,
            'url' => ($locale === 'en' ? '/en/information/news/' : '/id/informasi/berita/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function announcementResource(object $row, string $locale, bool $detail = false): array
    {
        $content = $locale === 'en' ? ($row->content_en ?: $row->content_id) : $row->content_id;
        $expiresAt = $row->expires_at ? Carbon::parse($row->expires_at) : null;

        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'excerpt' => $locale === 'en' ? ($row->excerpt_en ?: $row->excerpt_id) : $row->excerpt_id,
            'content' => $detail ? $this->sanitizeHtml((string) $content) : null,
            'priority' => $row->priority,
            'startsAt' => $row->starts_at,
            'expiresAt' => $row->expires_at,
            'publishedAt' => $row->published_at,
            'expired' => $expiresAt ? $expiresAt->lte(now()) : false,
            'attachmentUrl' => $row->attachment_id ? '/documents/'.$row->attachment_id.'/download' : null,
            'externalUrl' => $this->safeUrl($row->external_url),
            'url' => ($locale === 'en' ? '/en/information/announcements/' : '/id/informasi/pengumuman/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function eventResource(object $row, string $locale, bool $detail = false): array
    {
        $status = $row->status_override ?: match (true) {
            Carbon::parse($row->start_at)->isFuture() => 'upcoming',
            Carbon::parse($row->end_at)->isPast() => 'completed',
            default => 'ongoing',
        };

        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'description' => $detail ? $this->sanitizeHtml((string) ($locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id)) : Str::limit(strip_tags((string) $row->description_id), 160),
            'startAt' => $row->start_at,
            'endAt' => $row->end_at,
            'timezone' => $row->timezone,
            'location' => $locale === 'en' ? ($row->location_en ?: $row->location_id) : $row->location_id,
            'organizer' => $locale === 'en' ? ($row->organizer_en ?: $row->organizer_id) : $row->organizer_id,
            'poster' => $row->poster_id,
            'registrationUrl' => $this->safeUrl($row->registration_url),
            'meetingUrl' => $detail && $row->status_override !== 'private' ? $this->safeUrl($row->meeting_url) : null,
            'status' => $status,
            'icsUrl' => '/events/'.$row->id.'/ics',
            'googleCalendarUrl' => $this->googleCalendarUrl($row),
            'url' => ($locale === 'en' ? '/en/information/events/' : '/id/informasi/agenda/').($locale === 'en' ? $row->slug_en : $row->slug_id),
        ];
    }

    private function albumResource(object $row, string $locale, bool $detail = false): array
    {
        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'cover' => $row->cover_image_id ?: '/assets/brand/logo-informatika-512.webp',
            'eventDate' => $row->event_date,
            'category' => $row->category,
            'mediaCount' => DB::table('album_media')->where('album_id', $row->id)->count(),
            'url' => ($locale === 'en' ? '/en/information/gallery/' : '/id/informasi/galeri/').($locale === 'en' ? $row->slug_en : $row->slug_id),
            'detail' => $detail,
        ];
    }

    private function documentResource(object $row, string $locale): array
    {
        return [
            'id' => $row->id,
            'title' => $locale === 'en' ? ($row->title_en ?: $row->title_id) : $row->title_id,
            'description' => $locale === 'en' ? ($row->description_en ?: $row->description_id) : $row->description_id,
            'category' => $locale === 'en' ? ($row->category_name_en ?? null) : ($row->category_name_id ?? null),
            'year' => $row->year,
            'version' => $row->version,
            'effectiveDate' => $row->effective_date,
            'status' => $row->document_status,
            'fileType' => $row->file_type,
            'fileSize' => $row->file_size,
            'downloadCount' => $row->download_count,
            'downloadUrl' => '/documents/'.$row->id.'/download',
        ];
    }

    private function faqResource(object $row, string $locale): array
    {
        return [
            'id' => $row->id,
            'category' => $row->category,
            'question' => $locale === 'en' ? ($row->question_en ?: $row->question_id) : $row->question_id,
            'answer' => $locale === 'en' ? ($row->answer_en ?: $row->answer_id) : $row->answer_id,
            'featured' => (bool) $row->is_featured,
        ];
    }

    private function categories(string $locale): array
    {
        return DB::table('news_categories')
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (object $row): array => [
                'slug' => $locale === 'en' ? $row->slug_en : $row->slug_id,
                'name' => $locale === 'en' ? ($row->name_en ?: $row->name_id) : $row->name_id,
            ])
            ->all();
    }

    private function sanitizeHtml(string $html): string
    {
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html) ?? '';
        $html = preg_replace('/\son\w+="[^"]*"/i', '', $html) ?? '';
        $html = preg_replace("/\son\w+='[^']*'/i", '', $html) ?? '';
        $html = preg_replace('/javascript:/i', '', $html) ?? '';

        return strip_tags($html, '<p><h2><h3><h4><ul><ol><li><a><blockquote><strong><em><table><thead><tbody><tr><th><td><img><figure><figcaption><br>');
    }

    private function safeUrl(?string $url): ?string
    {
        if (! $url || preg_match('/^https?:\/\//i', $url) !== 1) {
            return null;
        }

        return $url;
    }

    private function cleanSearch(string $query): string
    {
        return trim(Str::limit(strip_tags($query), 80, ''));
    }

    private function googleCalendarUrl(object $event): string
    {
        return 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='
            .rawurlencode($event->title_id)
            .'&dates='.Carbon::parse($event->start_at)->utc()->format('Ymd\THis\Z').'/'.Carbon::parse($event->end_at)->utc()->format('Ymd\THis\Z')
            .'&details='.rawurlencode(strip_tags((string) $event->description_id))
            .'&location='.rawurlencode((string) $event->location_id);
    }

    private function escapeIcs(string $value): string
    {
        return str_replace(['\\', "\n", "\r", ',', ';'], ['\\\\', '\\n', '', '\,', '\;'], $value);
    }

    private function seo(string $locale, string $titleId, string $titleEn): array
    {
        $title = $locale === 'en' ? $titleEn : $titleId;

        return [
            'title' => $title.' | Program Studi Informatika',
            'description' => $locale === 'en'
                ? 'Official information from the Informatics Study Program, UIN Sultan Maulana Hasanuddin Banten.'
                : 'Informasi resmi Program Studi Informatika UIN Sultan Maulana Hasanuddin Banten.',
        ];
    }
}
