<?php

namespace App\Console\Commands;

use App\Services\LegacyLecturerImporter;
use Illuminate\Console\Command;

class InspectLegacyLecturers extends Command
{
    protected $signature = 'lecturers:inspect-legacy';

    protected $description = 'Inspect legacy lecturer inventory from the old Informatics website.';

    public function handle(LegacyLecturerImporter $importer): int
    {
        $report = $importer->inspect();
        $this->info('Legacy lecturers: '.$report['count']);
        $this->line('Photos found: '.$report['photos_found']);
        $this->table(['Name', 'Degree', 'Position'], collect($report['lecturers'])->map(fn (array $lecturer): array => [
            $lecturer['name'],
            $lecturer['degree'],
            $lecturer['position_id'] ?? '-',
        ])->all());

        return self::SUCCESS;
    }
}
