<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AuditLinks extends Command
{
    protected $signature = 'links:audit';

    protected $description = 'Audit basic internal links and redirect targets.';

    public function handle(): int
    {
        $badRedirects = DB::table('legacy_redirects')
            ->where(fn ($query) => $query->where('new_path', 'like', 'javascript:%')->orWhere('target_path', 'like', 'javascript:%'))
            ->count();
        $report = ['bad_redirects' => $badRedirects, 'external_crawl' => 'skipped', 'generated_at' => now()->toIso8601String()];
        File::ensureDirectoryExists(storage_path('logs/audits'));
        File::put(storage_path('logs/audits/link-audit.json'), json_encode($report, JSON_PRETTY_PRINT));
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return $badRedirects === 0 ? self::SUCCESS : self::FAILURE;
    }
}
