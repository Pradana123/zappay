<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\Deposit;
use App\Models\User;
use App\Http\Controllers\digiFlazzController;
use App\Http\Controllers\provider\vipreseller\VipResellerController;
use App\Http\Controllers\provider\apigames\ApiGamesController;
use App\Http\Controllers\provider\bangjeff\BangJeffController;

class PaydisiniCallbackController extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = \DB::table('setting_webs')->where('id', 1)->first();
    }
    
    // private $apiKey = $this->api->tokopay_merchant_id; // Ganti dengan API Key 
    
    public function callbackTransaction(Request $request)
    {
        $allowedIps = array_filter(array_map('trim', explode(',', env('PAYDISINI_CALLBACK_ALLOWED_IPS', '195.88.211.145'))));
        if (! in_array($request->ip(), $allowedIps, true)) {
            return response()->json(['success' => false, 'message' => 'Invalid IP address'], 403);
        }
        //Yang dibutuhkan
        $key = $request->input('key');
        $payId = $request->input('pay_id');
        $uniqueCode = $request->input('unique_code');
        $status = $request->input('status');
        $signature = $request->input('signature');
        
        if (! hash_equals((string) $this->api->paydisini_apikey, (string) $key)) {
            return response()->json(['success' => false, 'message' => 'Invalid API Key'], 400);
        }

        $expectedSignature = md5($this->api->paydisini_apikey . $uniqueCode . 'CallbackStatus');
        if (! hash_equals((string) $expectedSignature, (string) $signature)) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        }

        $transaction = Pembayaran::where('order_id', $uniqueCode)
            ->where('status', 'Belum Lunas')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        if ($status === 'Success') {
            $transaction->update(['status' => 'Lunas']);
            $pembelian = Pembelian::where('order_id', $uniqueCode)->first();

            if ($pembelian) {
                $pembelian->update(['status' => 'Sukses']);
                $this->handleSuccess($pembelian);
            } else {
                $deposit = Deposit::where('order_id', $uniqueCode)->first();
                if ($deposit) {
                    $user = User::where('username', $deposit->username)->first();
                    if ($user) {
                        $user->update(['balance' => $user->balance + $deposit->jumlah]);
                        $deposit->update(['status' => 'Success']);
                    }
                }
            }
            return response()->json(['success' => true]);
        } elseif ($status === 'Canceled') {
            $transaction->update(['status' => 'Gagal']);
            $pembelian = Pembelian::where('order_id', $uniqueCode)->first();
            if ($pembelian) {
                $pembelian->update(['status' => 'Gagal']);
            } else {
                $deposit = Deposit::where('order_id', $uniqueCode)->first();
                if ($deposit) {
                    $deposit->update(['status' => 'Gagal']);
                }
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }
    }

    private function handleSuccess($pembelian)
    {
        $layanan = Layanan::where('layanan', $pembelian->layanan)->first();
        if ($layanan) {
            $provider = $layanan->provider;
            $user_id = $pembelian->user_id;
            $zone = $pembelian->zone;
            $provider_id = $layanan->provider_id;

            $order = ['data' => ['status' => false], 'message' => 'Provider tidak dikenali.'];

            if ($provider === "digiflazz") {
                $random_part = mt_rand(100000, 999999);
                $provider_order_id = 'REF-CIHUY' . $random_part;
                $digiFlazz = new digiFlazzController;
                $order = $digiFlazz->order($user_id, $zone, $provider_id, $provider_order_id);

                if ($order['data']['status'] === "Pending" || $order['data']['status'] === "Sukses") {
                    $order['data']['status'] = true;
                    $order['transactionId'] = $provider_order_id;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "bangjeff") {
                $bangjeff = new BangJeffController;
                
                $ttlpembelian = [
                    [
                        "name" => "id",
                        "value" => $user_id
                    ]
                ];

                if ($zone !== null) {
                    $ttlpembelian[] = [
                        "name" => "server",
                        "value" => $zone
                    ];
                }
                
                $order = $bangjeff->order($provider_id, $pembelian->order_id, 1, $ttlpembelian);

                if (!$order['error']) {
                    $order['transactionId'] = $order['data']['invoiceNumber'];
                    $order['data']['status'] = true;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif (in_array($provider, ["vip", "vip-game", "vip-pulsa"], true)) {
                $vip = new VipResellerController;
                $order = $vip->order($user_id, $zone, $provider_id);

                if (($order['result'] ?? false) === true) {
                    $order['data']['status'] = true;
                    $order['transactionId'] = $order['data']['trxid'] ?? 0;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "apigames") {
                $provider_order_id = rand(1, 10000);
                $apigames = new ApiGamesController;
                $order = $apigames->order($user_id, $zone, $provider_id, $provider_order_id);

                if (($order['data']['status'] ?? null) == "Sukses") {
                    $order['transactionId'] = $provider_order_id;
                    $order['data']['status'] = true;
                } else {
                    $order['data']['status'] = false;
                }
            } elseif ($provider === "joki" || $provider === "jokigendong") {
                $provider_order_id = '';
                $order['data']['status'] = true;
            }

            if ($order['data']['status']) {
                if ($pembelian->tipe_transaksi !== 'joki') {
                    $pembelian->update([
                        'provider_order_id' => isset($order['transactionId']) ? $order['transactionId'] : 0,
                        'status' => 'Proses',
                        'log' => json_encode($order)
                    ]);
                    // $this->msg($invoice->no_pembeli, $pesanPembeli); 
                } else {
                    $pembelian->update([
                        'provider_order_id' => '', 
                        'status' => 'Proses',
                        'log' => json_encode($order)
                    ]);
                    // $this->msg($invoice->no_pembeli, $pesanJoki); 
                }
            } else {
                if ($pembelian->tipe_transaksi !== 'joki') {
                    $pembelian->update([
                        'status' => 'Batal',
                        'log' => json_encode($order)
                    ]);
                }
                
            }
        } else {
            Log::error('Service Tiidak Ditemukan', ['layanan' => $pembelian->layanan]);
        }
    }
}
