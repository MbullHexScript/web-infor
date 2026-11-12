<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RunBackup extends Command
{
    protected $signature = 'backup:run';

    protected $description = 'Create a local backup metadata record.';

    public function handle(BackupService $backup): int
    {
        $this->line(json_encode($backup->run(), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
