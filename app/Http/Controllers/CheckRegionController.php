<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\provider\vipreseller\VipResellerController;

class CheckRegionController extends Controller
{
    public function create()
    {
        return view('components.cekregion', [
            'logoheader' => \App\Models\Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => \App\Models\Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function check(Request $request)
    {
        $uid = $request->input('uid');
        $server = $request->input('server');

        $vip = new VipResellerController();
        $result = $vip->getNickname($uid, $server, 'mobile-legends-region');

        if (($result['result'] ?? false) === true) {
            return response()->json([
                'status' => true,
                'data' => [
                    'nickname' => $result['data'] ?? null,
                    'region' => $result['country']['name'] ?? null,
                    'country_code' => $result['country']['code'] ?? null,
                    'author' => 'ZAPPAY',
                ],
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => $result['message'] ?? 'Data tidak ditemukan.',
        ], 422);
    }
}
