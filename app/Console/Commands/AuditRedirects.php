<?php

namespace App\Console\Commands;

use App\Services\RedirectManager;
use Illuminate\Console\Command;

class AuditRedirects extends Command
{
    protected $signature = 'redirects:audit';

    protected $description = 'Audit redirect duplicates and loops.';

    public function handle(RedirectManager $redirects): int
    {
        $report = $redirects->audit();
        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return $report['warnings'] === 0 ? self::SUCCESS : self::FAILURE;
    }
}
