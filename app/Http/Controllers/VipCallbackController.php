<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;


class VipCallbackController extends Controller
{

public function handle(Request $request)
{
    $allowedIps = array_filter(array_map('trim', explode(',', env('VIP_CALLBACK_ALLOWED_IPS', '178.248.73.218'))));
    if (! in_array($request->ip(), $allowedIps, true)) {
        \Log::warning('Unauthorized VIP callback IP', ['ip' => $request->ip()]);
        return response()->json(['message' => 'Unauthorized IP'], 403);
    }

    $api = \DB::table('setting_webs')->where('id',1)->first();
    if (!$api || empty($api->vip_apiid) || empty($api->vip_apikey)) {
        return response()->json(['message' => 'VIP Reseller config is incomplete'], 500);
    }

    $expectedSignature = md5($api->vip_apiid.$api->vip_apikey);
    if (! hash_equals((string) $expectedSignature, (string) $request->header('X-Client-Signature'))) {
        \Log::warning('VIP callback signature mismatch.');
        return response()->json(['message' => 'Signature mismatch'], 401);
    }

    $payload = $request->json()->all();
    $data = $payload['data'] ?? [];

    $trxid   = $data['trxid'] ?? null;
    $userId  = $data['data'] ?? null;
    $zone    = $data['zone'] ?? null;
    $service = $data['service'] ?? null;
    $status  = strtolower((string) ($data['status'] ?? ''));
    $note    = $data['note'] ?? null;
    $price   = $data['price'] ?? 0;

    if (empty($trxid)) {
        return response()->json(['message' => 'Missing trxid'], 422);
    }

    $invoice = \App\Models\Pembelian::where('provider_order_id', $trxid)
        ->whereIn('status', ['Pending', 'Proses'])
        ->first();

    if (!$invoice) {
        \Log::error('VIP callback invoice not found', ['trxid' => $trxid]);
        return response()->json(['message' => 'Invoice not found'], 404);
    }

    $mappedStatus = match ($status) {
        'success' => 'Sukses',
        'error', 'failed', 'fail', 'cancel', 'canceled' => 'Batal',
        default => 'Proses',
    };

    $updateData = [
        'status' => $mappedStatus,
        'log' => json_encode($payload),
    ];

    if ($invoice->tipe_transaksi == 'voucher') {
        $updateData['voucher'] = $userId;
    } elseif ($invoice->tipe_transaksi == 'game') {
        $updateData['message'] = $note;
    }

    $invoice->update($updateData);

    $updatePesanan = \App\Models\Pembayaran::where('order_id', $invoice->order_id)->first();

    if ($updatePesanan && $mappedStatus === 'Sukses') {
        $pesanSukses = "*Pembelian Sukses*\n\n" .
            "No Invoice: *$invoice->order_id*\n" .
            "Layanan: *$service*\n" .
            "ID : *$userId*\n" .
            "Server : *$zone*\n" .
            "Nickname : *$invoice->nickname*\n" .
            "Harga: *Rp. " . number_format($price, 0, '.', ',') . "*\n" .
            "Status Pembelian: *Sukses*\n" .
            "Metode Pembayaran: *$updatePesanan->metode*\n\n" .
            "*Invoice* : " . env("APP_URL") . "/id/invoices/$invoice->order_id\n\n" .
            "INI ADALAH PESAN OTOMATIS";

        $this->msg($updatePesanan->no_pembeli, $pesanSukses);
    }

    return response()->json(['message' => 'OK']);
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
