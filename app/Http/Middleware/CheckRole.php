<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Batasi area admin hanya untuk user role Admin.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'Admin') {
            return $next($request);
        }

        if (in_array(Auth::user()->role, ['Member', 'Platinum', 'Gold'], true)) {
            return redirect('/id/dashboard');
        }

        abort(403, 'Akses ditolak.');
    }
}
