@extends('template.template')

@section('custom_style')
@endsection


@section('content')
    @include('../navbar')


    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}', 'Error');
        </script>
    @endif

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', 'Error');
            @endforeach
        </script>
    @endif

  <section id="history">
    <div class="space-y-10">
      <div class="relative overflow-hidden bg-murky-900 shadow-2xl">
        <div class="absolute inset-0 z-10 bg-gradient-to-r from-murky-800 via-murky-800/75 to-murky-800/75 sm:to-transparent"></div>
        <form action="{{ route('cari.post') }}" method="POST" class="container relative z-20 py-12">
          @csrf
    <img src="{{ url('') }}{{ !$config ? '' : $config->logo_header }}" width="100" height="100" class="mx-auto h-32 w-auto" style="color: transparent;" alt="ZAPPAY." />
    <h2 class="mx-auto max-w-2xl text-center text-3xl font-bold tracking-tight text-white sm:text-4xl">Cari pesanan kamu!</h2>
                    <p class="mt-6 mx-auto max-w-2xl text-center">Lacak transaksi kamu dengan cara memasukkan Nomor Invoice
                        dibawah ini:</p>
                    <div class="mt-6 mx-auto max-w-2xl text-center">
                        <label for="invoice" class="block text-xs font-medium text-white mb-2 text-left">Masukan Nomor
                            Invoice Kamu</label>
                        <div class="flex flex-col items-start">
                            <input
                                class="relative block w-full appearance-none border border-murky-600 bg-melpa-800 px-3 py-2 text-xs text-white placeholder-murky-200 focus:z-10 focus:border-primary-500 focus:outline-none focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-75 rounded-md"
                                type="text" placeholder="BGRLxxxxxxxx" name="id" required>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-center gap-x-6 ">
                        <button
                            class="inline-flex items-center justify-center rounded-md bg-primary-500 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-primary-400 disabled:cursor-not-allowed disabled:opacity-75 space-x-2 "
                            type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor"
                                aria-hidden="true" class="h-5 w-5">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd"></path>
                            </svg><span>Cari Transaksi</span></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="container max-w-2xl text-center mt-5">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-white ">10 Transaksi Terakhir</h1>
            <p class="mt-2 max-w-2xl text-sm text-white">
                Ini adalah 10 transaksi terakhir dari semua pengguna. Informasi yang tersedia mencakup tanggal transaksi,
                kode invoice, layanan, price, dan status.
            </p>
        </div>
    </div>

    <div class="container">
        <div class="space-y-4">
            <div class="-mx-4 overflow-x-auto ring-1 ring-murky-600 sm:mx-0 sm:rounded-lg mt-5">
                <table class="min-w-full divide-y divide-murky-600">
                    <thead>
                        <tr>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                <div class="">Date</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Invoice</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Layanan</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                <div class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Price</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                <div class="">Status</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="data-body">
                        @forelse ($pembelians as $pembelian)
                            @php
                                $status = $pembelian->status;
                                if ($status == 'Success' || $status == 'Sukses') {
                                    $label = 'emerald-200';
                                    $status = 'Success';
                                } elseif ($status == 'Pending' || $status == 'pending') {
                                    $label = 'yellow-300';
                                } elseif ($status == 'Proses' || $status == 'Process') {
                                    $label = 'sky-600';
                                    $status = 'Process';
                                } elseif ($status == 'Expired') {
                                    $label = 'rose-300';
                                    $status = 'Expired';
                                } elseif ($status == 'Batal' || $status == 'Gagal') {
                                    $label = 'rose-300';
                                    $status = 'Cancelled';
                                } else {
                                    $label = 'rose-300';
                                }
                            @endphp
                            <tr class="table-row">
                                <td
                                    class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">{{ $pembelian->created_at }}</div>
                                </td>
                                <td
                                    class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">
                                        {{ substr($pembelian->order_id, 0, 2) . '*******' . substr($pembelian->order_id, -3) }}
                                    </div>
                                </td>
                                <td
                                    class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">{{ $pembelian->layanan }}</div>
                                </td>
                                <td
                                    class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">
                                        <span>Rp&nbsp;{{ number_format($pembelian->harga, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td
                                    class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&:nth-last-child(2)]:table-cell">
                                    <div class="whitespace-nowrap">
                                        @if ($status == 'Progress' || $status == 'Process')
                                            <span
                                                class="inline-flex rounded-xl px-2 text-xs font-semibold leading-5 print:p-0 bg-{{ $label }} text-white">{{ strtoupper($status) }}</span>
                                        @elseif ($status == 'Expired')
                                            <span
                                                class="inline-flex rounded-xl px-2 text-xs font-semibold leading-5 print:p-0 bg-{{ $label }} text-gray-800">{{ strtoupper($status) }}</span>
                                        @elseif ($status == 'Batal' || $status == 'Cancelled')
                                            <span
                                                class="inline-flex rounded-xl px-2 text-xs font-semibold leading-5 print:p-0 bg-{{ $label }} text-rose-800">{{ strtoupper($status) }}</span>
                                        @else
                                            <span
                                                class="inline-flex rounded-xl px-2 text-xs font-semibold leading-5 print:p-0 bg-{{ $label }} text-emerald-900">{{ strtoupper($status) }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24 px-4 text-center sm:px-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                        class="mx-auto h-12 w-12 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 font-semibold text-white">Data tidak ditemukan!</h3>
                                    <p class="mt-1 text-sm text-murky-300">Tidak ada aktifitasi data.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('../footer')
    @push('custom_script')
    @endpush
@endsection
