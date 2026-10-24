<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticateApi
{
    /**
     * Whitelist IP addresses from the database or default fallback.
     *
     * @var array
     */
    protected $ipWhitelist = [];

    /**
     * Whether Bearer token is required.
     *
     * @var bool
     */
    protected $requireBearerToken = true; 

    public function __construct()
    {
        // Ambil semua IP dari tabel whitelist_ip_api
        try {
            $results = DB::table('whitelist_ip_api')->pluck('ip_address')->toArray();
            $this->ipWhitelist = $results ?: ['127.0.0.1']; // Fallback jika kosong
        } catch (\Exception $e) {
            Log::error('Failed to fetch whitelist IPs: ' . $e->getMessage());
            $this->ipWhitelist = ['127.0.0.1']; // Default fallback
        }

        // Ambil setting require bearer dari .env
        $this->requireBearerToken = filter_var(env('API_REQUIRE_BEARER', true), FILTER_VALIDATE_BOOLEAN);
    }
    
    public function handle(Request $request, Closure $next)
    {
        if (!$request->expectsJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        // Bypass IP Check kalau environment local
        if (app()->environment('local')) {
            return $this->checkBearer($request, $next);
        }

        // IP Address Validasi
        $clientIp = $request->ip() ?? '0.0.0.0';

        if (!in_array($clientIp, $this->ipWhitelist)) {
            return response()->json([
                'success' => false,
                'code' => 403,
                'message' => 'Access denied. Your IP ('.$clientIp.') is not authorized.'
            ], 403);
        }

        // lanjut cek bearer token
        return $this->checkBearer($request, $next);
    }

    protected function checkBearer(Request $request, Closure $next)
    {
        if ($this->requireBearerToken) {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Unauthorized. Bearer token is missing.'
                ], 401);
            }

            // Validasi token kalau mau
            // Contoh hardcode valid token
            // if ($token !== env('API_ACCESS_TOKEN')) {
            //     return response()->json([
            //         'success' => false,
            //         'code' => 401,
            //         'message' => 'Unauthorized. Invalid token.'
            //     ], 401);
            // }
        }

        return $next($request);
    }
}
