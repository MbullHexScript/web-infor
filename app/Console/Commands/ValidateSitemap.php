<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class ValidateSitemap extends Command
{
    protected $signature = 'sitemap:validate';

    protected $description = 'Validate generated sitemap file exists and has urlset.';

    public function handle(SitemapService $sitemap): int
    {
        $report = $sitemap->validate();
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return $report['valid'] ? self::SUCCESS : self::FAILURE;
    }
}
