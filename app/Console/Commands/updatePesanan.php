<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Http\Controllers\digiFlazzController;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class updatePesanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatePesanan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pesanan = Pembelian::whereIn('status', ['Sukses'])
                             ->latest()
                             ->get();
        
        $digi = new digiFlazzController();

        foreach ($pesanan as $data) {
            $pembelian = DB::table('pembelians')->where('id', $data->pembelian_id)->first();

            if (!$pembelian || !$pembelian->provider_order_id) {
                Log::error("Missing provider_order_id for status check", ['data' => $data]);
                continue;
            }

            $provider_order_id = $pembelian->provider_order_id;
            $layanan = Layanan::find($data->layanan_id); 
            if (!$layanan) {
                Log::error("Layanan not found for data ID: " . $data->layanan_id);
                continue;
            }
            $product_id = strval($layanan->id);
            $user_id = strval($data->user_id);
            $zone = strval($data->zone);

            try {
                $checking = $digi->status($provider_order_id, $product_id, $user_id, $zone);

                if (isset($checking['data'])) {
                    $status_order = $checking['data']['order_status'] ?? null;

                    if (!$status_order) {
                        Log::error("Error: 'order_status' not found in response", ['response' => $checking]);
                        continue;
                    }

                    if ($status_order == "completed" || $status_order == "success") {
                        $status_check = true;
                        $status_pembelian = "Sukses";
                    } elseif (in_array($status_order, ["Gagal", "Error", "error", "cancel", "canceled"])) {
                        $status_check = true;
                        $status_pembelian = "Batal";
                    } else {
                        $status_check = false;
                    }

                    $post_data = [
                        'data' => [
                            'ref_id' => $data->order_id,
                            'code' => strval($layanan->id),
                            'hp' => $data->user_id . $data->zone,
                            'price' => $data->harga,
                            'balance' => "100000000",
                            'tr_id' => $data->order_id,
                        ]
                    ];

                    if ($status_check) {
                        if ($status_pembelian == "Sukses") {
                            $post_data['data']['message'] = $status_pembelian;
                            $post_data['data']['sn'] = $data->order_id . "/" . $status_pembelian;
                            $post_data['data']['status'] = "1";
                            $post_data['data']['rc'] = "00";

                            Pembelian::where('provider_order_id', $provider_order_id)
                                ->update(['status' => $status_pembelian]);
                        } elseif ($status_pembelian == "Batal") {
                            $post_data['data']['message'] = $status_pembelian;
                            $post_data['data']['sn'] = $data->order_id . "/" . $status_pembelian;
                            $post_data['data']['status'] = "1";
                            $post_data['data']['rc'] = "00";

                            Pembelian::where('provider_order_id', $provider_order_id)
                                ->update(['status' => "Error", 'log' => json_encode($checking)]);
                        } else {
                            Pembelian::where('provider_order_id', $provider_order_id)
                                ->update(['status' => $status_pembelian, 'log' => json_encode($checking)]);
                        }

                        // Panggil API callback
                        $response = Http::post('https://api.digiflazz.com/v1/seller/callback', $post_data);
                        if (!$response->successful()) {
                            Log::error("Callback API request failed", ['response' => $response->body()]);
                        }
                    } else {
                        Log::info("No status update needed for provider_order_id: $provider_order_id");
                        continue;
                    }
                } else {
                    Log::error("Invalid response: 'data' not found", ['response' => $checking]);
                }
            } catch (\Exception $e) {
                Log::error("Error updating order: " . $e->getMessage());
                continue;
            }
        }

        \DB::disconnect('mysql');
    }
}
