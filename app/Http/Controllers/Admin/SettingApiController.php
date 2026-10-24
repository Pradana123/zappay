<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;


class SettingApiController extends Controller
{
    public function settingApi()
    {
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        return view('components.admin.settingapi',[
            'api' => DB::table('setting_webs')->where('id',1)->first(),
            ]);
        
        try {
        $response = Http::withOptions([
            'verify' => true,
        ])->get("https://solo.wablas.com/api/device/info", [
            'token' => $api->wa_key,
        ]);

        $data = $response->json()['data'];
        $status = $data['status'];
        $account = $data['name'];
        $expired = $data['expired_date'];
        $sender = $data['sender'];
        $wa_key = $api->wa_key;

        return view('components.admin.settingapi', [
            'status' => $status,
            'account' => $account,
            'expired' => $expired,
            'sender' => $sender,
            'wa_key' => $wa_key,
        ]);
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        $errorMessage = "Terjadi kesalahan koneksi ke API. Silakan coba lagi nanti.";
        return view('components.admin.settingapi', [
            'error' => $errorMessage,
        ]);
    } catch (\Illuminate\Http\Client\RequestException $e) {
        $errorMessage = "Terjadi kesalahan saat mengambil data dari API. Silakan coba lagi nanti.";
        return view('components.admin.settingapi', [
            'error' => $errorMessage,
        ]);
    }
    }
    
    public function saveSettingPaydisini(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'paydisini_apikey' => $request->paydisini_apikey,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Paydisini!!');
        
    }
    
    public function saveSettingTripay(Request $request)
    {
        $validated = $request->validate([
            'tripay_api' => ['required', 'string', 'max:255'],
            'tripay_merchant_code' => ['required', 'string', 'max:100'],
            'tripay_private_key' => ['required', 'string', 'max:255'],
        ]);

        DB::table('setting_webs')->where('id',1)->update([
          'tripay_api' => trim($validated['tripay_api']),
          'tripay_merchant_code' => trim($validated['tripay_merchant_code']),
          'tripay_private_key' => trim($validated['tripay_private_key'])
        ]);

        return back()->with('success','Berhasil konfigurasi Tripay!');
        
    }
    
    public function saveSettingTokopay(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'tokopay_merchant_id' => $request->tokopay_merchant_id,
          'tokopay_secret_key' => $request->tokopay_secret_key
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Tokopay!');
        
    }
    
    public function saveSettingDigiflazz(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'username_digi' => $request->username_digi,
          'api_key_digi' => $request->api_key_digi,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Digiflazz!');
        
    }
    
    public function saveSettingBangjeff(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'apikey_bangjeff' => $request->apikey_bangjeff
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Bangjeff!');
        
    }
    public function saveSettingAoshi(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'apikey_aoshi' => $request->apikey_aoshi
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi AoshiMarket!');
        
    }
    public function saveSettingMobilegamestore(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'api_mobilegamestore' => $request->api_mobilegamestore
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Api Key Mobilegamestore!');
        
    }
    
    public function saveSettingApigames(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'apigames_secret' => $request->apigames_secret,
          'apigames_merchant' => $request->apigames_merchant,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Apigames!');
        
    }
    
    public function saveSettingVip(Request $request)
    {
        $validated = $request->validate([
            'vip_apiid' => ['required', 'string', 'max:100'],
            'vip_apikey' => ['required', 'string', 'max:255'],
        ]);

        DB::table('setting_webs')->where('id',1)->update([
          'vip_apiid' => trim($validated['vip_apiid']),
          'vip_apikey' => trim($validated['vip_apikey']),
        ]);

        return back()->with('success','Berhasil konfigurasi VIP Reseller!!');
        
    }
    
    public function saveSettingWj(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'wj_apiid' => $request->wj_apiid,
          'wj_apikey' => $request->wj_apikey,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi WeJiStore!!');
        
    }
    
    public function saveSettingWagateway(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'nomor_admin' => $request->nomor_admin,
          'wa_key' => $request->wa_key,
          'wa_number' => $request->wa_number
            
        ]);
        
         $response = $this->changeNumber($request->wa_number);
        
        return back()->with('success','Berhasil konfigurasi WA gateway!');
        
    }
    
    public function changeNumber($number){
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $curl = curl_init();
        $data = [
            'phone' => "$number",
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $api->wa_key",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL,  "https://solo.wablas.com/api/device/change-number");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    
}