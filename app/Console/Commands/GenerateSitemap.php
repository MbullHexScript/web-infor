<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate public sitemap files.';

    public function handle(SitemapService $sitemap): int
    {
        $report = $sitemap->generate();
        $this->info('Sitemap generated: '.$report['urls'].' URLs');

        return self::SUCCESS;
    }
}
