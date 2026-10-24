<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembelian;
use App\Models\Layanan;

class sendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function sendReport()
    {
        $orders = Pembelian::where('is_digi', 1)
            ->where('success_report_sended', 0)
            ->whereIn('status', ['Pending', 'Sukses'])
            ->get();

        info('Orders retrieved:', ['count' => $orders->count()]);

        foreach ($orders as $order) {
            info('Processing order:', ['order_id' => $order->order_id, 'status' => $order->status]);

            $layanan = Layanan::where('layanan', $order->layanan)->first();
            if (!$layanan) {
                info('Layanan not found for order:', ['order_id' => $order->order_id, 'layanan' => $order->layanan]);
                continue; // Skip this order if layanan is not found
            }

            $post_data = array(
                'data' => array(
                    'ref_id' => $order->ref_id,
                    'code' => strval($layanan->id),
                    'hp' => strval($order->user_id) . strval($order->zone),
                    'price' => strval($order->harga),
                    'balance' => '0',
                    'tr_id' => $order->order_id,
                     'sn' => $this->generateSn($order->ref_id), // Menggunakan fungsi generateSn
                ),
            );

            // Tentukan status dan RC berdasarkan status order
            if ($order->status === 'Pending') {
                $post_data['data']['status'] = '0';
                $post_data['data']['rc'] = '39';
                $post_data['data']['message'] = 'Pending';
            } elseif ($order->status === 'Sukses') {
                $post_data['data']['status'] = '1';
                $post_data['data']['rc'] = '00';
                $post_data['data']['message'] = 'Sukses';
            } else {
                $post_data['data']['status'] = '2';
                $post_data['data']['rc'] = '06';
            }

            info('Post data prepared:', $post_data);

            // Kirim callback ke DigiFlazz
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.digiflazz.com/v1/seller/callback');
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            $exec = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            info('Curl executed:', ['response' => $exec, 'http_code' => $http_code]);

            // Jika berhasil mengirimkan callback untuk status sukses, tandai success_report_sended
            if ($http_code === 200 && $order->status === 'Sukses') {
                $update = Pembelian::where('order_id', $order->order_id)->update([
                    'success_report_sended' => 1,
                ]);
                info('Order updated:', ['order_id' => $order->order_id, 'success_report_sended' => $update]);
            }
        }

        info('sendReport execution completed.');
        return response()->json(['message' => 'Report command has been executed.']);
    }
    
    private function generateSn($refId)
    {
        $date = now(); // Menggunakan tanggal saat ini
        $year = $date->format('y'); // 2 digit tahun
        $month = $date->format('m'); // 2 digit bulan
        $day = $date->format('d'); // 2 digit tanggal
        $randomNumber = str_pad(rand(0, 999999), 9, '0', STR_PAD_LEFT); // Nomor acak 6 digit
        $randomLetters = strtoupper(Str::random(3)); // 5 huruf acak kapital
    
        return "Success/RefId : S{$year}{$month}{$day}{$randomNumber}{$randomLetters}";
    }
}