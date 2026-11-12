<?php

namespace App\Console\Commands;

use App\Services\LegacyResearchImporter;
use Illuminate\Console\Command;

class InspectLegacyResearch extends Command
{
    protected $signature = 'research:inspect-legacy';

    protected $description = 'Inspect legacy research and innovation content.';

    public function handle(LegacyResearchImporter $importer): int
    {
        $report = $importer->inspect();
        $this->info('Legacy research items: '.count($report['items']));
        $this->line('Rejected: '.count($report['rejected']));
        $this->table(['Type', 'Title', 'Year', 'Verification', 'Target'], collect($report['items'])->map(fn (array $item): array => [
            $item['type'],
            $item['title'],
            $item['year'] ?? '-',
            $item['verification_status'],
            $item['target_url'],
        ])->all());

        return self::SUCCESS;
    }
}
