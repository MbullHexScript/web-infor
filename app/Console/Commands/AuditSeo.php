<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AuditSeo extends Command
{
    protected $signature = 'seo:audit {--json : Output JSON only}';

    protected $description = 'Audit SEO metadata coverage.';

    public function handle(): int
    {
        $report = [
            'missing_meta_title' => DB::table('seo_metadata')->whereNull('meta_title_id')->whereNull('meta_title_en')->count(),
            'missing_meta_description' => DB::table('seo_metadata')->whereNull('meta_description_id')->whereNull('meta_description_en')->count(),
            'images_without_alt' => DB::table('media')->where('mime_type', 'like', 'image/%')->whereNull('alt_text_id')->whereNull('alt_id')->whereNull('deleted_at')->count(),
            'generated_at' => now()->toIso8601String(),
        ];
        File::ensureDirectoryExists(storage_path('logs/audits'));
        File::put(storage_path('logs/audits/seo-audit.json'), json_encode($report, JSON_PRETTY_PRINT));

        $this->line(json_encode($report, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
