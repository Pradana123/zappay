<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\Berita;
use App\Models\Pembayaran;
use App\Models\Method;
use App\Http\Controllers\PaydisiniController;
use App\Http\Controllers\TriPayController;
use App\Http\Controllers\TokoPayController;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function reloadd()
    {
        return view('template.reload', [
            'data' => Deposit::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->get(),
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'pay_method' => Method::all()
        ]);
    }

    public function create()
    {
        return view('template.deposit', [
            'data' => Deposit::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->get(),
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'pay_method' => Method::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'metode' => 'required',
            'no_telfon' => 'required',
        ], [
            'jumlah.numeric' => 'Jumlah tidak valid',
            'jumlah.min' => 'Jumlah tidak valid',
            'jumlah.required' => 'Harap mengisi jumlah',
            'no_telfon.required' => 'Harap Mengisi No WhatsApp',
            'metode.required' => 'Harap mengisi metode',
        ]);

        Log::info('🔍 Deposit Request Received:', $request->all());

        if (!$request->filled('metode')) {
            Log::warning('⚠️ Metode pembayaran tidak dikirim.');
            
            return redirect()->back()->with('error', 'Metode pembayaran belum dipilih');
        }

        $method = Method::where('code', $request->metode)->first();

        if (!$method) {
            Log::error('❌ Provider tidak ditemukan untuk kode: ' . $request->metode);
            return redirect()->back()->with('error', 'Provider tidak dikenali');
        }

        Log::info('✅ Metode ditemukan:', $method->toArray());

        $unik = date('Hs');
        $kode_unik = substr(md5(uniqid(rand(), true)), 0, 8);
        $order_id = 'BGRL' . $unik . $kode_unik;

        $fix_fee = $method->fix_fee ?? 0;
        $fee_percent = $method->fee_percent ?? 0;

        $total1 = $request->jumlah + $fix_fee;
        $total2 = $total1 * ($fee_percent / 100);
        $ttldeposit = round($total1 + $total2);

        $provider = $method->payment; // gunakan field payment sebagai provider
        $no_pembayaran = null;
        $reference = null;
        $amount = null;

        switch ($provider) {
            case 'tripay':
                $tripay = new TriPayController();
                $tripayres = $tripay->request($order_id, $ttldeposit, $method->code, $order_id . '@email.com', $request->no_telfon);
                
                if (!isset($tripayres['success']) || $tripayres['success'] != true) {
                    return redirect()->back()->with('error', $tripayres['message'] ?? 'Gagal memproses pembayaran Tripay');
                }
            
                $no_pembayaran = $tripayres['qr_url'] ?? null;
                $reference = $tripayres['reference'] ?? null;
                $amount = $tripayres['amount'] ?? null;
                break;
                
            case 'paydisini':
                $paydisini = new PaydisiniController();
                $response = $paydisini->createTransaction(new Request([
                    'unique_code' => $order_id,
                    'service' => $method->code,
                    'amount' => $ttldeposit,
                    'note' => 'Deposit Saldo',
                    'valid_time' => 1800,
                    'ewallet_phone' => $request->no_telfon,
                    'type_fee' => 1,
                ]));

                $responseData = $response->getData(true);
                if (!isset($responseData['data'])) {
                    return redirect()->back()->with('error', $responseData['msg'] ?? 'Gagal memproses pembayaran Paydisini');
                }

                $no_pembayaran = $responseData['data']['qrcode_url'] ?? $responseData['data']['checkout_url'];
                $reference = $responseData['data']['unique_code'];
                $amount = $responseData['data']['amount'];
                break;

            case 'tokopay':
                $tokopay = new TokoPayController();
                $tokopayres = $tokopay->createOrder($ttldeposit, $order_id, $method->code);

                if ($tokopayres['status'] !== 'Success') {
                    return response()->json(['status' => false, 'data' => $tokopayres['error_msg'] ?? 'Gagal memproses pembayaran Tokopay']);
                }

                $no_pembayaran = $tokopayres['data']['qr_link'] ?? null;
                $reference = $tokopayres['data']['trx_id'] ?? null;
                $amount = $tokopayres['data']['total_bayar'] ?? null;
                break;

            default:
                Log::error('❌ Provider tidak dikenali: ' . $provider);
                return redirect()->back()->with('error', 'Provider tidak dikenali');
        }

        Deposit::create([
            'order_id' => $order_id,
            'username' => Auth::user()->username,
            'metode' => $request->metode,
            'no_pembayaran' => $no_pembayaran,
            'jumlah' => $request->jumlah,
            'status' => 'Pending',
        ]);

        Pembayaran::create([
            'order_id' => $order_id,
            'harga' => $amount,
            'no_pembayaran' => $no_pembayaran,
            'no_pembeli' => $request->no_telfon,
            'status' => 'Belum Lunas',
            'metode' => $request->metode,
            'reference' => $reference,
        ]);

        return redirect('/id/deposit/' . $order_id)->with('success', 'Berhasil deposit saldo, silahkan lakukan pembayaran');
    }
}