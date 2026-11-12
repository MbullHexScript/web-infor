<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class VerifyBackup extends Command
{
    protected $signature = 'backup:verify {id?}';

    protected $description = 'Verify latest or selected backup checksum.';

    public function handle(BackupService $backup): int
    {
        $report = $backup->verify($this->argument('id') ? (int) $this->argument('id') : null);
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return $report['ok'] ? self::SUCCESS : self::FAILURE;
    }
}
