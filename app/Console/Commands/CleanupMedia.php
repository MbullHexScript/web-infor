<?php

namespace App\Console\Commands;

use App\Services\MediaLibraryService;
use Illuminate\Console\Command;

class CleanupMedia extends Command
{
    protected $signature = 'media:cleanup {--dry-run : Show candidates without deleting}';

    protected $description = 'Soft-delete orphan media records.';

    public function handle(MediaLibraryService $media): int
    {
        $report = $media->cleanup((bool) $this->option('dry-run'));
        $this->info(($report['dry_run'] ? 'Dry-run' : 'Cleanup').' complete.');
        $this->line('Candidates: '.$report['candidates']);
        $this->line('Deleted: '.$report['deleted']);

        return self::SUCCESS;
    }
}
