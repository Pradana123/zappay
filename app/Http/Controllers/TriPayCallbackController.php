<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\digiFlazzController;
use App\Http\Controllers\provider\vipreseller\VipResellerController;
use App\Http\Controllers\provider\apigames\ApiGamesController;
use App\Http\Controllers\providers\aoshi\AoshiController;
use App\Http\Controllers\provider\wejistore\WejiStoreController;
use App\Http\Controllers\provider\bangjeff\BangJeffController;
use App\Http\Controllers\provider\alpharamz\AlpharamzController;
use App\Http\Controllers\provider\topupedia\TopupediaController;
use App\Http\Controllers\provider\moogold\MoogoldController;
use Illuminate\Support\Facades\Http;

class TriPayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $api->tripay_private_key);

        if (! hash_equals((string) $signature, (string) $callbackSignature)) {
            return 'Invalid signature';
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return 'Invalid callback event, no action was taken';
        }

        $data = json_decode($json);
        if (! $data || empty($data->reference)) {
            return response('Invalid payload', 400);
        }
        $ref = $data->reference;

        $invoice = Pembayaran::where('reference', $ref)
            ->where('status', 'Belum Lunas')
            ->first();

        if (!$invoice) {
            return response('Invoice not found or current status is not UNPAID', 404);
        }

        $order_id = $invoice->order_id;
        $dataPembeli = Pembelian::where('order_id', $order_id)->first();
        if (!$dataPembeli) {
            return response()->json(['success' => false, 'message' => 'Order data not found'], 404);
        }

        $dataLayanan = Layanan::where('layanan', $dataPembeli->layanan)->first();
        if (!$dataLayanan) {
            return response()->json(['success' => false, 'message' => 'Service data not found'], 404);
        }

        $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->first();

        $pesanPembeli = 
        "*Pembayaran Berhasil*\n\n" .
        "No Invoice: *$order_id*\n" .
        "Layanan : *$dataPembeli->layanan*\n" .
        "ID : *$dataPembeli->user_id*\n" .
        "Server : *$dataPembeli->zone*\n" .
        "Nickname : *$dataPembeli->nickname*\n" .
        "Harga : *Rp. " . number_format($dataPembeli->harga, 0, '.', ',') . "*\n" .
        "Status Pembelian: *Diproses*\n" .
        "Estimasi Proses: *1-5 Menit Max 24 Jam*\n\n" .
        "INI ADALAH PESAN OTOMATIS";

        $zoneSend = $dataPembeli->zone == null ? "" : "($dataPembeli->zone)\n";
        $nickname = $dataPembeli->nickname == null ? '' : "Nickname : $dataPembeli->nickname\n";

        $pesanAdmin = 
        "*Pembayaran Berhasil*\n\n" .
        "No Invoice: *$order_id*\n" .
        "Layanan : $dataPembeli->layanan\n" .
        "ID : $dataPembeli->user_id\n" .
        "Server : $dataPembeli->zone\n" .
        $nickname .
            "Metode Pembayaran : $invoice->metode\n" .
            "Harga : Rp. " . number_format($invoice->harga, 0, '.', ',') . "\n\n" .
            "*Kontak Pembeli*\n" .
            "No HP : $invoice->no_pembeli\n" .
            "Invoice : " . env("APP_URL") . "/id/invoices/$order_id";

        $uid = $dataPembeli->user_id;
        $zone = $dataPembeli->zone;
        $provider_id = $dataLayanan->provider_id;

        if (intval($data->total_amount) !== (int) $invoice->harga) {
            return 'Invalid amount';
        }

        if ($data->status == "PAID") {
            
                $order = ['data' => ['status' => false], 'message' => 'Provider tidak dikenali.'];
                $requestPesan = $this->msg($api->nomor_admin, $pesanAdmin);
		        $pesanPembeli = $this->msg($invoice->no_pembeli, $pesanPembeli);
           
                if($dataLayanan->provider == "digiflazz"){
                    $random_part = Str::random(18, '123456789');
                    $provider_order_id = 'CISHOP' . $random_part;
                    $digiFlazz = new digiFlazzController;
                    $order = $digiFlazz->order($uid, $zone, $provider_id, $provider_order_id);
    
                    if ($order['data']['status'] == "Pending" || $order['data']['status'] == "Sukses") {
                        $order['data']['status'] = true;
                        $order['transactionId'] = $provider_order_id;
                    } else {
                        $order['data']['status'] = false;
                    }
                } else if ($dataLayanan->provider == "moogold") {
                        $moo = new MoogoldController();
                        $random_part = mt_rand(100000, 999999);
                        $provider_order_id = 'CISHOP' . $random_part;
                        $order = $moo->order($uid, $provider_id, $provider_order_id, $zone);
                        Log::info('callback moogold', $order);
                        if(isset($order['data']['status'])){
                            $provider_order_id = $order['order_id'];
                            $order['data']['status'] = true;
                        }else{
                            $order['data']['status'] = false;
                        }
                    } else if(in_array($dataLayanan->provider, ["vip", "vip-game", "vip-pulsa"], true)){
                    $vip = new VipResellerController;
                    $order = $vip->order($uid, $zone, $provider_id);

                    if(($order['result'] ?? false) === true){
                        $order['data']['status'] = true;
                        $order['transactionId'] = $order['data']['trxid'] ?? 0;
                    }else{
                        $order['data']['status'] = false;
                    }
                } else if($dataLayanan->provider == "apigames"){
                    $provider_order_id = rand(1, 10000);
                    $apigames = new ApiGamesController;
                    $order = $apigames->order($uid, $zone, $provider_id, $provider_order_id);
    
                    if($order['data']['status'] == "Sukses"){
                        $order['transactionId'] = $provider_order_id;
                        $order['data']['status'] = true;
                    }else{
                        $order['data']['status'] = false;
                    }
                } else if ($dataLayanan->provider == "bangjeff") {
                            $bangjef = new BangjeffController;
                            
                            $ttlpembelian = [
                                [
                                    "name" => "id",
                                    "value" => $dataPembeli->user_id
                                ]
                            ];
                        
                            if ($dataPembeli->zone != null) {
                                $ttlpembelian[] = [
                                    "name" => "server",
                                    "value" => $dataPembeli->zone
                                ];
                            }
                            
                            $order = $bangjef->order($provider_id, $order_id, 1, $ttlpembelian);
                        
                            if ($order['error'] == false) {
                                $order['transactionId'] = $order['data']['invoiceNumber'];
                                $order['data']['status'] = true;
                            } else {
                                $order['data']['status'] = false;
                            }
                        }  else if($dataLayanan->provider == "joki"){
                    $order['data']['status'] = true;
                }
            
                if ($order['data']['status']) { // Jika pembelian sukses
                
               
        
                if($dataPembeli->tipe_transaksi !== 'joki'){
                    
                    
                    $pesanSukses = 
                    "*Pembayaran Kamu berhasil✨*\n\n" .
                    "Terima kasih berikut detail transaksinya  :\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan: *$dataPembeli->layanan*\n" .
                    "ID : *$dataPembeli->user_id*\n" .
                    "Server : *$dataPembeli->zone*\n" .
                    "Nickname : *$dataPembeli->nickname*\n" .
                    "No Whatsapp: *$invoice->no_pembeli*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n\n" .
                    "Invoice : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
                    "*Ditunggu orderan selanjutnya! Terimakasih.*\n";
                   
                   $pesanSuksesAdmin = 
                    "*Pembelian Sukses*\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan: *$dataPembeli->layanan*\n" .
                    "ID : *$dataPembeli->user_id*\n" .
                    "Server : *$dataPembeli->zone*\n" .
                    "Nickname : *$dataPembeli->nickname*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Sukses*\n\n" .
                    "*Kontak Pembeli*\n" .
                    "No HP : $invoice->no_pembeli\n" .
                   "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
                   "INI ADALAH PESAN OTOMATIS";
    
                    $requestSuksesAdmin = $this->msg($api->nomor_admin, $pesanSuksesAdmin);
    		        $requestSukses = $this->msg($invoice->no_pembeli, $pesanSukses);

                    $dataPembeli->update([
                        'provider_order_id' => isset($order['transactionId']) ? $order['transactionId'] : 0,
                        'status' => 'Proses',
                        'log' => json_encode($order)
                    ]);
                    
                    
                    
                }else{
                    
                    
                     $pesanSukses = 
                    "*Pembelian Sukses*\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan: *$dataPembeli->layanan*\n" .
                    "ID : *$dataPembeli->user_id*\n" .
                    "Server : *$dataPembeli->zone*\n" .
                    "Nickname : *$dataPembeli->nickname*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Sukses*\n" .
                   "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
                   "INI ADALAH PESAN OTOMATIS";
                   
                   $pesanSuksesAdmin = 
                    "*Pembelian Sukses*\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan: *$dataPembeli->layanan*\n" .
                    "ID : *$dataPembeli->user_id*\n" .
                    "Server : *$dataPembeli->zone*\n" .
                    "Nickname : *$dataPembeli->nickname*\n" .
                    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Sukses*\n\n" .
                    "*Kontak Pembeli*\n" .
                    "No HP : $invoice->no_pembeli\n" .
                   "*Invoice* : " . env("APP_URL") . "/id/invoices/$order_id\n\n" .
                   "INI ADALAH PESAN OTOMATIS";
    
                    $requestSuksesAdmin = $this->msg($api->nomor_admin, $pesanSuksesAdmin);
    		        $requestSukses = $this->msg($invoice->no_pembeli, $pesanSukses);
    
                }
                
                } else { //jika pembelian gagal

                   if($dataPembeli->tipe_transaksi !== 'joki'){
                       
                        $dataPembeli->update([
                        'status' => 'Batal',
                        'log' => json_encode($order)
                        ]);
                        
                   }

                }
            
            $invoice->update(['status' => 'Lunas']);

            return response()->json(['success' => true]);

        } else if ($data->status == "EXPIRED" || $data->status == "FAILED") {

            $invoice->update(['status' => 'Batal']);
            return response()->json(['success' => true]);

        } else {

            return response()->json(['error' => 'Unrecognized payment status']);

        }
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