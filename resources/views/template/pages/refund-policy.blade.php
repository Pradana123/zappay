@extends('template.template')

@section('content')
@include('../navbar')

@php
    $siteName = env('APP_NAME', 'ZAPPAY');
@endphp

<section class="container pt-6 md:pt-16 pb-16">
    <div class="rounded-2xl bg-murky-700 p-6 md:p-10 shadow-lg border border-white/10">
        <p class="text-primary-300 text-sm font-bold mb-2">Kebijakan</p>
        <h1 class="text-2xl md:text-4xl font-extrabold mb-4">Kebijakan Refund</h1>
        <p class="text-sm md:text-base leading-7 text-white/80 max-w-4xl">
            Kebijakan refund ini berlaku untuk transaksi di {{ $siteName }}. Pengembalian dana hanya dapat diproses pada kondisi tertentu dan memerlukan pengecekan status pembayaran serta status pesanan.
        </p>

        <div class="mt-8 space-y-4">
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Refund Dapat Diproses Jika</h2>
                <ul class="list-disc ml-5 text-sm leading-7 text-white/75">
                    <li>Pembayaran berhasil, tetapi pesanan gagal diproses oleh sistem atau provider.</li>
                    <li>Produk kosong atau tidak tersedia setelah pembayaran berhasil.</li>
                    <li>Terjadi gangguan sistem yang menyebabkan pesanan tidak dapat diselesaikan.</li>
                </ul>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Refund Tidak Berlaku Jika</h2>
                <ul class="list-disc ml-5 text-sm leading-7 text-white/75">
                    <li>Pembeli salah memasukkan User ID, Zone ID, nomor tujuan, atau data akun.</li>
                    <li>Pesanan sudah berhasil terkirim ke data tujuan yang diinput pembeli.</li>
                    <li>Pembeli membayar di luar metode pembayaran resmi yang tersedia di website.</li>
                </ul>
            </div>
            <div class="rounded-xl bg-black/30 p-5 border border-white/10">
                <h2 class="font-bold mb-2">Cara Mengajukan Refund</h2>
                <p class="text-sm leading-6 text-white/75">Hubungi admin dan sertakan nomor pesanan, produk, metode pembayaran, ID atau nomor tujuan, serta bukti pembayaran. Admin akan melakukan pengecekan sebelum memberikan keputusan refund.</p>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('cari') }}" class="inline-flex justify-center rounded-lg bg-primary-400 px-5 py-3 text-sm font-bold text-white hover:opacity-90">Cek Pesanan</a>
            <a href="{{ route('terms') }}" class="inline-flex justify-center rounded-lg border border-white/20 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">Baca Syarat &amp; Ketentuan</a>
        </div>
    </div>
</section>

@include('../footer')
@endsection
