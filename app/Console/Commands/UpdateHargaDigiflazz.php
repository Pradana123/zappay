<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DigiFlazzController;
use App\Models\Produk;

class UpdateHargaDigiflazz extends Command
{
    protected $signature = 'update:harga-digiflazz';
    protected $description = 'Update harga dari Digiflazz';

    public function handle()
    {
        $digiflazz = new DigiFlazzController();
        $response = $digiflazz->harga();

        foreach ($response['data'] as $produk) {
            Produk::where('sku', $produk['sku'])->update([
                'price' => $produk['price'],
            ]);
        }
    }
}
