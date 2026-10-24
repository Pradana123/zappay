<?php

namespace App\Http\Controllers\provider\wejistore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WejiStoreController extends Controller
{
    public function order($uid = null, $zone = null, $service = null)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $sign = md5($api->wj_apiid.$api->wj_apikey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://wesintopup.my.id/api/game-feature");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api->wj_apikey."&sign=$sign&type=order&service=$service&data_no=$uid&data_zone=$zone");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $res = json_decode(curl_exec($ch), true);
        return $res;
    }

    public function status($poid = null)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $sign = md5($api->wj_apiid.$api->wj_apikey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://wesintopup.my.id/api/game-feature');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api->wj_apiid."&sign=$sign&type=status&trxid=$poid");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $res = json_decode(curl_exec($ch), true);
        return $res;
    }
    
    public function username($uid = null, $zone = null)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        $sign = md5($api->wj_apiid.$api->wj_apikey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://wesintopup.my.id/api/game-feature');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api->wj_apikey."&sign=$sign&type=$get_nickname&code=$code&service=$service&data_no=$uid&data_zone=$zone");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $res = json_decode(curl_exec($ch), true);
        return $res;
    }
}