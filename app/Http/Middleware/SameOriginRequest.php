<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SameOriginRequest
{
    public function handle(Request $request, Closure $next)
    {
        $referer = $request->headers->get('referer');
        $origin = $request->headers->get('origin');
        $host = $request->getHost();

        foreach ([$origin, $referer] as $value) {
            if ($value && parse_url($value, PHP_URL_HOST) === $host) {
                return $next($request);
            }
        }

        abort(403, 'Request ditolak karena bukan dari origin website yang sama.');
    }
}
