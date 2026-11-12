<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $devSources = $this->devServerSources();
        $scriptSources = "script-src 'self'";
        $connectSources = "connect-src 'self'";

        if ($devSources !== []) {
            $scriptSources .= " 'unsafe-inline' ".implode(' ', $devSources['http']);
            $connectSources .= ' '.implode(' ', [...$devSources['http'], ...$devSources['ws']]);
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        if (str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Cache-Control', 'no-store, private, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        if ($request->is('admin*') || app()->environment('staging')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        $directives = [
            "default-src 'self'",
            $scriptSources,
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data:",
            $connectSources,
            "frame-src 'self' https://www.google.com https://maps.google.com https://www.youtube-nocookie.com https://player.vimeo.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];
        if (app()->isProduction()) {
            $directives[] = 'upgrade-insecure-requests';
        }
        $response->headers->set('Content-Security-Policy', implode('; ', $directives));

        if (app()->isProduction()) {
            $hsts = 'max-age=31536000; includeSubDomains';
            if (filter_var(env('HSTS_PRELOAD', false), FILTER_VALIDATE_BOOL)) {
                $hsts .= '; preload';
            }
            $response->headers->set('Strict-Transport-Security', $hsts);
        }

        return $response;
    }

    /**
     * @return array{http: list<string>, ws: list<string>}|array{}
     */
    private function devServerSources(): array
    {
        if (! app()->environment('local')) {
            return [];
        }

        $hotFile = public_path('hot');
        $origin = is_file($hotFile) ? trim((string) file_get_contents($hotFile)) : '';

        if (filter_var($origin, FILTER_VALIDATE_URL) === false) {
            $origin = 'http://127.0.0.1:5173';
        }

        $parts = parse_url($origin);
        $host = $parts['host'] ?? '127.0.0.1';
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $scheme = ($parts['scheme'] ?? 'http') === 'https' ? 'https' : 'http';
        $wsScheme = $scheme === 'https' ? 'wss' : 'ws';

        return [
            'http' => [sprintf('%s://%s%s', $scheme, $host, $port)],
            'ws' => [sprintf('%s://%s%s', $wsScheme, $host, $port)],
        ];
    }
}
