<?php

namespace App\Services;

use App\Models\Layanan;
use App\Models\User;

class PricingGuard
{
    public function minimumProfit(): int
    {
        return max(0, (int) env('MINIMUM_PRODUCT_PROFIT', 100));
    }

    public function coinMaxPercent(): int
    {
        $percent = (int) env('MAX_COIN_USAGE_PERCENT', 10);
        return max(0, min(100, $percent));
    }

    public function role(?User $user): string
    {
        if (! $user) {
            return 'public';
        }

        return match ($user->role) {
            'Admin', 'Gold' => 'gold',
            'Platinum' => 'platinum',
            'Member' => 'member',
            default => 'public',
        };
    }

    public function profitPercentForRole(Layanan $layanan, ?User $user): float
    {
        return (float) match ($this->role($user)) {
            'gold' => $layanan->profit_gold ?? $layanan->profit ?? 0,
            'platinum' => $layanan->profit_platinum ?? $layanan->profit ?? 0,
            'member' => $layanan->profit_member ?? $layanan->profit ?? 0,
            default => $layanan->profit ?? 0,
        };
    }

    public function priceForRole(Layanan $layanan, ?User $user): int
    {
        $price = match ($this->role($user)) {
            'gold' => $layanan->harga_gold ?? $layanan->harga ?? 0,
            'platinum' => $layanan->harga_platinum ?? $layanan->harga ?? 0,
            'member' => $layanan->harga_member ?? $layanan->harga ?? 0,
            default => $layanan->harga ?? 0,
        };

        return (int) round((float) $price);
    }

    public function applyMarkup(float|int $modal, float|int $profitPercent): int
    {
        $modal = max(0, (float) $modal);
        $profitPercent = max(0, (float) $profitPercent);
        $calculated = (int) ceil($modal + ($modal * $profitPercent / 100));

        return max($calculated, (int) ceil($modal + $this->minimumProfit()));
    }

    public function estimateModalFromFinalPrice(int|float $finalPrice, int|float $profitPercent): int
    {
        $finalPrice = max(0, (float) $finalPrice);
        $profitPercent = max(0, (float) $profitPercent);

        if ($profitPercent <= 0) {
            return (int) ceil($finalPrice);
        }

        return (int) ceil($finalPrice / (1 + ($profitPercent / 100)));
    }

    public function minimumSafeProductPrice(Layanan $layanan, ?User $user = null, int $qty = 1): int
    {
        $qty = max(1, $qty);
        $rolePrice = $this->priceForRole($layanan, $user);
        $profitPercent = $this->profitPercentForRole($layanan, $user);
        $estimatedModal = $this->estimateModalFromFinalPrice($rolePrice, $profitPercent) * $qty;

        return (int) ceil($estimatedModal + $this->minimumProfit());
    }

    public function clampDiscountedPrice(int|float $currentPrice, Layanan $layanan, ?User $user = null, int $qty = 1): int
    {
        $currentPrice = (int) round(max(0, (float) $currentPrice));
        return max($currentPrice, $this->minimumSafeProductPrice($layanan, $user, $qty));
    }
}
