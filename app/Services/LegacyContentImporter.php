<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LegacyContentImporter
{
    private string $baseUrl = 'https://informatika.fsainsuinbanten.my.id';

    /**
     * @return array<string, mixed>
     */
    public function inspect(): array
    {
        $urls = [
            $this->baseUrl.'/berita/' => 'news',
            $this->baseUrl.'/pengumuman/' => 'announcement',
            $this->baseUrl.'/kegiatan/' => 'event',
            $this->baseUrl.'/kalender-akademik/' => 'document',
            $this->baseUrl.'/jadwal-kuliah/' => 'document',
            $this->baseUrl.'/mbkm/' => 'document',
        ];

        $items = [];

        foreach ($urls as $url => $type) {
            $items = array_merge($items, $this->inspectUrl($url, $type));
        }

        return [
            'source' => $this->baseUrl,
            'items' => $items,
            'rejected' => array_values(array_filter($items, fn (array $item): bool => $item['relevance_status'] === 'rejected')),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function import(?string $type = null, bool $dryRun = true): array
    {
        $items = collect($this->inspect()['items'])
            ->when($type, fn ($collection) => $collection->where('type', $type))
            ->values();

        $created = 0;
        $skipped = 0;
        $redirects = 0;

        foreach ($items as $item) {
            if ($item['relevance_status'] === 'rejected') {
                $skipped++;

                continue;
            }

            if ($dryRun) {
                continue;
            }

            DB::table('content_migrations')->updateOrInsert(
                ['source_url' => $item['source_url']],
                [
                    'content_type' => $item['type'],
                    'target_path' => $item['target_url'],
                    'verification_status' => 'pending',
                    'translation_status' => 'pending',
                    'media_migration_status' => 'pending',
                    'revision_notes' => $item['notes'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
            $created++;

            DB::table('legacy_redirects')->updateOrInsert(
                ['source_path' => parse_url($item['source_url'], PHP_URL_PATH) ?: '/'],
                ['target_path' => $item['target_url'], 'status_code' => 301, 'updated_at' => now(), 'created_at' => now()],
            );
            $redirects++;
        }

        return [
            'dry_run' => $dryRun,
            'inspected' => $items->count(),
            'created_or_updated' => $created,
            'skipped' => $skipped,
            'redirects' => $redirects,
            'items' => $items->all(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function inspectUrl(string $url, string $type): array
    {
        try {
            $body = Http::timeout(20)->get($url)->body();
        } catch (\Throwable $exception) {
            return [[
                'source_url' => $url,
                'title' => 'Tidak dapat mengambil sumber',
                'type' => $type,
                'date' => null,
                'category' => null,
                'media' => 'unknown',
                'relevance_status' => 'rejected',
                'verification_status' => 'failed',
                'translation_status' => 'not_available',
                'target_url' => null,
                'notes' => $exception->getMessage(),
            ]];
        }

        preg_match_all('/href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $body, $matches, PREG_SET_ORDER);
        $items = [];

        foreach ($matches as $match) {
            $href = html_entity_decode($match[1]);
            $title = trim(strip_tags($match[2]));

            if (! str_starts_with($href, $this->baseUrl.'/202') || $title === '') {
                continue;
            }

            $slug = Str::slug($title ?: basename(parse_url($href, PHP_URL_PATH) ?: 'legacy-content'));
            $isDemo = str_contains(Str::lower($title.' '.$href), 'artificial-intelligence')
                || str_contains(Str::lower($title.' '.$href), 'cloud-computing')
                || preg_match('/\/\d+\/?$/', parse_url($href, PHP_URL_PATH) ?: '') === 1;

            $items[$href] = [
                'source_url' => $href,
                'title' => $title,
                'type' => $type,
                'date' => $this->dateFromUrl($href),
                'category' => $type,
                'media' => str_contains($body, '<img') ? 'detected on source page' : 'none detected',
                'relevance_status' => $isDemo ? 'rejected' : 'needs_review',
                'verification_status' => 'pending',
                'translation_status' => 'pending',
                'target_url' => $this->targetUrl($type, $slug),
                'notes' => $isDemo ? 'Ditolak dari publikasi otomatis karena terindikasi artikel demo/generik atau tanpa judul bermakna.' : 'Perlu review editorial sebelum import.',
            ];
        }

        if ($items === []) {
            $items[] = [
                'source_url' => $url,
                'title' => 'Tidak ada item terstruktur ditemukan',
                'type' => $type,
                'date' => null,
                'category' => $type,
                'media' => str_contains($body, '<img') ? 'detected on source page' : 'none detected',
                'relevance_status' => 'needs_review',
                'verification_status' => 'pending',
                'translation_status' => 'pending',
                'target_url' => $this->targetUrl($type, Str::slug(basename(parse_url($url, PHP_URL_PATH) ?: $type))),
                'notes' => 'Halaman lama perlu diperiksa manual karena tidak ada daftar artikel yang jelas.',
            ];
        }

        return array_values($items);
    }

    private function targetUrl(string $type, string $slug): string
    {
        return match ($type) {
            'announcement' => '/id/informasi/pengumuman/'.$slug,
            'event' => '/id/informasi/agenda/'.$slug,
            'gallery' => '/id/informasi/galeri/'.$slug,
            'document' => '/id/informasi/unduhan',
            default => '/id/informasi/berita/'.$slug,
        };
    }

    private function dateFromUrl(string $url): ?string
    {
        if (preg_match('/\/(\d{4})\/(\d{2})\/(\d{2})\//', $url, $match) !== 1) {
            return null;
        }

        return $match[1].'-'.$match[2].'-'.$match[3];
    }
}
