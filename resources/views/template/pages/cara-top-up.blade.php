@extends('template.template')

@section('content')
@include('../navbar')

@php
    $siteName = env('APP_NAME', 'ZAPPAY');
@endphp

<section class="container pt-6 md:pt-16 pb-16">
    <div class="rounded-2xl bg-murky-700 p-6 md:p-10 shadow-lg border border-white/10">
        <div class="max-w-3xl">
            <p class="text-primary-300 text-sm font-bold mb-2">Panduan Transaksi</p>
            <h1 class="text-2xl md:text-4xl font-extrabold mb-4">Cara Top Up di {{ $siteName }}</h1>
            <p class="text-sm md:text-base leading-7 text-white/80">
                Ikuti langkah berikut agar pesanan diproses dengan benar. Pastikan ID game, nomor tujuan, atau data akun yang kamu masukkan sudah sesuai sebelum melakukan pembayaran.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-5 mt-8">
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <div class="text-primary-300 text-xl font-black mb-2">01</div>
                <h2 class="text-lg font-bold mb-2">Pilih Produk</h2>
                <p class="text-sm leading-6 text-white/75">Buka halaman beranda, lalu pilih kategori produk seperti Mobile Legends, Free Fire, Honor of Kings, PUBG Mobile, pulsa, paket data, atau voucher digital.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <div class="text-primary-300 text-xl font-black mb-2">02</div>
                <h2 class="text-lg font-bold mb-2">Masukkan Data Tujuan</h2>
                <p class="text-sm leading-6 text-white/75">Isi User ID, Zone ID, nomor HP, atau data tujuan lain sesuai format produk. Untuk beberapa game, sistem akan menampilkan nickname sebagai pengecekan awal.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <div class="text-primary-300 text-xl font-black mb-2">03</div>
                <h2 class="text-lg font-bold mb-2">Pilih Nominal dan Pembayaran</h2>
                <p class="text-sm leading-6 text-white/75">Pilih item yang ingin dibeli, lalu pilih metode pembayaran yang tersedia. Periksa ulang ringkasan pesanan sebelum lanjut.</p>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <div class="text-primary-300 text-xl font-black mb-2">04</div>
                <h2 class="text-lg font-bold mb-2">Bayar dan Tunggu Proses</h2>
                <p class="text-sm leading-6 text-white/75">Setelah pembayaran berhasil, sistem akan memproses pesanan secara otomatis. Simpan nomor pesanan untuk pengecekan status atau komplain.</p>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('home') }}" class="inline-flex justify-center rounded-lg bg-primary-400 px-5 py-3 text-sm font-bold text-white hover:opacity-90">Mulai Top Up</a>
            <a href="{{ route('cari') }}" class="inline-flex justify-center rounded-lg border border-white/20 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">Cek Pesanan</a>
        </div>
    </div>
</section>

@include('../footer')
@endsection
