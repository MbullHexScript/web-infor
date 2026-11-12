<?php

namespace App\Console\Commands;

use App\Services\LegacyContentImporter;
use Illuminate\Console\Command;

class ImportLegacyContent extends Command
{
    protected $signature = 'content:import-legacy {--type=} {--all} {--dry-run}';

    protected $description = 'Import legacy information content in a controlled, idempotent workflow.';

    public function handle(LegacyContentImporter $importer): int
    {
        $type = $this->option('all') ? null : ($this->option('type') ?: null);
        $report = $importer->import(is_string($type) ? $type : null, (bool) $this->option('dry-run'));

        $this->info('Legacy content import '.($report['dry_run'] ? 'dry-run' : 'commit').' completed.');
        $this->line('Inspected: '.$report['inspected']);
        $this->line('Created/updated migrations: '.$report['created_or_updated']);
        $this->line('Skipped: '.$report['skipped']);
        $this->line('Redirects: '.$report['redirects']);

        return self::SUCCESS;
    }
}
