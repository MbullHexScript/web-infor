<?php

namespace App\Console\Commands;

use App\Services\LegacyStudentLifeImporter;
use Illuminate\Console\Command;

class InspectLegacyStudentLife extends Command
{
    protected $signature = 'student-life:inspect-legacy';

    protected $description = 'Inspect legacy student life, facilities, admissions, alumni, and HMPS content.';

    public function handle(LegacyStudentLifeImporter $importer): int
    {
        $report = $importer->inspect();

        $this->info('Legacy student-life sources inspected.');
        $this->table(
            ['Type', 'Title', 'Source URL', 'Target URL', 'Verification'],
            collect($report['items'])->map(fn (array $item) => [
                $item['type'],
                $item['title'],
                $item['source_url'],
                $item['target_url'],
                $item['verification_status'],
            ])->all(),
        );
        $this->warn('Rejected: '.count($report['rejected']).' item(s).');

        return self::SUCCESS;
    }
}
