<?php

namespace App\Console\Commands;

use App\Services\SintesaIntegrationService;
use Illuminate\Console\Command;
use Throwable;

class SyncSintesa extends Command
{
    protected $signature = 'sintesa:sync
        {--dry-run : Read and validate SINTESA data without changing the website database}
        {--only=all : Synchronize all, catalog, or lecturers}';

    protected $description = 'Synchronize authoritative academic data from SINTESA.';

    public function handle(SintesaIntegrationService $sintesa): int
    {
        try {
            $report = $sintesa->sync((bool) $this->option('dry-run'), (string) $this->option('only'));
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Sinkronisasi SINTESA gagal: '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
