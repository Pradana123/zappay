@extends('template.template')
@section('content')
  @include('../navbar')
  <section id="history">
    <div class="space-y-10">
      <div class="relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 z-10 bg-transparent"></div>
        <div class="container relative z-20 py-12 ">
          <img src="{{ url('') }}{{ !$config ? '' : $config->logo_header }}" width="100" height="100" class="mx-auto h-32 w-auto" style="color: transparent;" alt="Logo">
          <h2 class="mx-auto max-w-2xl text-center text-3xl font-bold tracking-tight text-white sm:text-4xl"> Leaderboard!</h2>
          <div class="mt-6 mx-auto max-w-2xl text-center">
            <span class="text-baser">Reseller, saatnya jadi juara! Raih posisi Top 10 di leaderboard Top Up sebanyak mungkin dan dapatkan hadiah menarik! Tingkatkan penjualanmu sekarang!</span>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="leaderboard" class="relative pb-12">
        <div class="container">
            <div
                class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 md:grid-cols-2 lg:mx-0 lg:max-w-none xl:grid-cols-3">
                <div>
                    <div class="flex justify-center">
                        <h2 class="ml-3 inline-flex rounded-md text-center bg-murky-700 shadow py-1 px-4 text-xs leading-6">
                            Top 10 - Hari Ini</h2>
                    </div>
                    <div class="relative rounded-md bg-murky-800 p-6">
                        <ul class="space-y-3 text-sm leading-6 text-white">
                            @foreach ($top10Today as $index => $item)
    @if (isset($item->name))
        <li class="flex items-center justify-between gap-x-3">
            <div>{{ $index + 1 }}. {{ $item->name }} 
                @if ($index == 0) 🏆 
                @elseif($index == 1) 🥇 
                @elseif($index == 2) 🥈 
                @endif 
            </div>
            <div>Rp&nbsp;{{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</div>
        </li>
    @endif
@endforeach
                        </ul>
                    </div>
                </div>
                <div>
                    <div class="flex justify-center">
                        <h2 class="ml-3 inline-flex rounded-md text-center bg-murky-700 shadow py-1 px-4 text-xs leading-6">
                            Top 10 - Minggu Ini</h2>
                    </div>
                    <div class="relative rounded-md bg-murky-800 p-6">
                        <ul class="space-y-3 text-sm leading-6 text-white">
                            @foreach($top10ThisWeek as $index => $item)
    @if (isset($item->name))
        <li class="flex items-center justify-between gap-x-3">
            <div>{{ $index + 1 }}. {{ $item->name }} 
                @if ($index == 0) 🏆 
                @elseif($index == 1) 🥇 
                @elseif($index == 2) 🥈 
                @endif 
            </div>
            <div>Rp&nbsp;{{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</div>
        </li>
    @endif
@endforeach
                        </ul>
                    </div>
                </div>
                <div>
                    <div class="flex justify-center">
                        <h2 class="ml-3 inline-flex rounded-md text-center bg-murky-700 shadow py-1 px-4 text-xs leading-6">
                            Top 10 - Bulan Ini</h2>
                    </div>
                    <div class="relative rounded-md bg-murky-800 p-6 ">
                        <ul class="space-y-3 text-sm leading-6 text-white">
                            @foreach ($top10ThisMonth as $index => $item)
    @if (isset($item->name))
        <li class="flex items-center justify-between gap-x-3">
            <div>{{ $index + 1 }}. {{ $item->name }} 
                @if ($index == 0) 🏆 
                @elseif($index == 1) 🥇 
                @elseif($index == 2) 🥈 
                @endif 
            </div>
            <div>Rp&nbsp;{{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</div>
        </li>
    @endif
@endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('../footer')
@endsection
