<?php

namespace App\Observers;

use App\Models\Pembelian;
use Illuminate\Support\Facades\Http;

class PembelianObserver
{
    public function updated(Pembelian $pembelian)
    {
        if (! $pembelian->isDirty('status')) {
            return;
        }

        if (! in_array($pembelian->status, ['Sukses', 'Pending'], true)) {
            return;
        }

        $webhookUrl = env('PEMBELIAN_STATUS_WEBHOOK_URL');
        if (empty($webhookUrl)) {
            return;
        }

        Http::timeout(10)->post($webhookUrl, [
            'order_id' => $pembelian->order_id,
            'status' => $pembelian->status,
        ]);
    }
}
