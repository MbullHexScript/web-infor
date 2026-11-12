<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LegacyRedirectMiddleware
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is('admin*') && Schema::hasTable('legacy_redirects')) {
            $path = '/'.ltrim($request->path(), '/');
            $redirect = DB::table('legacy_redirects')
                ->whereNull('deleted_at')
                ->where(fn ($query) => $query->where('old_path', $path)->orWhere('source_path', $path))
                ->where(fn ($query) => $query->where('is_active', true)->orWhereNull('is_active'))
                ->first();

            if ($redirect) {
                DB::table('legacy_redirects')->where('id', $redirect->id)->update([
                    'hit_count' => ((int) $redirect->hit_count) + 1,
                    'last_hit_at' => now(),
                    'updated_at' => now(),
                ]);

                return redirect($redirect->new_path ?: $redirect->target_path, $redirect->redirect_code ?: $redirect->status_code ?: 301);
            }
        }

        return $next($request);
    }
}
