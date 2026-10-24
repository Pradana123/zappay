<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;


class SettingWebController extends Controller
{
    public function settingWeb()
    {
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        return view('components.admin.settingweb',[
            'web' => DB::table('setting_webs')->where('id',1)->first(),
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

        return view('components.admin.settingweb', [
            'status' => $status,
            'account' => $account,
            'expired' => $expired,
            'sender' => $sender,
            'wa_key' => $wa_key,
        ]);
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        $errorMessage = "Terjadi kesalahan koneksi ke API. Silakan coba lagi nanti.";
        return view('components.admin.settingweb', [
            'error' => $errorMessage,
        ]);
    } catch (\Illuminate\Http\Client\RequestException $e) {
        $errorMessage = "Terjadi kesalahan saat mengambil data dari API. Silakan coba lagi nanti.";
        return view('components.admin.settingweb', [
            'error' => $errorMessage,
        ]);
    }
    }
    
    public function saveSettingWeb(Request $request)
    {
        $request->validate([
            'judul_web' => 'required',
            'keywords' => 'required',
            'deskripsi_web' => 'required',
            'logo_header' => 'image:mimes:jpg,jpeg,png',
            'logo_footer' => 'image:mimes:jpg,jpeg,png',
            'logo_favicon' => 'image:mimes:jpg,jpeg,png',
            'url_wa' => 'required',
            'url_ig' => 'required',
            'url_tiktok' => 'required',
            'url_youtube' => 'required',
            'url_fb' => 'required',
            // 'zappaydo_api' => 'required',
        ]);
        
        if($request->file('logo_header')){
            $file = $request->file('logo_header');
            $folder = 'assets/logo';
            $file->move($folder, $file->getClientOriginalName());      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_header' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        
        if($request->file('logo_footer')){
            $file2 = $request->file('logo_footer');
            $folder2 = 'assets/logo';
            $file2->move($folder2, $file2->getClientOriginalName());      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_footer' => "/".$folder2."/".$file2->getClientOriginalName()
            ]);
        }
        
        if($request->file('logo_favicon')){
            $file3 = $request->file('logo_favicon');
            $folder3 = 'assets/logo';
            $file3->move($folder3, $file3->getClientOriginalName());      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_favicon' => "/".$folder3."/".$file3->getClientOriginalName()
            ]);
        }
        
        DB::table('setting_webs')->where('id',1)->update([
           
           'judul_web' => $request->judul_web,
           'keywords' => $request->keywords,
           'deskripsi_web' => $request->deskripsi_web,
           'url_wa' => $request->url_wa,
           'url_ig' => $request->url_ig,
           'url_tiktok' => $request->url_tiktok,
           'url_youtube' => $request->url_youtube,
           'url_fb' => $request->url_fb,
        //    'zappaydo_api' => $request->zappaydo_api,
           'created_at' => now(),
           'updated_at' => now()
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi website!');
        
        
    }
    
    public function saveSettingWarna(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'warna1' => $request->warna1,
          'warna2' => $request->warna2,
          'warna3' => $request->warna3,
          'warna4' => $request->warna4
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Warna!');
        
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
    
     public function saveSettingMutasi(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'ovo_admin' => $request->ovo_admin,
          'ovo1_admin' => $request->ovo1_admin,
          'gopay_admin' => $request->gopay_admin,
          'gopay1_admin' => $request->gopay1_admin,
          'dana_admin' => $request->dana_admin,
          'shopeepay_admin' => $request->shopeepay_admin,
          'bca_admin' => $request->bca_admin
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi mutasi e-wallet / bank!');
        
    }
    
}