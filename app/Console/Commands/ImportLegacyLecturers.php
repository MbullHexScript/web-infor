<?php

namespace App\Console\Commands;

use App\Services\LegacyLecturerImporter;
use Illuminate\Console\Command;

class ImportLegacyLecturers extends Command
{
    protected $signature = 'lecturers:import-legacy {--dry-run} {--no-download-photos}';

    protected $description = 'Import legacy lecturer inventory and optionally download photos.';

    public function handle(LegacyLecturerImporter $importer): int
    {
        $report = $importer->import((bool) $this->option('dry-run'), ! (bool) $this->option('no-download-photos'));
        $this->info('Legacy lecturer import '.$report['mode'].' completed.');
        $this->line('Created: '.$report['created']);
        $this->line('Updated: '.$report['updated']);
        $this->line('Photos: '.$report['photos_imported']);
        $this->line('Report: '.storage_path('logs/imports/lecturers'));

        return self::SUCCESS;
    }
}
