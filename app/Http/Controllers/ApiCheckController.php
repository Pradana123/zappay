<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\provider\vipreseller\VipResellerController;

class ApiCheckController extends Controller
{
    public function check($user_id = null, $zone_id = null, $game = null)
    {
        $code = $this->resolveVipNicknameCode($game);

        if (!$code) {
            Log::warning('VIP Reseller nickname code not mapped', ['game' => $game]);
            return [
                'status' => ['code' => 1],
                'message' => 'Game belum tersedia untuk cek nickname VIP Reseller.',
            ];
        }

        $vip = new VipResellerController();
        $result = $vip->getNickname($user_id, $zone_id, $code);

        if (($result['result'] ?? false) === true) {
            $data = [
                'status' => ['code' => 200],
                'data' => [
                    'user_id' => $user_id,
                    'username' => $result['data'] ?? null,
                ],
            ];

            if ($zone_id !== null && $zone_id !== '' && $zone_id !== '-') {
                $data['data']['zone_id'] = $zone_id;
            }

            if (isset($result['country'])) {
                $data['data']['country'] = $result['country'];
            }

            return $data;
        }

        Log::info('VIP Reseller nickname check failed', [
            'game' => $game,
            'code' => $code,
            'message' => $result['message'] ?? null,
        ]);

        return [
            'status' => ['code' => 1],
            'message' => $result['message'] ?? 'Nickname tidak ditemukan.',
        ];
    }

    private function resolveVipNicknameCode($game): ?string
    {
        $name = strtolower(trim((string) $game));
        $name = preg_replace('/\s+/', ' ', $name);

        $exact = [
            'mobile legends' => 'mobile-legends',
            'mobile legends: bang bang' => 'mobile-legends',
            'mobile legends bang bang' => 'mobile-legends',
            'mobile legends region' => 'mobile-legends-region',
            'free fire' => 'free-fire',
            'free fire max' => 'free-fire',
            'pubg mobile' => 'pubgm',
            'pubgm' => 'pubgm',
            'valorant' => 'valorant',
            'genshin impact' => 'genshin-impact',
            'honkai star rail' => 'honkai-star-rail',
            'honkai: star rail' => 'honkai-star-rail',
            'point blank' => 'pointblank',
        ];

        if (isset($exact[$name])) {
            return $exact[$name];
        }

        if (str_contains($name, 'mobile legends')) {
            return 'mobile-legends';
        }

        if (str_contains($name, 'free fire')) {
            return 'free-fire';
        }

        if (str_contains($name, 'pubg')) {
            return 'pubgm';
        }

        if (str_contains($name, 'genshin')) {
            return 'genshin-impact';
        }

        if (str_contains($name, 'honkai') && str_contains($name, 'star')) {
            return 'honkai-star-rail';
        }

        if (str_contains($name, 'point blank')) {
            return 'pointblank';
        }

        return null;
    }
}
