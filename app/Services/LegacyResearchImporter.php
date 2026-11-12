<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LegacyResearchImporter
{
    private array $sources = [
        'https://informatika.fsainsuinbanten.my.id/jurnal/' => 'journal',
        'https://informatika.fsainsuinbanten.my.id/pengabdian-2/' => 'community-service',
        'https://informatika.fsainsuinbanten.my.id/kerjasama/' => 'partnership',
        'https://fsainsuinbanten.my.id/' => 'faculty-reference',
        'https://uinbanten.ac.id/' => 'university-reference',
    ];

    /**
     * @return array<string, mixed>
     */
    public function inspect(): array
    {
        $items = [];
        foreach ($this->sources as $url => $type) {
            $items = array_merge($items, $this->inspectUrl($url, $type));
        }

        return [
            'items' => $items,
            'rejected' => array_values(array_filter($items, fn (array $item): bool => $item['verification_status'] === 'rejected')),
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

        $changed = 0;

        if (! $dryRun) {
            foreach ($items as $item) {
                DB::table('content_migrations')->updateOrInsert(
                    ['source_url' => $item['source_url']],
                    [
                        'content_type' => 'research:'.$item['type'],
                        'target_path' => $item['target_url'],
                        'verification_status' => $item['verification_status'],
                        'translation_status' => $item['translation_status'],
                        'media_migration_status' => $item['media'] === 'none' ? 'not_required' : 'pending',
                        'revision_notes' => $item['notes'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );
                $changed++;
            }
        }

        return [
            'dry_run' => $dryRun,
            'inspected' => $items->count(),
            'created_or_updated' => $changed,
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
            return [$this->item($url, $type, 'Tidak dapat mengambil sumber', null, 'rejected', $exception->getMessage())];
        }

        preg_match_all('/href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $body, $matches, PREG_SET_ORDER);
        $items = [];

        foreach ($matches as $match) {
            $href = html_entity_decode($match[1]);
            $title = trim(strip_tags($match[2]));

            if ($title === '' || Str::length($title) < 8 || str_starts_with($href, '#')) {
                continue;
            }

            $absolute = str_starts_with($href, 'http') ? $href : rtrim($url, '/').'/'.ltrim($href, '/');
            $looksRelevant = Str::contains(Str::lower($title.' '.$absolute), ['jurnal', 'pengabdian', 'riset', 'penelitian', 'publikasi', 'kerjasama', 'hki']);
            $items[$absolute] = $this->item(
                $absolute,
                $type,
                $title,
                $this->dateFromUrl($absolute),
                $looksRelevant ? 'pending' : 'rejected',
                $looksRelevant ? 'Perlu review editorial dan verifikasi data resmi.' : 'Judul/tautan tidak cukup relevan untuk modul riset.',
                str_contains($body, '<img') ? 'detected' : 'none',
            );
        }

        if ($items === []) {
            $items[] = $this->item($url, $type, 'Tidak ada item terstruktur ditemukan', null, 'pending', 'Perlu audit manual.');
        }

        return array_values($items);
    }

    private function item(string $url, string $type, string $title, ?string $year, string $verification, string $notes, string $media = 'unknown'): array
    {
        return [
            'source_url' => $url,
            'type' => $type,
            'title' => $title,
            'year' => $year,
            'lead' => null,
            'members' => [],
            'output' => null,
            'verification_status' => $verification,
            'media' => $media,
            'external_url' => $url,
            'translation_status' => 'pending',
            'target_url' => $this->targetUrl($type, Str::slug($title)),
            'notes' => $notes,
        ];
    }

    private function targetUrl(string $type, string $slug): string
    {
        return match ($type) {
            'community-service' => '/id/riset/pengabdian/'.$slug,
            'journal' => '/id/riset/rumah-jurnal',
            'partnership' => '/id/riset/kerja-sama',
            default => '/id/riset/penelitian/'.$slug,
        };
    }

    private function dateFromUrl(string $url): ?string
    {
        if (preg_match('/\/(20\d{2})\//', $url, $match) !== 1) {
            return null;
        }

        return $match[1];
    }
}
