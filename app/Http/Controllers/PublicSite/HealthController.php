<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;

class HealthController extends Controller
{
    public function __invoke(HealthCheckService $health)
    {
        $result = $health->check();

        return response()->json([
            'status' => $result['status'],
            'database' => $result['database'] ? 'ok' : 'failed',
            'cache' => $result['cache'] ? 'ok' : 'failed',
            'storage' => $result['storage_writable'] ? 'ok' : 'failed',
        ], $result['status'] === 'ok' ? 200 : 503);
    }
}
