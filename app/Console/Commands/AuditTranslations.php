<?php

namespace App\Console\Commands;

use App\Services\QualityAuditService;
use Illuminate\Console\Command;

class AuditTranslations extends Command
{
    protected $signature = 'translations:audit {--json : Output JSON only}';

    protected $description = 'Audit English translation completeness.';

    public function handle(QualityAuditService $audit): int
    {
        $report = $audit->translationAudit();
        $audit->writeJson('translations-audit', $report);
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
