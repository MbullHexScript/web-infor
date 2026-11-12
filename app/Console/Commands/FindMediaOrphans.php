<?php

namespace App\Console\Commands;

use App\Services\MediaLibraryService;
use Illuminate\Console\Command;

class FindMediaOrphans extends Command
{
    protected $signature = 'media:find-orphans';

    protected $description = 'List media records without usage references.';

    public function handle(MediaLibraryService $media): int
    {
        $items = $media->audit()['items'];
        $this->table(['ID', 'Path', 'Name'], collect($items)->map(fn ($item) => [$item->id, $item->path, $item->original_name])->all());

        return self::SUCCESS;
    }
}
