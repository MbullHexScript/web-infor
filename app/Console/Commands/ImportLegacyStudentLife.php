<?php

namespace App\Console\Commands;

use App\Services\LegacyStudentLifeImporter;
use Illuminate\Console\Command;

class ImportLegacyStudentLife extends Command
{
    protected $signature = 'student-life:import-legacy
        {--type=all : achievements, facilities, alumni, hmps, admissions, or all}
        {--dry-run : Inspect without writing migration records}';

    protected $description = 'Import legacy student life inventory into pending migration records.';

    public function handle(LegacyStudentLifeImporter $importer): int
    {
        $type = (string) $this->option('type');
        $allowed = ['achievements', 'facilities', 'alumni', 'hmps', 'admissions', 'all'];

        if (! in_array($type, $allowed, true)) {
            $this->error('Invalid type. Use: '.implode(', ', $allowed));

            return self::FAILURE;
        }

        $report = $importer->import($type, (bool) $this->option('dry-run'));

        $this->info(($report['dry_run'] ? 'Dry-run' : 'Import').' complete.');
        $this->line('Scanned: '.$report['scanned']);
        $this->line('Created or updated: '.$report['created_or_updated']);
        $this->line('Duplicates: '.$report['duplicates']);
        $this->line('Rejected: '.$report['rejected']);

        return self::SUCCESS;
    }
}
