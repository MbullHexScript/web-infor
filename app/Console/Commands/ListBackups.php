<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class ListBackups extends Command
{
    protected $signature = 'backup:list';

    protected $description = 'List backup records.';

    public function handle(BackupService $backup): int
    {
        $this->table(['ID', 'Type', 'Status', 'Path', 'Size'], collect($backup->list())->map(fn ($row) => [$row['id'], $row['backup_type'], $row['status'], $row['path'], $row['size']])->all());

        return self::SUCCESS;
    }
}
