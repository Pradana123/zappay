<?php

namespace App\Http\Controllers\provider\topupedia;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Http;

class TopupediaController extends Controller
{
    private $api;
    public function __construct()
    {
        $this->api = env('TOPUPEDIA_API_KEY');
        $this->url = 'https://api.topupedia.com';
    }
    public function balance()
    {
        $data = $this->go($this->url.'/api/v3/balance');
        
        return $data;
    }
    
    public function getProduct()
    {
        $data = $this->go($this->url.'/api/v3/product');
        
        return $data;
    }
    
    public function listVariant()
    {
        $data = $this->go($this->url.'/api/v3/variant', [
            'code' => 'MLBB'
        ]);
        
        return $data;
    }
    
    public function detailVariant($code)
    {
        $data = $this->go($this->url.'/api/v3/variant/'.$code);
        
        return $data;
    }
    
    
     public function order($code,$ref,$qty,$input)
    {
        $data = $this->go($this->url.'/api/v3/checkout',[
          'code' => $code,
          'referenceNumber' => $ref,
          'qty' => $qty,
          'inputs' => $input
        ]);
        
        return $data;
    }
    
    
    public function checkOrder($invoice)
    {
        $data = $this->go($this->url."/api/v3/order/{$invoice}");
        
        return $data;
    }
    
    public function go($url,$data = [])
    {
        if (empty($this->api)) {
            return [
                'error' => true,
                'message' => 'TOPUPEDIA_API_KEY belum diatur di file .env.'
            ];
        }

        $data = Http::withToken($this->api)->timeout(30)->post($url,$data);

        $response = $data->json();

        return $response;

    }

  public function handleCallback(Request $request)
  {
    $data = json_decode($request->getContent(), true);

    if (!is_array($data) || empty($data['invoice_number']) || empty($data['status_code'])) {
        return response()->json(['message' => 'Invalid callback payload'], 400);
    }

    $poid = $data['invoice_number'];
    $voucher = $data['voucher'] ?? null;
    $statusCode = $data['status_code'];

    if ($statusCode === "SUCCESS") {
        $statusCode = "Sukses";
    }

    \Log::info('Topupedia callback diterima', [
        'invoice_number' => $poid,
        'status_code' => $statusCode,
    ]);

    $pembelian = Pembelian::where('provider_order_id', $poid)->first();

    if ($pembelian) {
        $updateData = [
            'status' => $statusCode
        ];

        if ($pembelian->tipe_transaksi == "voucher" && $voucher !== null) {
            $updateData['voucher'] = $voucher;
        }

        $pembelian->update($updateData);
    }

    return response()->json(['message' => 'Callback processed']);
}

    
    
}
    
    