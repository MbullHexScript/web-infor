<?php

namespace App\Console\Commands;

use App\Services\MediaLibraryService;
use Illuminate\Console\Command;

class AuditMedia extends Command
{
    protected $signature = 'media:audit';

    protected $description = 'Audit media metadata, missing alt text, and orphan usage.';

    public function handle(MediaLibraryService $media): int
    {
        $report = $media->audit();
        $this->info('Media audit complete.');
        $this->line('Total: '.$report['total']);
        $this->line('Orphans: '.$report['orphans']);
        $this->line('Missing alt: '.$report['missing_alt']);

        return self::SUCCESS;
    }
}
