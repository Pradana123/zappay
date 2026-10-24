<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WhitelistedIP;

class WhitelistIp
{
    /**
     * Optional middleware untuk membatasi akses berdasarkan tabel whitelisted_ips.
     * Tidak dipasang global agar admin tidak terkunci saat pertama instalasi.
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        $allowed = WhitelistedIP::where('ip_address', $clientIp)->exists();

        if (! $allowed) {
            abort(403, 'IP address tidak diizinkan.');
        }

        return $next($request);
    }
}
