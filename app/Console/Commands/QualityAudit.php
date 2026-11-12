<?php

namespace App\Console\Commands;

use App\Services\QualityAuditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class QualityAudit extends Command
{
    protected $signature = 'quality:audit {--write-inventory : Write master content inventory files}';

    protected $description = 'Run non-destructive quality audits and write a summary.';

    public function handle(QualityAuditService $audit): int
    {
        $report = $audit->summary();
        $path = $audit->writeJson('quality-audit', $report);

        if ($this->option('write-inventory')) {
            $this->writeInventory($audit->masterInventoryRows());
        }

        $this->info("Quality audit written to {$path}");
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }

    private function writeInventory(array $rows): void
    {
        File::ensureDirectoryExists(base_path('docs/content'));
        $headers = [
            'ID',
            'Modul',
            'Jenis konten',
            'Judul',
            'URL lama',
            'URL baru ID',
            'URL baru EN',
            'Status migrasi',
            'Status verifikasi',
            'Status terjemahan',
            'Status media',
            'Status SEO',
            'Status redirect',
            'Penanggung jawab verifikasi',
            'Catatan',
            'Tanggal terakhir diperiksa',
        ];

        $csv = implode(',', $headers).PHP_EOL;
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"', $row)).PHP_EOL;
        }
        File::put(base_path('docs/content/master-content-inventory.csv'), $csv);

        $markdown = '# Master Content Inventory'.PHP_EOL.PHP_EOL;
        $markdown .= 'Generated: '.now()->toDateTimeString().PHP_EOL.PHP_EOL;
        $markdown .= '| '.implode(' | ', $headers).' |'.PHP_EOL;
        $markdown .= '| '.implode(' | ', array_fill(0, count($headers), '---')).' |'.PHP_EOL;
        foreach ($rows as $row) {
            $markdown .= '| '.implode(' | ', array_map(fn ($value) => str_replace('|', '/', (string) $value), $row)).' |'.PHP_EOL;
        }
        File::put(base_path('docs/content/master-content-inventory.md'), $markdown);
    }
}
