<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HealthCheckService
{
    /**
     * @return array<string, mixed>
     */
    public function check(): array
    {
        $database = true;
        try {
            DB::select('select 1');
        } catch (\Throwable) {
            $database = false;
        }

        $cache = true;
        try {
            Cache::put('health-check', 'ok', 5);
            $cache = Cache::get('health-check') === 'ok';
        } catch (\Throwable) {
            $cache = false;
        }

        return [
            'status' => $database && $cache && File::isWritable(storage_path()) ? 'ok' : 'degraded',
            'database' => $database,
            'cache' => $cache,
            'storage_writable' => File::isWritable(storage_path()),
            'checked_at' => now()->toIso8601String(),
        ];
    }
}
