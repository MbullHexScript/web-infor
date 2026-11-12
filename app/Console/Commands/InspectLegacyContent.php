<?php

namespace App\Console\Commands;

use App\Services\LegacyContentImporter;
use Illuminate\Console\Command;

class InspectLegacyContent extends Command
{
    protected $signature = 'content:inspect-legacy';

    protected $description = 'Inspect legacy information content from the old Informatics website.';

    public function handle(LegacyContentImporter $importer): int
    {
        $report = $importer->inspect();
        $this->info('Legacy source: '.$report['source']);
        $this->line('Items: '.count($report['items']));
        $this->line('Rejected: '.count($report['rejected']));
        $this->table(
            ['Type', 'Title', 'Date', 'Relevance', 'Target'],
            collect($report['items'])->map(fn (array $item): array => [
                $item['type'],
                $item['title'],
                $item['date'] ?? '-',
                $item['relevance_status'],
                $item['target_url'] ?? '-',
            ])->all(),
        );

        return self::SUCCESS;
    }
}
