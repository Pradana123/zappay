<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Berita;
use App\Models\WhitelistedIP;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    private function getWhitelistedIps()
    {
        return WhitelistedIP::pluck('ip_address')->toArray();
    }

    public function create()
    {
        return view('components.admin.adminlogin', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (filter_var(env('ADMIN_IP_WHITELIST_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) {
            $whitelistedIps = $this->getWhitelistedIps();
            if (! in_array($request->ip(), $whitelistedIps, true)) {
                return redirect()->route('adminlogin')->withErrors(['error' => 'Access denied from your IP.']);
            }
        }

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'Admin') {
                return redirect()->intended(route('dashboard'));
            } else {
                Auth::logout();
                return redirect()->route('adminlogin')->withErrors(['error' => 'Username / password mismatch']);
            }
        }

        throw ValidationException::withMessages([
            'error' => ['Username / password mismatch'],
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
