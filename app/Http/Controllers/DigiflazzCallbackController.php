<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;

class DigiflazzCallbackController extends Controller 
{
    public function handle(Request $request) 
    {
        $secret = env('DIGIFLAZZ_CALLBACK_SECRET');
        if (empty($secret)) {
            Log::error('DIGIFLAZZ_CALLBACK_SECRET belum diatur di file .env.');
            return response()->json(['message' => 'Callback secret is not configured'], 500);
        }
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        if (hash_equals('sha1=' . $signature, (string) $request->header('X-Hub-Signature'))) {
            $data = json_decode($request->getContent(), true);
            $refId = $data['data']['ref_id'];
            $updateStatus = $data['data']['status'];
            $ser_n = $data['data']['sn'];
            $msg_n = $data['data']['message'];

            if ($request->header('X-Digiflazz-Event') == 'update') {
                $invoice = Pembelian::where('provider_order_id', $refId)->where('status', 'Proses')->first();

                if ($invoice) {
                    $updateData = [
                        'status' => $updateStatus,
                        'log' => json_encode($data)
                    ];

                    $tipeTransaksi = ['game', 'voucher', 'pulsa', 'app', 'populer', 'voucher2'];

                    if (in_array($invoice->tipe_transaksi, $tipeTransaksi)) {
                        $updateData['voucher'] = $ser_n;
                    }

                    $invoice->update($updateData);

                    // Cek apakah invoice ada sebelum mengakses properti 'order_id'
$updatePesanan = Pembayaran::where('order_id', $invoice->order_id)->whereIn('metode', ['SALDO', 'qris', 'e-walet', 'virtual-account', 'convenience-store', 'pulsa'])->first();

                    if ($updatePesanan) {
$pesanSukses = "*Pembelian Sukses*\n\n" . 
    "No Invoice: *$invoice->order_id*\n" . 
    "Layanan: *$invoice->layanan*\n" . 
    "ID : *$invoice->user_id*\n" . 
    "Server : *$invoice->zone*\n" . 
    "Nickname : *$invoice->nickname*\n" . 
    "Harga: *Rp. " . number_format($invoice->harga, 0, '.', ',') . "*\n" . 
    "Status Pembelian: *Sukses*\n" . 
    "Metode Pembayaran: *$updatePesanan->metode*\n" . 
    "SN: *$invoice->voucher*\n\n" . 
    "*Invoice* : " . env("APP_URL") . "/id/invoice/$invoice->order_id\n\n" . 
    "INI ADALAH PESAN OTOMATIS";

                        $this->msg($updatePesanan->no_pembeli, $pesanSukses);
                    }
                } else {
                    Log::error("Invoice not found for ref_id: $refId");
                }
            }
        } else {
            Log::warning('Invalid Digiflazz callback signature.');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        return response()->json(['message' => 'Callback processed'], 200);
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