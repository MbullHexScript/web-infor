<?php

namespace App\Console\Commands;

use App\Services\CurriculumWorkbookImporter;
use Illuminate\Console\Command;

class ValidateCurriculum extends Command
{
    protected $signature = 'curriculum:validate';

    protected $description = 'Validate imported curriculum data in the database.';

    public function handle(CurriculumWorkbookImporter $importer): int
    {
        $result = $importer->validateDatabase();

        foreach ($result['warnings'] as $warning) {
            $this->warn($warning);
        }

        foreach ($result['errors'] as $error) {
            $this->error($error);
        }

        if (! $result['warnings'] && ! $result['errors']) {
            $this->info('Imported curriculum data looks valid.');
        }

        return $result['errors'] ? self::FAILURE : self::SUCCESS;
    }
}
