<?php

namespace App\Console\Commands;

use App\Services\CurriculumWorkbookImporter;
use Illuminate\Console\Command;

class InspectCurriculumWorkbook extends Command
{
    protected $signature = 'curriculum:inspect {path? : Path to the XLSX workbook}';

    protected $description = 'Inspect the OBE curriculum workbook and print validation summary.';

    public function handle(CurriculumWorkbookImporter $importer): int
    {
        $path = $this->argument('path') ?: config('academic.curriculum_import_path');
        $payload = $importer->inspect($path);
        $summary = $payload['summary'];

        $this->info('Workbook inspected: '.$path);
        $this->line('Checksum: '.$summary['sha256']);
        $this->line('Sheets: '.$summary['sheet_count']);
        $this->line('Courses: '.$summary['course_count']);
        $this->line('Student total credits: '.$summary['student_total_credits']);
        $this->line('Specializations: '.$summary['specializations_count']);
        $this->line('Graduate profiles: '.$summary['profiles_count']);
        $this->line('Warnings: '.count($summary['warnings']));
        $this->line('Errors: '.count($summary['errors']));

        $this->table(
            ['Semester', 'Courses', 'Mandatory', 'Elective Offered', 'Student Package'],
            collect($summary['semester_totals'])->map(fn (array $item, string|int $semester): array => [
                $semester,
                $item['course_count'],
                $item['mandatory_credits'],
                $item['elective_credits'],
                $item['student_package_credits'],
            ])->values()->all()
        );

        foreach ($summary['warnings'] as $warning) {
            $this->warn($warning);
        }

        foreach ($summary['errors'] as $error) {
            $this->error($error);
        }

        return $summary['errors'] ? self::FAILURE : self::SUCCESS;
    }
}
