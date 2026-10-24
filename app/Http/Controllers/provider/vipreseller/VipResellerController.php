<?php

namespace App\Http\Controllers\provider\vipreseller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class VipResellerController extends Controller
{
    private const GAME_FEATURE_ENDPOINT = 'https://vip-reseller.co.id/api/game-feature';

    public function order($uid = null, $zone = null, $service = null)
    {
        if (empty($uid) || empty($service)) {
            return $this->failed('Data tujuan atau kode layanan VIP Reseller kosong.');
        }

        $payload = [
            'type' => 'order',
            'service' => $service,
            'data_no' => $uid,
        ];

        if ($zone !== null && $zone !== '' && $zone !== '-') {
            $payload['data_zone'] = $zone;
        }

        return $this->requestGameFeature($payload);
    }

    public function orderGame($uid = null, $zone = null, $service = null)
    {
        return $this->order($uid, $zone, $service);
    }

    public function orderPulsa($uid = null, $zone = null, $service = null)
    {
        return $this->order($uid, $zone, $service);
    }

    public function service($uid = null, $zone = null, $service = null)
    {
        $payload = ['type' => 'services'];

        if (!empty($uid)) {
            $payload['filter_game'] = $uid;
        }

        if (!empty($zone)) {
            $payload['filter_status'] = $zone;
        }

        return $this->requestGameFeature($payload);
    }

    public function status($poid = null)
    {
        $payload = ['type' => 'status'];

        if (!empty($poid)) {
            $payload['trxid'] = $poid;
        }

        return $this->requestGameFeature($payload);
    }

    public function username($uid = null, $zone = null, $code = 'mobile-legends')
    {
        return $this->getNickname($uid, $zone, $code);
    }

    public function getNickname($uid = null, $zone = null, $code = 'mobile-legends')
    {
        if (empty($uid) || empty($code)) {
            return $this->failed('User ID atau kode game kosong.');
        }

        $payload = [
            'type' => 'get-nickname',
            'code' => $code,
            'target' => $uid,
        ];

        if ($zone !== null && $zone !== '' && $zone !== '-') {
            $payload['additional_target'] = $zone;
        }

        return $this->requestGameFeature($payload);
    }

    private function requestGameFeature(array $payload)
    {
        $api = \DB::table('setting_webs')->where('id', 1)->first();

        if (!$api || empty($api->vip_apiid) || empty($api->vip_apikey)) {
            return $this->failed('API ID atau API Key VIP Reseller belum diatur.');
        }

        $payload = array_merge([
            'key' => $api->vip_apikey,
            'sign' => md5($api->vip_apiid . $api->vip_apikey),
        ], $payload);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => self::GAME_FEATURE_ENDPOINT,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            Log::error('VIP Reseller request failed', [
                'type' => $payload['type'] ?? null,
                'error' => $curlError,
            ]);
            return $this->failed('Gagal menghubungi VIP Reseller.');
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) {
            Log::error('VIP Reseller returned invalid JSON', [
                'type' => $payload['type'] ?? null,
                'http_code' => $httpCode,
                'response' => $response,
            ]);
            return $this->failed('Respons VIP Reseller tidak valid.');
        }

        return $decoded;
    }

    private function failed(string $message): array
    {
        return [
            'result' => false,
            'message' => $message,
            'data' => ['status' => false],
        ];
    }
}
