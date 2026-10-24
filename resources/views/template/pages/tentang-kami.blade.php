@extends('template.template')

@section('content')
@include('../navbar')

@php
    $siteName = env('APP_NAME', 'ZAPPAY');
@endphp

<section class="container pt-6 md:pt-16 pb-16">
    <div class="rounded-2xl bg-murky-700 p-6 md:p-10 shadow-lg border border-white/10">
        <p class="text-primary-300 text-sm font-bold mb-2">Tentang Kami</p>
        <h1 class="text-2xl md:text-4xl font-extrabold mb-4">Tentang {{ $siteName }}</h1>
        <p class="text-sm md:text-base leading-7 text-white/80 max-w-4xl">
            {{ $siteName }} adalah platform top up game, voucher, pulsa, paket data, dan produk digital yang dirancang untuk membantu pelanggan bertransaksi secara cepat, aman, dan praktis. Layanan ini berfokus pada kemudahan pemesanan, pilihan metode pembayaran, serta dukungan pelanggan saat transaksi membutuhkan bantuan.
        </p>

        <div class="grid md:grid-cols-3 gap-5 mt-8">
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="text-lg font-bold mb-2">Cepat</h2>
                <p class="text-sm leading-6 text-white/75">Proses pemesanan dibuat sederhana agar pelanggan bisa menyelesaikan transaksi tanpa langkah yang rumit.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="text-lg font-bold mb-2">Aman</h2>
                <p class="text-sm leading-6 text-white/75">Setiap transaksi memiliki nomor pesanan sehingga status pembayaran dan proses pesanan dapat dilacak.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="text-lg font-bold mb-2">Responsif</h2>
                <p class="text-sm leading-6 text-white/75">Admin siap membantu jika pelanggan mengalami kendala pembayaran, data tujuan, atau pesanan yang belum selesai.</p>
            </div>
        </div>

        <div class="mt-8 rounded-xl bg-black/30 p-5 border border-white/10">
            <h2 class="text-lg font-bold mb-2">Catatan Penting</h2>
            <p class="text-sm leading-6 text-white/75">Semua merek, logo, dan nama game adalah milik masing-masing pemiliknya. {{ $siteName }} tidak berafiliasi langsung dengan penerbit game kecuali dinyatakan secara resmi.</p>
        </div>
    </div>
</section>

@include('../footer')
@endsection
