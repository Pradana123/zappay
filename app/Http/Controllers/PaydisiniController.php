<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;

class PaydisiniController extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = \DB::table('setting_webs')->where('id', 1)->first();
    }
    
    private $apiUrl = 'https://paydisini.co.id/api/';
    // private $apiKey = $this->api->paydisini_apikey;

    /**
     * Membuat Transaksi Baru
     */
    public function createTransaction(Request $request)
    {
        $uniqueCode = $request->input('unique_code', 'postman123unikcode');
        $service = $request->input('service', 17);
        $amount = $request->input('amount');
        $note = $request->input('note', 'Pembayaran pertama');
        $validTime = $request->input('valid_time', 1800);
        $ewalletPhone = $request->input('ewallet_phone', null);
        $typeFee = $request->input('type_fee', 1);

        if (empty($amount) || $amount <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah tidak bisa kosong atau kurang dari 0'
            ], 400);
        }

        $signature = md5($this->api->paydisini_apikey . $uniqueCode . $service . $amount . $validTime . 'NewTransaction');

        $formData = [
            'key' => $this->api->paydisini_apikey,
            'merchant_id' => '1537',
            'request' => 'new',
            'unique_code' => $uniqueCode,
            'service' => $service,
            'amount' => $amount,
            'note' => $note,
            'valid_time' => $validTime,
            'ewallet_phone' => $ewalletPhone,
            'type_fee' => $typeFee,
            'signature' => $signature
        ];

        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['data'])) {
                    $data = $responseData['data'];
                    $no_pembayaran = $data['qrcode_url'] ?? 
                                     $data['checkout_url'] ?? 
                                     $data['checkout_url_beta'] ?? 
                                     $data['checkout_url_v2'] ?? 
                                     $data['checkout_url_v3'] ?? 
                                     '';

                    if (empty($no_pembayaran)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Respons tidak berisi URL pembayaran yang valid',
                            'data' => $responseData
                        ]);
                    }

                    $amount = $data['amount'] ?? $amount;
                    $reference = $data['unique_code'] ?? '';

                    return response()->json([
                        'status' => 'success',
                        'data' => [
                            'no_pembayaran' => $no_pembayaran,
                            'amount' => $amount,
                            'reference' => $reference
                        ]
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Respons tidak berisi data yang diharapkan',
                        'data' => $responseData
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat transaksi baru',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memeriksa Status Transaksi
     */
    public function checkTransactionStatus(Request $request)
    {
        $uniqueCode = $request->input('unique_code', 'postman123unikcode');
        $signature = md5($this->api->paydisini_apikey . $uniqueCode . 'StatusTransaction');

        $formData = [
            'key' => $this->api->paydisini_apikey,
            'request' => 'status',
            'unique_code' => $uniqueCode,
            'signature' => $signature
        ];

        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memeriksa status transaksi',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Membatalkan Transaksi
     */
    public function cancelTransaction(Request $request)
    {
        $uniqueCode = $request->input('unique_code', 'postman123unikcode');

        $signature = md5($this->api->paydisini_apikey . $uniqueCode . 'CancelTransaction');

        $formData = [
            'key' => $this->api->paydisini_apikey,
            'request' => 'cancel',
            'unique_code' => $uniqueCode,
            'signature' => $signature
        ];


        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membatalkan transaksi',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Melihat Payment Channel
     */
    public function getPaymentChannel()
    {
        $signature = md5($this->api->paydisini_apikey . 'PaymentChannel');
        $formData = [
            'key' => $this->api->paydisini_apikey,
            'request' => 'payment_channel',
            'signature' => $signature
        ];
        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal melihat payment channel',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Melihat Panduan Pembayaran
     */
    public function getPaymentGuide(Request $request)
    {
        $service = $request->input('service', 11);
        $signature = md5($this->api->paydisini_apikey . $service . 'PaymentGuide');

        $formData = [
            'key' => $this->api->paydisini_apikey,
            'request' => 'payment_guide',
            'service' => $service,
            'signature' => $signature
        ];

        try {
            $response = Http::asForm()->post($this->apiUrl, $formData);

            Log::info('API Response', ['response' => $response->body()]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal melihat panduan pembayaran',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
