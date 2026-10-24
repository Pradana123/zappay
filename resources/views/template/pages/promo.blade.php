@extends('template.template')

@section('content')
@include('../navbar')

@php
    $siteName = env('APP_NAME', 'ZAPPAY');
@endphp

<section class="container pt-6 md:pt-16 pb-16">
    <div class="rounded-2xl bg-murky-700 p-6 md:p-10 shadow-lg border border-white/10">
        <p class="text-primary-300 text-sm font-bold mb-2">Promo</p>
        <h1 class="text-2xl md:text-4xl font-extrabold mb-4">Promo {{ $siteName }}</h1>
        <p class="text-sm md:text-base leading-7 text-white/80 max-w-3xl">
            Halaman ini berisi informasi promo, diskon, kode voucher, dan penawaran khusus yang sedang tersedia di {{ $siteName }}.
        </p>

        <div class="mt-8 rounded-xl bg-black/30 p-6 border border-white/10">
            <h2 class="text-xl font-bold mb-2">Belum Ada Promo Aktif</h2>
            <p class="text-sm leading-6 text-white/75">Jika promo sedang tersedia, informasi kode voucher dan syarat penggunaannya akan ditampilkan di halaman ini. Kamu tetap bisa melihat daftar harga terbaru melalui halaman daftar harga.</p>
            <a href="{{ route('price') }}" class="mt-5 inline-flex justify-center rounded-lg bg-primary-400 px-5 py-3 text-sm font-bold text-white hover:opacity-90">Lihat Daftar Harga</a>
        </div>

        <div class="grid md:grid-cols-2 gap-5 mt-6">
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Cara Menggunakan Voucher</h2>
                <p class="text-sm leading-6 text-white/75">Masukkan kode voucher pada halaman checkout jika kolom voucher tersedia. Sistem akan menghitung potongan sesuai ketentuan promo.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Syarat Promo</h2>
                <p class="text-sm leading-6 text-white/75">Promo dapat memiliki batas waktu, minimal transaksi, produk tertentu, atau kuota penggunaan. Pastikan membaca ketentuan promo sebelum checkout.</p>
            </div>
        </div>
    </div>
</section>

@include('../footer')
@endsection
