<?php

namespace App\Console\Commands;

use App\Services\QualityAuditService;
use Illuminate\Console\Command;

class AuditDatabasePerformance extends Command
{
    protected $signature = 'db:audit-performance';

    protected $description = 'Audit database performance signals without mutating data.';

    public function handle(QualityAuditService $audit): int
    {
        $report = $audit->databasePerformanceAudit();
        $audit->writeJson('database-performance-audit', $report);
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
