<?php

namespace App\Console\Commands;

use App\Services\CurriculumWorkbookImporter;
use Illuminate\Console\Command;

class ImportCurriculumWorkbook extends Command
{
    protected $signature = 'curriculum:import
        {path? : Path to the XLSX workbook}
        {--dry-run : Validate and report without writing curriculum data}
        {--update-existing : Update existing records that match curriculum/code keys}';

    protected $description = 'Import OBE curriculum workbook data into academic tables.';

    public function handle(CurriculumWorkbookImporter $importer): int
    {
        $path = $this->argument('path') ?: config('academic.curriculum_import_path');
        $result = $importer->import($path, (bool) $this->option('dry-run'), (bool) $this->option('update-existing'));
        $import = $result['import'];

        $this->info('Import report #'.$import->id.' '.$import->status);
        $this->line('Mode: '.$import->mode);
        $this->line('Rows processed: '.$import->rows_processed);
        $this->line('Created: '.$import->rows_created);
        $this->line('Updated: '.$import->rows_updated);
        $this->line('Skipped: '.$import->rows_skipped);
        $this->line('Warnings: '.$import->warnings_count);
        $this->line('Errors: '.$import->errors_count);
        $this->line('Reports: '.storage_path('logs/imports'));

        return $import->errors_count ? self::FAILURE : self::SUCCESS;
    }
}
