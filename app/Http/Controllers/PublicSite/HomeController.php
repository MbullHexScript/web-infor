<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(string $locale): Response
    {
        abort_unless(in_array($locale, ['id', 'en'], true), 404);

        return Inertia::render('Public/HomePage', Cache::remember(
            "homepage.{$locale}",
            now()->addMinutes(15),
            fn (): array => $this->payload($locale),
        ));
    }

    private function payload(string $locale): array
    {
        $identity = $this->setting('site.identity');
        $links = $this->setting('site.links');
        $contact = $this->setting('site.contact');
        $hero = $this->setting('homepage.hero');
        $studyFields = collect($this->setting('homepage.study_fields'))
            ->where('is_active', true)
            ->sortBy('sort_order')
            ->values()
            ->all();
        $curriculum = $this->setting('homepage.curriculum');

        $sections = DB::table('homepage_sections')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (object $section): array => [
                'key' => $section->key,
                'title' => $locale === 'en' ? ($section->title_en ?: $section->title_id) : $section->title_id,
                'subtitle' => $locale === 'en' ? ($section->subtitle_en ?: $section->subtitle_id) : $section->subtitle_id,
                'backgroundStyle' => $section->background_style,
                'sortOrder' => $section->sort_order,
            ])
            ->values()
            ->all();

        return [
            'locale' => $locale,
            'identity' => $identity,
            'links' => $links,
            'contact' => $contact,
            'hero' => $hero,
            'sections' => $sections,
            'topLinks' => $this->topLinks($links),
            'quickLinks' => $this->quickLinks(),
            'announcements' => $this->announcements(),
            'statistics' => $this->statistics(),
            'studyFields' => $studyFields,
            'curriculum' => $curriculum,
            'journals' => $this->journals(),
            'content' => $this->contentPlaceholders(),
            'seo' => $this->seo($locale, $identity),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function setting(string $key): array
    {
        $value = DB::table('site_settings')->where('key', $key)->value('value');

        return $value ? json_decode((string) $value, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    /**
     * @param  array<string, mixed>  $links
     * @return array<int, array<string, mixed>>
     */
    private function topLinks(array $links): array
    {
        return [
            $this->topLink('UIN SMH Banten', $links['uin'] ?? '#'),
            $this->topLink('Fakultas Sains dan Teknologi', $links['faculty'] ?? '#'),
            $this->topLink('SIAKAD', $links['siakad'] ?? '#'),
            $this->topLink('E-Learning', $links['elearning'] ?? '#'),
            $this->topLink('Perpustakaan', $links['library'] ?? '#'),
            $this->topLink('PMB', $links['pmb'] ?? '#'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function topLink(string $label, string $url): array
    {
        return [
            'label' => $label,
            'url' => $url,
            'external' => str_starts_with($url, 'http'),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function quickLinks(): array
    {
        return DB::table('quick_links')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get()
            ->map(fn (object $link): array => [
                'key' => $link->key,
                'label_id' => $link->label_id,
                'label_en' => $link->label_en,
                'url' => $link->url,
                'icon' => $link->icon,
                'isExternal' => (bool) $link->is_external,
                'sortOrder' => $link->sort_order,
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function announcements(): array
    {
        $now = now();

        return DB::table('announcements')
            ->where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->filter(function (object $announcement) use ($now): bool {
                $metadata = $this->metadata($announcement);
                $startsAt = isset($metadata['starts_at']) ? Carbon::parse($metadata['starts_at']) : null;
                $expiresAt = isset($metadata['expires_at']) ? Carbon::parse($metadata['expires_at'])->endOfDay() : null;

                return (! $startsAt || $startsAt->lte($now)) && (! $expiresAt || $expiresAt->gte($now));
            })
            ->sortBy(fn (object $announcement): int => match ($this->metadata($announcement)['priority'] ?? 'normal') {
                'urgent' => 1,
                'important' => 2,
                default => 3,
            })
            ->take(3)
            ->map(fn (object $announcement): array => [
                'title_id' => $announcement->title_id,
                'title_en' => $announcement->title_en,
                'date' => Carbon::parse($announcement->updated_at)->toDateString(),
                'priority' => $this->metadata($announcement)['priority'] ?? 'normal',
                'featured' => (bool) ($this->metadata($announcement)['featured'] ?? false),
                'url' => '/id/informasi/pengumuman',
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function statistics(): array
    {
        return DB::table('statistics')
            ->where('is_active', true)
            ->where('verification_status', 'verified')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (object $statistic): array => [
                'key' => $statistic->key,
                'label_id' => $statistic->label_id,
                'label_en' => $statistic->label_en,
                'value' => $statistic->value,
                'suffix' => $statistic->suffix,
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function journals(): array
    {
        return DB::table('journals')
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->limit(3)
            ->get()
            ->map(fn (object $journal): array => [
                'name' => $journal->name,
                'slug' => $journal->slug,
                'fullName' => $journal->full_name,
                'tagline' => $journal->tagline,
                'scope_id' => $journal->scope_id,
                'scope_en' => $journal->scope_en,
                'statusLabel' => $journal->status_label,
                'ojsUrl' => $journal->ojs_url,
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function contentPlaceholders(): array
    {
        return [
            'headMessage' => [
                'name' => 'Ahmad Tabrani',
                'degree' => 'M.T.I.',
                'position_id' => 'Ketua Program Studi Informatika',
                'position_en' => 'Head of Informatics Study Program',
                'photo' => '/assets/people/ahmad-tabrani-sakti.jpg',
                'quote_id' => 'Kami membangun lingkungan akademik yang memadukan kompetensi teknologi, integritas, dan nilai-nilai keislaman untuk menghadapi masa depan digital.',
                'quote_en' => 'We cultivate an academic environment that integrates technological competence, integrity, and Islamic values to shape a better digital future.',
            ],
            'news' => [],
            'events' => [],
            'lecturers' => $this->lecturers(),
            'research' => [],
            'achievements' => [],
            'facilities' => [],
        ];
    }

    /**
     * @param  array<string, mixed>  $identity
     * @return array<string, mixed>
     */
    private function seo(string $locale, array $identity): array
    {
        $isEnglish = $locale === 'en';
        $title = $isEnglish
            ? 'Informatics Study Program | Sultan Maulana Hasanuddin State Islamic University Banten'
            : 'Program Studi Informatika | UIN Sultan Maulana Hasanuddin Banten';
        $description = $isEnglish
            ? 'Official website of the Informatics Study Program, Faculty of Science and Technology, Sultan Maulana Hasanuddin State Islamic University Banten. Explore curriculum, lecturers, course plans, research, achievements, news, and admissions.'
            : 'Website resmi Program Studi Informatika Fakultas Sains dan Teknologi UIN Sultan Maulana Hasanuddin Banten. Informasi kurikulum, dosen, RPS, riset, prestasi, berita, dan penerimaan mahasiswa baru.';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => ($identity['domain'] ?? 'https://informatika.uinbanten.ac.id').'/'.$locale,
            'alternateId' => ($identity['domain'] ?? 'https://informatika.uinbanten.ac.id').'/id',
            'alternateEn' => ($identity['domain'] ?? 'https://informatika.uinbanten.ac.id').'/en',
            'ogImage' => '/assets/brand/logo-informatika-512.webp',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function metadata(object $record): array
    {
        return $record->metadata ? json_decode((string) $record->metadata, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function lecturers(): array
    {
        return DB::table('lecturers')
            ->where('status', 'published')
            ->where('verification_status', 'verified')
            ->orderByDesc('is_featured')
            ->orderBy('title_id')
            ->limit(6)
            ->get()
            ->map(fn (object $lecturer): array => [
                'name' => $lecturer->title_id,
                'degree' => $this->metadata($lecturer)['degree'] ?? '',
                'expertise_id' => $this->metadata($lecturer)['expertise_id'] ?? 'Bidang keahlian dapat diperbarui admin.',
                'expertise_en' => $this->metadata($lecturer)['expertise_en'] ?? 'Expertise can be updated by admin.',
                'position_id' => $this->metadata($lecturer)['position_id'] ?? null,
                'position_en' => $this->metadata($lecturer)['position_en'] ?? null,
                'photo' => $this->metadata($lecturer)['photo'] ?? null,
                'slug' => $lecturer->slug,
            ])
            ->all();
    }
}
