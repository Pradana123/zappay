<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Http\Controllers\ApiCheckController;
use App\Http\Controllers\digiFlazzController as Digi;
use App\Http\Controllers\VipResellerController;
use App\Http\Controllers\BangjeffController;
use App\Http\Controllers\SmileOneController;
use App\Http\Controllers\ApiGamesController;
use App\Http\Controllers\UniPinController;
use App\Http\Controllers\MengtopupController;
use Illuminate\Support\Facades\Http;

class DigiflazzApiController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Digiflazz seller request received', ['ref_id' => $request->ref_id, 'pulsa_code' => $request->pulsa_code]);
        $response = [
            'data' => [
                'ref_id' => $request->ref_id,
                'code' => $request->pulsa_code,
                'hp' => $request->hp,
                'balance' => '',
                'sn' => '',
            ],
        ];


        $allowedIps = array_filter(array_map('trim', explode(',', env('DIGIFLAZZ_SELLER_ALLOWED_IPS', '52.74.250.133'))));
        if (! in_array($request->ip(), $allowedIps, true)) {
            Log::warning('Unauthorized Digiflazz seller IP', ['ip' => $request->ip()]);
            return response()->json(['status' => false], 403);
        }

        if (!$request->username || !$request->commands || !$request->ref_id || !$request->hp || !$request->pulsa_code || !$request->sign) {
            Log::error('Missing required parameters', $request->all());
            $response['data']['status'] = '2';
            $response['data']['price'] = '0';
            $response['data']['message'] = 'Terdapat parameter yang kosong';
            $response['data']['tr_id'] = Str::random(6);
            $response['data']['rc'] = '07';

            return response()->json($response);
        }

        $serverSign = md5(ENV("DIGI_SELLER_USERNAME") . ENV("DIGI_SELLER_KEY") . $request->ref_id);
        if (! hash_equals((string) $serverSign, (string) $request->sign)) {
            $response['data']['status'] = '2';
            $response['data']['price'] = '0';
            $response['data']['message'] = 'Sign tidak valid';
            $response['data']['tr_id'] = Str::random(6);
            $response['data']['rc'] = '204';

            return response()->json($response);
        }

        $layanan = Layanan::where('id', $request->pulsa_code)
            ->where('status', 'available')
            ->first();

        if (!$layanan) {
            Log::error('Service not found for pulsa_code: ' . $request->pulsa_code);
            $response['data']['status'] = '2';
            $response['data']['price'] = '0';
            $response['data']['message'] = 'Product tidak valid';
            $response['data']['tr_id'] = Str::random(6);
            $response['data']['rc'] = '20';

            return response()->json($response);
        }

        $kategori = Kategori::where('id', $layanan->kategori_id)->first();
        Log::info('Category found:', $kategori ? $kategori->toArray() : []);

        $pembelian = Pembelian::where('ref_id', $request->ref_id)->first();
        Log::info('Existing purchase check', ['found' => (bool) $pembelian, 'ref_id' => $request->ref_id]);
        //   return $pembelian;
        //jika pembelian sudah ada return status
        if ($pembelian) {
            Log::info('Returning existing purchase status:', ['status' => $pembelian->status]);
            if ($pembelian->status == "Pending" || $pembelian->status == "Processing" || $pembelian->status == "Error") {
                $response['data']['message'] = "PENDING";
                $response['data']['status'] = "0";
                $response['data']['rc'] = "39";
            } else if ($pembelian->status == "Sukses") {
                $response['data']['message'] = "Sukses";
                $response['data']['status'] = "1";
                $response['data']['rc'] = "00";
            } else {
                $response['data']['status'] = "2";
                $response['data']['rc'] = "06";
                $response['data']['message'] = "GAGAL";
            }
            $response['data']['price'] = $pembelian->harga;
            $response['data']['tr_id'] = $pembelian->order_id;

            return response()->json($response);
        }

        $target = $request->hp;
        $zone = '';

        if (preg_match('/mobile\slegend/i', $kategori->nama)) {
            if (strlen($request->hp) == 13) {
                $target = substr($request->hp, 0, 9);
                $zone = substr($request->hp, 9, 4);
            } elseif (strlen($request->hp) == 15) {
                $target = substr($request->hp, 0, 10);
                $zone = substr($request->hp, 10, 5);
            } elseif (strlen($request->hp) == 10) {
                $target = substr($request->hp, 0, 6);
                $zone = substr($request->hp, 6, 4);
            } elseif (strlen($request->hp) == 12) {
                $target = substr($request->hp, 0, 8);
                $zone = substr($request->hp, 8, 4);
            } elseif (strlen($request->hp) == 11) {
                $target = substr($request->hp, 0, 7);
                $zone = substr($request->hp, 7, 4);
            }
            Log::info(json_encode(['target' => $target, 'zone' => $zone]));
        }

        $provider_order_id = null;
        $order = [];
        if ($layanan->provider == 'digiflazz') {
            $digi = new Digi();
            $order = $digi->order($request->hp, '', $layanan->provider_id, $request->ref_id);
            $provider_order_id = $request->ref_id;
            Log::info('DigiFlazz order response:', $order);
            if ($order['data']['status'] == 'Pending' || $order['data']['status'] == 'Sukses') {
                $order['status'] = true;
            } else {
                $order['status'] = false;
            }
        } elseif ($layanan->provider == 'apigames') {
            $provider_order_id = $request->ref_id;
            $apigames = new ApiGamesController();
            $order = $apigames->order($target, $zone, $layanan->provider_id, $provider_order_id);
            Log::info('APIGames order response:', $order);
            if ($order['data']['status'] == 'Sukses') {
                $order['transactionId'] = $provider_order_id;
                $order['status'] = true;
            } else {
                $order['status'] = false;
            }
        } elseif ($layanan->provider == 'meng') {
            $meng = new MengtopupController();
            $order = $meng->order($target, $zone, $layanan->provider_id);
            Log::info('MengTopup order response:', $order);
            if ($order['status']) {
                $order['status'] = true;
                $provider_order_id = $order['data']['id'];
            } else {
                $order['status'] = false;
            }
        } elseif ($layanan->provider == 'manual') {
            $order['status'] = true;
            $provider_order_id = rand(1, 100000);
            Log::info('Manual order created:', ['provider_order_id' => $provider_order_id]);
        } else {
            //kuntul
            Log::warning('Unknown provider:', ['provider' => $layanan->provider]);
        }

        Log::info(json_encode($order));

        $rand = 'PK' . rand();
        $pembelian = new Pembelian();
        $pembelian->username = 'digiflazz';
        $pembelian->order_id = $rand;
        $pembelian->user_id = $target;
        $pembelian->zone = $zone;
        $pembelian->nickname = $request->hp;
        $pembelian->layanan = $layanan->layanan;
        $pembelian->harga = $layanan->harga_gold;
        $pembelian->profit = '0';
        $pembelian->status = $order['status'] ? 'Pending' : 'Batal';
        $pembelian->provider_order_id = $provider_order_id ? $provider_order_id : '';
        $pembelian->log = json_encode($order);
        $pembelian->is_digi = 1;
        $pembelian->ref_id = $request->ref_id;
        $pembelian->tipe_transaksi = 'game';

        $pembelian->save();
        Log::info('Purchase record saved:', $pembelian->toArray());

        $kontrol = json_encode($pembelian);
        $pembelianArray = json_decode($kontrol, true); // Decode the outer JSON to an associative array

        $logArray = json_decode($pembelianArray['log'], true); // Decode the nested JSON to an associative array

        $pesan = "Layanan : $layanan->layanan\n" . 'ID : ' . $target . (isset($zone) ? '(' . $zone . ')' : '') . "\n" . 'Invoice : ' . env('APP_URL') . "/pembelian/invoice/{$pembelianArray['order_id']}\n" . 'Log Status: ' . ($logArray['status'] ?? 'Not available') . "\n" . "Log : $kontrol\n"; // Added semicolon at the end

        $req1 = $this->msg('asdad', $pesan);

        if ($order['status']) {
            $pembayaran = new Pembayaran();
            $pembayaran->order_id = $rand;
            $pembayaran->harga = $layanan->harga_gold;
            $pembayaran->no_pembayaran = 'Saldo';
            $pembayaran->no_pembeli = '6289675314257';
            $pembayaran->status = 'Lunas';
            $pembayaran->metode = 'Saldo';
            $pembayaran->save();
            Log::info('Payment record saved:', $pembayaran->toArray());

            $response['data']['status'] = '0';
            $response['data']['rc'] = '39';
            $response['data']['message'] = 'Pending';
            $response['data']['price'] = "$layanan->harga_gold";
            $response['data']['tr_id'] = $rand;
        } else {
            $response['data']['status'] = '2';
            $response['data']['rc'] = '14';
            $response['data']['message'] = 'Gagal';
            $response['data']['price'] = '0';
            $response['data']['tr_id'] = $rand;
            Log::error('Order failed:', ['response' => $response]);
            return response()->json($response);
        }

        return response()->json($response);
    }

    public function testing()
    {
        abort(404);
    }

    public function connect($url, $data, $header)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.digiflazz.com' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $chresult = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($chresult, true);
        return $json_result;
    }

    public function msg($nomor, $msg)
    {
        $api = \DB::table('setting_webs')->where('id', 1)->first();
        $apiUrl = 'https://api.fonnte.com/send';
        $token = $api->wa_key;
    
        $postData = [
            'target' => $nomor,
            'message' => $msg,
        ];
    
        $headers = [
            'Authorization: ' . $token,
        ];
    
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => $headers,
        ]);
    
        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
        curl_close($curl);
    
        if ($statusCode === 200) {
            return [
                'success' => true,
                'message' => 'Message sent successfully',
                'response' => $response,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to send message',
                'response' => $response,
            ];
        }
    }
}
