<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AppCleanup extends Command
{
    protected $signature = 'app:cleanup {--dry-run : Show cleanup candidates}';

    protected $description = 'Clean old temporary reports and cache-like operational files.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $directories = [storage_path('logs/audits'), storage_path('logs/imports')];
        $candidates = collect($directories)->flatMap(fn ($dir) => File::exists($dir) ? File::files($dir) : [])->filter(fn ($file) => $file->getMTime() < now()->subDays(30)->timestamp);
        if (! $dryRun) {
            $candidates->each(fn ($file) => File::delete($file->getPathname()));
        }
        $this->line(json_encode(['dry_run' => $dryRun, 'candidates' => $candidates->count(), 'deleted' => $dryRun ? 0 : $candidates->count()], JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
