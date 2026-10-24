<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TopupediadashboardController extends Controller
{
    private $api;
    private $url;

    public function __construct()
     {
        $this->api = env('TOPUPEDIA_API_KEY');
        $this->url = 'https://api.topupedia.com';
    }
    public function balance()
    {
        if (empty($this->api)) {
            return view('components.admin.topupedia.ceksaldobj', ['error' => 'TOPUPEDIA_API_KEY belum diatur di file .env.']);
        }

        try {
            $headers = [
                'Authorization' => 'Bearer ' . $this->api,
                'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->timeout(30)->post($this->url . '/api/v3/balance');

            if ($response->successful()) {
                $data = $response->json();
                
                $balance = $data['data']['balance'] ?? null;

                if ($balance !== null) {
                    return view('components.admin.topupedia.ceksaldobj', ['saldo' => $balance]);
                } else {
                    $error = $response->json();
            return response()->json($error, $response->status());
                }
            } else {
                 $error = $response->json();
            return response()->json($error, $response->status());
            }
        } catch (\Exception $e) {
            return view('components.admin.topupedia.ceksaldobj', ['error' => $e->getMessage()]);
        }
    }
    
    public function getProduct()
{
    if (empty($this->api)) {
        return view('components.admin.topupedia.products', ['error' => 'TOPUPEDIA_API_KEY belum diatur di file .env.']);
    }

    try {
        $headers = [
            'Authorization' => 'Bearer ' . $this->api,
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->timeout(30)->post($this->url . '/api/v3/product');

        if ($response->successful()) {
            $data = $response->json();
            return view('components.admin.topupedia.products', ['products' => $data]);
        } else {
            $error = $response->json();
            return view('components.admin.topupedia.products', ['error' => $error]);
        }
    } catch (\Exception $e) {
        return view('components.admin.topupedia.products', ['error' => $e->getMessage()]);
    }
}

}
