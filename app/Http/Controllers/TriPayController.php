<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;

class TriPayController extends Controller
{
    public function request($idOrder, $jumlah, $method, $dataUser, $nohp)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if (!$api || empty($api->tripay_api) || empty($api->tripay_merchant_code) || empty($api->tripay_private_key)) {
            return ['success' => false, 'msg' => 'Konfigurasi Tripay belum lengkap.'];
        }

        $data = [
            'method'         => $method,
            'merchant_ref'   => $idOrder,
            'amount'         => $jumlah,
            'customer_name'  => env("APP_NAME"),
            'customer_email' => $dataUser,
            'customer_phone' => $nohp,
            'order_items'    => [
                [
                    'name'        => 'Pembayaran ' . $method . ' ' . $idOrder,
                    'price'       => $jumlah,
                    'quantity'    => 1,
                ]
            ],
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $api->tripay_merchant_code . $idOrder . $jumlah, $api->tripay_private_key)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $rawResponse = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($rawResponse === false) {
            return ['success' => false, 'msg' => 'Gagal menghubungi Tripay: ' . $error];
        }

        $response = json_decode($rawResponse);
        if (!$response || !isset($response->success)) {
            return ['success' => false, 'msg' => 'Respons Tripay tidak valid'];
        }

        $paymentNumber = '';
        // if ($method == "QRISC" || $method == "QRISD" || $method == "QRIS" || $method == "QRISOP") {
        //     $paymentNumber = $response->data->qr_url;
        // } else if ($response->data->pay_code == null) {
        //     $paymentNumber = $response->data->checkout_url;
        // } else {
        //     $paymentNumber = $response->data->pay_code;
        // }
        
        
        if($response->success == true){
            
                
            if(empty($response->data->pay_code)){
                
                if(empty($response->data->pay_url)){
                    
                    $paymentNumber = $response->data->qr_url;
                    
                }else{
                    
                    $paymentNumber = $response->data->pay_url;
                    
                }
                
                
            }else{
                
                $paymentNumber = $response->data->pay_code;
            }
            
            return array('success' => $response->success, 'amount' => $response->data->amount, 'no_pembayaran' => $paymentNumber, 'reference' => $response->data->reference);
            
        }else{
            
            $err = strtolower($response->message ?? '');
            
            $msg = '';
            
            if(str_contains($err, 'minimum')) { 
                
                $pch = explode('rp',$err);
                
                $msg = 'Minimum jumlah pembayaran untuk metode pembayaran ini adalah Rp '.$pch[1].' ';
                
            }elseif(str_contains($err, 'maximum')){
                
                $pch = explode('rp',$err);
                
                $msg = 'Maksimal jumlah pembayaran untuk metode pembayaran ini adalah Rp '.$pch[1].' ';
                
            }else{
                
                $msg = 'Metode pembayaran ini sedang tidak dapat digunakan';
            }
                        
             return array('success' => false,'msg' => $msg);
            
            
        }
        
          
        
        
    }

    public function fee($jumlah, $code)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if (!$api || empty($api->tripay_api)) {
            return 0;
        }
        
        $payload = [
            'code'    => $code,
            'amount'    => $jumlah
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/merchant/fee-calculator?' . http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api],
            CURLOPT_FAILONERROR    => false
        ]);

        $rawResponse = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        $response = json_decode($rawResponse);
        if (!$response || empty($response->success) || !isset($response->data[0]->total_fee)) {
            return 0;
        }

        return $response->data[0]->total_fee->customer + $response->data[0]->total_fee->merchant;
    }

    public function channel()
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if (!$api || empty($api->tripay_api)) {
            return (object) ['success' => false, 'message' => 'Konfigurasi Tripay belum lengkap.'];
        }
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api],
            CURLOPT_FAILONERROR    => false
        ]);

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);

        curl_close($curl);

        return $response;
    }

    public function detail($reference)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if (!$api || empty($api->tripay_api)) {
            return (object) ['success' => false, 'message' => 'Konfigurasi Tripay belum lengkap.'];
        }
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api/transaction/detail?reference=' . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $api->tripay_api ],
            CURLOPT_FAILONERROR    => false
        ]);

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);

        curl_close($curl);

        return $response;
    }
}
