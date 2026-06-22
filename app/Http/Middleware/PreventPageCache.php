<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventPageCache
{
    /** @var list<string> */
    private array $protectedPrefixes = [
        'super-admin',
        'admin',
        'client',
        'producteur',
        'distributeur',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() || $this->isProtectedPath($request)) {
            return $this->withNoCacheHeaders($response);
        }

        return $response;
    }

    private function isProtectedPath(Request $request): bool
    {
        foreach ($this->protectedPrefixes as $prefix) {
            if ($request->is($prefix) || $request->is($prefix.'/*')) {
                return true;
            }
        }

        return false;
    }

    private function withNoCacheHeaders(Response $response): Response
    {
        return $response->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }
}
