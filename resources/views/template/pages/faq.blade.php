@extends('template.template')

@section('content')
@include('../navbar')

@php
    $siteName = env('APP_NAME', 'ZAPPAY');
@endphp

<section class="container pt-6 md:pt-16 pb-16">
    <div class="rounded-2xl bg-murky-700 p-6 md:p-10 shadow-lg border border-white/10">
        <p class="text-primary-300 text-sm font-bold mb-2">Pusat Bantuan</p>
        <h1 class="text-2xl md:text-4xl font-extrabold mb-4">FAQ {{ $siteName }}</h1>
        <p class="text-sm md:text-base leading-7 text-white/80 max-w-3xl">Berikut pertanyaan yang sering ditanyakan pembeli saat menggunakan layanan top up dan produk digital di {{ $siteName }}.</p>

        <div class="mt-8 space-y-4">
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Berapa lama pesanan diproses?</h2>
                <p class="text-sm leading-6 text-white/75">Sebagian besar pesanan diproses otomatis setelah pembayaran berhasil. Lama proses bisa berbeda tergantung produk, antrean provider, dan validitas data tujuan.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Kenapa nickname tidak muncul?</h2>
                <p class="text-sm leading-6 text-white/75">Nickname bisa tidak muncul jika User ID atau Zone ID salah, game sedang gangguan, atau pengecekan dari provider sedang tidak tersedia. Periksa kembali data sebelum transaksi.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Apa yang harus dilakukan jika pesanan belum masuk?</h2>
                <p class="text-sm leading-6 text-white/75">Cek status pesanan menggunakan nomor invoice. Jika pembayaran sudah sukses tetapi produk belum masuk, hubungi admin dengan menyertakan nomor pesanan, produk, ID tujuan, dan bukti pembayaran.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Apakah data yang salah bisa dibatalkan?</h2>
                <p class="text-sm leading-6 text-white/75">Pesanan yang sudah terkirim ke data tujuan sesuai input pembeli tidak bisa dibatalkan. Pastikan data tujuan sudah benar sebelum menyelesaikan pembayaran.</p>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('cari') }}" class="inline-flex justify-center rounded-lg bg-primary-400 px-5 py-3 text-sm font-bold text-white hover:opacity-90">Cek Status Pesanan</a>
            <a href="{{ route('zappay.cara-top-up') }}" class="inline-flex justify-center rounded-lg border border-white/20 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">Lihat Cara Top Up</a>
        </div>
    </div>
</section>

@include('../footer')
@endsection
