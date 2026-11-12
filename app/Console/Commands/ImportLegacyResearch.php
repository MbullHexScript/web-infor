<?php

namespace App\Console\Commands;

use App\Services\LegacyResearchImporter;
use Illuminate\Console\Command;

class ImportLegacyResearch extends Command
{
    protected $signature = 'research:import-legacy {--type=} {--all} {--dry-run}';

    protected $description = 'Import legacy research content into migration records.';

    public function handle(LegacyResearchImporter $importer): int
    {
        $type = $this->option('all') ? null : ($this->option('type') ?: null);
        $report = $importer->import(is_string($type) ? $type : null, (bool) $this->option('dry-run'));

        $this->info('Legacy research import '.($report['dry_run'] ? 'dry-run' : 'commit').' completed.');
        $this->line('Inspected: '.$report['inspected']);
        $this->line('Created/updated: '.$report['created_or_updated']);

        return self::SUCCESS;
    }
}
