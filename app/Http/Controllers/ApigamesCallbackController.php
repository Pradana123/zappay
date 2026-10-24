<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;

class ApigamesCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $allowedIps = array_filter(array_map('trim', explode(',', env('APIGAMES_CALLBACK_ALLOWED_IPS', '157.245.207.5,54.86.50.139'))));
        if (! in_array($request->ip(), $allowedIps, true)) {
            Log::warning('Unauthorized IP Access: ' . $request->ip());
            return response()->json(['message' => 'Forbidden','your_ip' => $request->ip()], 403);
        }

        // Data Konfigurasi
        $merchantId = $api->apigames_merchant; 
        $secretKey = $api->apigames_secret;

        // Ambil data dari body request
        $data = $request->json()->all();

        // Validasi data dasar
        if (!isset($data['merchant_id'], $data['ref_id'])) {
            Log::error('Missing required fields in webhook payload');
            return response()->json(['message' => 'Bad Request'], 400);
        }

        // Validasi merchant ID
        if (! hash_equals((string) $merchantId, (string) $data['merchant_id'])) {
            Log::warning('Invalid Merchant ID: ' . $data['merchant_id']);
            return response()->json(['message' => 'Invalid Merchant'], 400);
        }

        // Validasi Signature X-Apigames-Authorization
        $receivedSignature = $request->header('X-Apigames-Authorization');
        $calculatedHash = md5($merchantId . ':' . $secretKey . ':' . $data['ref_id']);

        if (! hash_equals((string) $calculatedHash, (string) $receivedSignature)) {
            Log::warning('Signature mismatch!');
            return response()->json(['message' => 'Invalid Signature'], 401);
        }

        // Ambil informasi dari payload
        $trxId = $data['trx_id'] ?? null;
        $refId = $data['ref_id'];
        $status = $data['status'] ?? 'Gagal';
        $note = $data['message'] ?? '';
        $userId = $data['destination'] ?? null;
        $zone = $data['product_code'] ?? null;
        $productName = $data['product_detail']['name'] ?? 'Unknown Product';
        $priceRp = $data['product_detail']['price_rp'] ?? 0;

        // Cari pembelian berdasarkan ref_id
        $invoice = Pembelian::where('provider_order_id', $trxId)->where('status', 'Proses')->first();

        if (!$invoice) {
            Log::error("Order not found for trx_id: $trxId");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Persiapkan data update
        $updateData = [
            'status' => $status === 'Sukses' ? 'Selesai' : 'Gagal',
            'log' => json_encode($data),
        ];

        if ($invoice->tipe_transaksi == 'voucher') {
            $updateData['voucher'] = $userId;
        } elseif ($invoice->tipe_transaksi == 'game') {
            $updateData['message'] = $note;
            $updateData['sn'] = $data['sn'] ?? null;
        }

        // Update pembelian
        $invoice->update($updateData);

        // Update pembayaran (jika pakai saldo)
        $updatePesanan = Pembayaran::where('order_id', $invoice->order_id)->where('metode', 'SALDO')->first();

        // Kirim pesan WhatsApp jika sukses
        if ($updatePesanan && $status === 'Sukses') {
            $pesanSukses = "*Pembelian Sukses*\n\n" .
                "No Invoice: *$invoice->order_id*\n" .
                "Layanan: *$productName*\n" .
                "ID: *$userId*\n" .
                "Server: *$zone*\n" .
                "Nickname: *$invoice->nickname*\n" .
                "Harga: *Rp." . number_format($priceRp, 0, ',', '.') . "*\n" .
                "Status Pembelian: *Sukses*\n" .
                "Metode Pembayaran: *$updatePesanan->metode*\n\n" .
                "*Invoice* : " . env("APP_URL") . "/id/invoice/$invoice->order_id\n\n" .
                "INI ADALAH PESAN OTOMATIS";

            $this->msg($updatePesanan->no_pembeli, $pesanSukses);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Fungsi untuk kirim pesan WhatsApp via API Fonnte
     */
    protected function msg($nomor, $msg)
    {
        $setting = \DB::table('setting_webs')->where('id', 1)->first();
        $token = $setting->wa_key;
        $apiUrl = 'https://api.fonnte.com/send';

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
        curl_close($curl);

        return $response;
    }
}