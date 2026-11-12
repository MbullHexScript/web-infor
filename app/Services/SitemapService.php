<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * @return array<string, mixed>
     */
    public function generate(): array
    {
        $urls = collect([
            '/id', '/en',
            '/id/profil', '/en/profile',
            '/id/akademik/kurikulum', '/en/academics/curriculum',
            '/id/informasi', '/en/information',
            '/id/riset', '/en/research',
            '/id/kemahasiswaan', '/en/student-life',
            '/id/fasilitas', '/en/facilities',
            '/id/pmb', '/en/admissions',
            '/id/kontak', '/en/contact',
        ]);

        DB::table('news')->where('is_public', true)->where('verification_status', 'verified')->whereNotNull('published_at')->whereNull('deleted_at')->get(['slug_id', 'slug_en', 'updated_at'])->each(function ($row) use ($urls) {
            $urls->push('/id/informasi/berita/'.$row->slug_id);
            $urls->push('/en/information/news/'.$row->slug_en);
        });

        $domain = rtrim(config('app.url'), '/');
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls->unique()->filter() as $url) {
            $xml .= '  <url><loc>'.e($domain.$url).'</loc><lastmod>'.now()->toDateString()."</lastmod></url>\n";
        }
        $xml .= "</urlset>\n";

        File::put(public_path('sitemap.xml'), $xml);
        File::put(public_path('sitemap-pages.xml'), $xml);

        return ['urls' => $urls->unique()->count(), 'path' => public_path('sitemap.xml')];
    }

    /**
     * @return array<string, mixed>
     */
    public function validate(): array
    {
        $path = public_path('sitemap.xml');

        return [
            'exists' => File::exists($path),
            'valid' => File::exists($path) && str_contains(File::get($path), '<urlset'),
            'path' => $path,
        ];
    }
}
