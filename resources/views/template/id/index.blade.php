@extends('template.template')

@section('custom_style')
    <style>
        body {
            font-family: 'IBM Plex Sans Condensed', sans-serif;
        }
    </style>
@endsection

@section('content')
    @include('../navbar')
    <section class="relative mb-4 bg-transparent py-4 shadow-2xl">
        <div id="hero" class="hero-swiper swiper container swiper-initialized swiper-horizontal swiper-backface-hidden">
            <div id="heroo" class="swiper-wrapper" aria-live="off">
                @foreach ($banner as $key => $data)
                    <div class="swiper-slide swiper-slide-active swiper-slide-next" style="width: 470px; margin-right: 64px;"
                        role="group" aria-label="1 / 1" data-swiper-slide-index="0">
                        <img src="{{ $data->path }}" class="w-full h-auto object-cover rounded-3xl" alt="ZAPPAY" />
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="hidden items-center justify-end space-x-2 md:flex"></div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>
            </div>
        </div>
        <div class="absolute inset-0 -z-10">
            <div class="area"></div>
        </div>
    </section>

    <div class="flex flex-col gap-y-8 pb-8 pt-4 sm:p-0 md:pt-8">
        @if ($flashsale->count() > 0)
            <div id="fs" class="container ">                
                <div class="rounded-2xl bg-muted/50">
                <div class="px-4 pb-3 pt-4">
                    <h3 class="flex items-center space-x-4 text-foreground">
                         <div class="text-lg font-semibold uppercase leading-relaxed tracking-wider flex items-center">
                        <lottie-player 
                            src="https://lottie.host/72527c22-6566-4eda-b453-dc61dd77ef2b/rt3d8phYjG.json" 
                            speed="1" 
                            style="width: 25px; height: 30px;" 
                            loop 
                            autoplay 
                            direction="1" 
                            mode="normal">
                        </lottie-player>
                        P R O M O</div>
                            <div class="flex items-center gap-1 text-sm capitalize">
                                <div class="fs-countdown">

                                    <div class="time" id="days"></div>
                                    <div class="separator">:</div>
                                    <div class="time" id="hours"></div>
                                    <div class="separator">:</div>
                                    <div class="time" id="minutes"></div>
                                    <div class="separator">:</div>
                                    <div class="time" id="seconds"></div>
                                </div>
                            </div>
                        </h3>
                        <p class="pl-6 text-xs text-foreground">Jangan lewatkan penawaran terbatas kami!</p>
                    </div>
                    <div class="relative flex h-full w-full flex-col items-center justify-center overflow-hidden pb-2 pt-1"> 
    <div class="group flex overflow-hidden p-2 [--gap:1rem] [gap:var(--gap)] flex-row container [--duration:20s]"> 
        <div data-run-marquee="true" data-run-marquee-vertical="false" class="flex shrink-0 justify-around [gap:var(--gap)] data-[run-marquee-vertical=true]:animate-marquee-vertical data-[run-marquee=true]:animate-marquee data-[run-marquee]:flex-row data-[run-marquee-vertical=true]:flex-col group-hover:[animation-play-state:paused]"> 
            <div class=" flex"> 
                <div class="assdafsdvsvasgdsgsdgwgreragwgwrgeargwrgergegsvdsDVSVcsdvdszvsbwtergerg43t34f34343ff34g34gG2"> 
                    <div id="special_deals"> 
                        <div class="list swiper-wrapper marquee-content"> 
                            @for ($i = 0; $i < 5; $i++) 
                                @foreach ($flashsale as $fs) 
                                    @php 
                                        $discount = round( (($fs->harga - $fs->harga_flash_sale) / $fs->harga) * 100, ); 
                                    @endphp 
                                    <a class="swiper-slide-link" href="{{ url('/id') }}/{{ $fs->kode_game }}"> 
                                        <div class="item relative" data-item-theme="0722"> 
                                            <img alt="" class="flash-sale-img lazyloaded rounded-lg" src="{{ $fs->thumbnail ?? $fs->gmr_thumb }}" /> 
                                            <div class="T truncatee"> 
                                                <div class="text-xs font-medium text-sm">{{ $fs->kategori->nama_display }}</div> 
                                                <h2 class="sku text-white text-xs"> 
                                                    <figcaption class="text-xs font-medium text-foreground">{{ $fs->layanan }} </figcaption> 
                                                </h2> 
                                                <p class="text-xs font-medium text-destructive line-through red-line-through">Rp. {{ number_format($fs->harga, 0, '.', ',') }}</p>
                                                        <p class="text-xs font-medium text-primary">Rp. {{ number_format($fs->harga_flash_sale, 0, '.', ',') }}</p>
                                            </div> 
                                        
                                        <div class="w-14 absolute aspect-square -top-[9px] -right-[9px] overflow-hidden rounded-sm">
    <div class="absolute block w-square-diagonal py-0.5 text-center text-xxs font-semibold uppercase bottom-0 right-0 rotate-45 origin-bottom-right shadow-sm bg-hemat text-primary-foreground" style="font-size: 8px;">
        DISC {{ number_format((($fs->harga - $fs->harga_flash_sale) / $fs->harga) * 100, 0) }}%</div>
</div>
</div>
                                    </a> 
                                @endforeach 
                            @endfor 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
</div>
</div>
@endif

        
        <div id="content-melpa" class="container">
            <div class="mb-5">
                <h3 class="text-lg font-semibold leading-relaxed tracking-wider flex" >
        <lottie-player 
            src="https://lottie.host/105ce5c3-7e93-4dbc-bfc8-6e4c816320e5/8kDtYqEr0W.json" 
            speed="1" 
            style="width: 25px; height: 25px;" 
            loop 
            autoplay 
            direction="1" 
            mode="normal">
        </lottie-player>
        POPULER SEKARANG!
                </h3>
                <p class="pl-6 text-xs">Beberapa produk yang paling populer saat ini.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 md:gap-4 lg:grid-cols-3 mt-3">
                @foreach ($kategori as $category)
                    @if ($category->tipe == 'populer')
                        <a href="{{ url('/id') }}/{{ $category->kode }}" class="melpaSlideUp" style="animation-delay: 0s;">
                            <div
                                class="group category-item bg-title-product flex items-center gap-x-1.5 rounded-2xl bg-murky-600 p-1.5 duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 md:gap-x-3 md:rounded-2xl md:p-3">
                                
                                    <img src="{{ $category->thumbnail }}"
                                    class="aspect-square h-14 w-14 rounded-lg !object-cover !object-center ring-1 ring-murky-600 md:h-20 md:w-20 md:rounded-xl"
                                    alt="{{ $category->kode }}">
                                <div class="relative flex w-full flex-col">
                                    <h2 class="w-[100px] truncate text-xxs font-semibold sm:w-[200px] md:w-[275px] md:text-base"> {{ $category->nama_display }}
                                    </h2>
                                    <p class="text-xxs md:text-sm">{{ $category->sub_nama }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <section id="mobile-game" class="relative w-full overflow-hidden pb-6 md:pb-8 lg:pb-10 bg-secondary-950 ">
            <div class="container mx-auto">
                <!-- Nav Product -->
                <div class="flex items-center gap-2">
                    <div class="block lg:hidden"><button id="scrollLeft"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-card text-primary-foreground hover:bg-primary/90 h-9 w-9"
                            type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-chevron-left h-4 w-4">
                                <path d="m15 18-6-6 6-6"></path>
                            </svg></button></div>
                                                    
                    <div class="tabs-container flex overflow-x-auto scroll-smooth"><button
                            class="tab-button whitespace-nowrap melpazoom rounded-none border border-secondary-600 px-3 py-2 text-sm text-text-color shadow-xl outline-none duration-300 hover:bg-secondary-500 focus:bg-secondary-500 focus-visible:bg-secondary-500 bg-transparent active" style="border-radius: 9px;"
                            id="headlessui-tabs-tab-:game:" role="tab" type="button" aria-selected="false"
                            tabindex="0" data-headlessui-state="false" aria-controls="headlessui-tabs-panel-:game:"
                            data-tabs-toggle="#headlessui-tabs-panel-game"> Topup Games </button>
                            <button
                            class="tab-button whitespace-nowrap melpazoom rounded-none border border-secondary-600 px-3 py-2 text-sm text-text-color shadow-xl outline-none duration-300 hover:bg-secondary-500 focus:bg-secondary-500 focus-visible:bg-secondary-500 bg-transparent active" style="border-radius: 9px;"
                            id="headlessui-tabs-tab-:voucher2:" role="tab" type="button" aria-selected="false"
                            tabindex="0" data-headlessui-state="false" aria-controls="headlessui-tabs-panel-:voucher2:"
                            data-tabs-toggle="#headlessui-tabs-panel-voucher2"> E-Wallet </button>
                            <button
                            class="tab-button whitespace-nowrap melpazoom rounded-none border border-secondary-600 px-3 py-2 text-sm text-text-color shadow-xl outline-none duration-300 hover:bg-secondary-500 focus:bg-secondary-500 focus-visible:bg-secondary-500 bg-transparent active" style="border-radius: 9px;"
                            id="headlessui-tabs-tab-:pulsa:" role="tab" type="button" aria-selected="false"
                            tabindex="0" data-headlessui-state="false" aria-controls="headlessui-tabs-panel-:pulsa:"
                            data-tabs-toggle="#headlessui-tabs-panel-pulsa"> Pulsa & Data </button></div>
                    <div class="block lg:hidden"><button id="scrollRight"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-card text-primary-foreground hover:bg-primary/90 h-9 w-9"
                            type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-chevron-right h-4 w-4">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg></button></div>
                </div>
                <!-- Nav Product End -->
                <div class="my-8">

                    <!--                                <div id="headlessui-tabs-panel-mobilelegends" role="tabpanel"  aria-labelledby="headlessui-tabs-tab-mobilelegends" data-tabs-content style="display: none;">-->

                    <!--<div id="filterProductss" class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6 hidden">-->
                    <!--    @foreach ($kategori as $item)
    -->
                    <!--        @if ($item->tipe == 'giftskin' || $item->tipe == 'joki' || $item->tipe == 'jokigendong' || $item->tipe == 'vilogml')
    -->
                    <!--            <a-->
                    <!--                class="group relative transform overflow-hidden rounded-2xl  bg-murky-700 duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900 category-item"-->
                    <!--                href="{{ url('/id/' . $item->kode) }}"-->
                    <!--                style="outline: none;"-->
                    <!--            >-->
                    <!--                  <div class="blur-sharp">-->
                    <!--                <img-->
                    <!--                    alt="{{ $item->nama }}"-->
                    <!--                    fetchpriority="high"-->
                    <!--                    width="192"-->
                    <!--                    height="288"-->
                    <!--                    decoding="async"-->
                    <!--                    data-nimg="1"-->
                    <!--                    class="aspect-[4/6] object-cover object-center"-->
                    <!--                    sizes="100vw"-->
                    <!--                    src="{{ $item->thumbnail }}"-->
                    <!--                    style="color: transparent;"-->
                    <!--                />-->

                    <!--                </div>-->
                    <!--                <article class="absolute inset-x-0 -bottom-10 z-10 flex transform flex-col px-3 transition-all duration-300 ease-in-out group-hover:bottom-3 sm:px-4 group-hover:sm:bottom-4">-->
                    <!--                    <h2 class="truncate text-sm font-semibold text-murky-200 sm:text-base">{{ $item->nama }}</h2>-->
                    <!--                    <p class="truncate text-xxs text-murky-400 sm:text-xs">{{ $item->sub_nama }}</p>-->
                    <!--                </article>-->
                    <!--                <div class="absolute inset-0 transform bg-gradient-to-t from-transparent transition-all duration-300 group-hover:from-murky-900">-->
                    <!--                    <div class="mdigital-item-container"></div>-->
                    <!--                </div>-->
                    <!--            </a>-->
                    <!--
    @endif-->
                    <!--
    @endforeach-->
                    <!--</div>-->

                    <!--                                </div>-->
                    <div id="headlessui-tabs-panel-game" role="tabpanel" aria-labelledby="headlessui-tabs-tab-game"
                        data-tabs-content style="display: none;">
                        <div class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6"
                            id="game">
                            @foreach ($kategori as $category)
                                @if ($category->tipe == 'game' || $category->tipe == 'populer')
                                    <a href="{{ url('/id/' . $category->kode) }}" class="melpazoom">
                                        <div class="group category-item relative overflow-hidden rounded-xl bg-muted transition-transform duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900"
                                            style="display: block;">
                                            
                                            <div class="w-full aspect-square"><img alt="{{ $category->nama_display }}" fetchpriority="high" decoding="async" data-nimg="1"
                                                    class="h-full w-full object-cover object-center"
                                                    src="{{ $category->thumbnail }}"
                                                    src="{{ $category->thumbnail }}" style="color: transparent;"></div>
                                            <div class="bg-murky-900 py-2">
                                                <div class="flex flex-col px-3 py-1">
                                                    <h2 class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $category->nama_display }}</h2>
                                                    <p class="truncate text-xxs text-foreground sm:text-xs">{{ $category->sub_nama }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                            
                                    <style>
                                        .category-item:hover {
                                            transform: rotate(3deg);
                                        }
                                    </style>
                                @endif
                            @endforeach
                        </div>


                        <div class="text-center mt-3" id="buttonContainer2">
                            <div class="justify-content-center">
                                <button
                                    class="inline-flex items-center justify-center melpazoom rounded-md bg-primary-500 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-murky-800/75 disabled:cursor-not-allowed disabled:opacity-75 lg:border lg:border-murky-800/75 lg:bg-transparent lg:hover:bg-murky-800"
                                    id="showAllButton2" aria-label="Tampilkan Lainnya" type="button">Tampilkan
                                    Lainnya...</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            const panelTopup = document.getElementById("game");
                            const items = panelTopup.querySelectorAll(".category-item");
                            const showAllButton = document.getElementById("showAllButton2");
                            const buttonContainer = document.getElementById("buttonContainer2");

                            let visibleItems = 12;

                            function updateVisibility() {
                                items.forEach((item, index) => {
                                    item.style.display = index < visibleItems ? "block" : "none";
                                });

                                if (items.length <= 12) {
                                    buttonContainer.style.display = "none";
                                } else {
                                    buttonContainer.style.display = visibleItems >= items.length ? "none" : "block";
                                }
                            }

                            updateVisibility();

                            showAllButton.addEventListener("click", () => {
                                visibleItems += 12;
                                updateVisibility();
                            });
                        });
                    </script>

                    <div id="headlessui-tabs-panel-mobilelegends" role="tabpanel"
                        aria-labelledby="headlessui-tabs-tab-mobilelegends" data-tabs-content style="display: none;">
                        <div
                            class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6 ">
                            @foreach ($kategori as $category)
                                @if (
                                    $category->tipe == 'giftskin' ||
                                        $category->tipe == 'joki' ||
                                        $category->tipe == 'jokigendong' ||
                                        $category->tipe == 'vilogml')
                                    <a href="{{ url('/id/' . $category->kode) }}" class="melpazoom">
                                        <div class="group category-item relative overflow-hidden rounded-xl bg-muted transition-transform duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900"
                                            style="display: block;">
                                            
                                            <div class="w-full aspect-square"><img alt="{{ $category->nama_display }}" fetchpriority="high" decoding="async" data-nimg="1"
                                                    class="h-full w-full object-cover object-center"
                                                    src="{{ $category->thumbnail }}"
                                                    src="{{ $category->thumbnail }}" style="color: transparent;"></div>
                                            <div class="bg-murky-900 py-2">
                                                <div class="flex flex-col px-3 py-1">
                                                    <h2 class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $category->nama_display }}</h2>
                                                    <p class="truncate text-xxs text-foreground sm:text-xs">{{ $category->sub_nama }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div id="headlessui-tabs-panel-emoney" role="tabpanel" aria-labelledby="headlessui-tabs-tab-emoney"
                        data-tabs-content style="display: none;">
                        <div
                            class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6 ">
                            @foreach ($kategori as $category)
                                @if ($category->tipe == 'emoney')
                                <a href="{{ url('/id/' . $category->kode) }}" class="melpazoom">
                                    <div class="group category-item relative overflow-hidden rounded-xl bg-muted transition-transform duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900"
                                        style="display: block;">
                                        
                                        <div class="w-full aspect-square"><img alt="{{ $category->nama_display }}" fetchpriority="high" decoding="async" data-nimg="1"
                                                class="h-full w-full object-cover object-center"
                                                src="{{ $category->thumbnail }}"
                                                src="{{ $category->thumbnail }}" style="color: transparent;"></div>
                                        <div class="bg-murky-900 py-2">
                                            <div class="flex flex-col px-3 py-1">
                                                <h2 class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $category->nama_display }}</h2>
                                                <p class="truncate text-xxs text-foreground sm:text-xs">{{ $category->sub_nama }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="headlessui-tabs-panel-app" role="tabpanel" aria-labelledby="headlessui-tabs-tab-app"
                        data-tabs-content style="display: none;">
                        <div
                            class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6 ">
                            @foreach ($kategori as $category)
                                @if ($category->tipe == 'app')
                                <a href="{{ url('/id/' . $category->kode) }}" class="melpazoom">
                                    <div class="group category-item relative overflow-hidden rounded-xl bg-muted transition-transform duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900"
                                        style="display: block;">
                                        
                                        <div class="w-full aspect-square"><img alt="{{ $category->nama_display }}" fetchpriority="high" decoding="async" data-nimg="1"
                                                class="h-full w-full object-cover object-center"
                                                src="{{ $category->thumbnail }}"
                                                src="{{ $category->thumbnail }}" style="color: transparent;"></div>
                                        <div class="bg-murky-900 py-2">
                                            <div class="flex flex-col px-3 py-1">
                                                <h2 class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $category->nama_display }}</h2>
                                                <p class="truncate text-xxs text-foreground sm:text-xs">{{ $category->sub_nama }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div id="headlessui-tabs-panel-voucher2" role="tabpanel" aria-labelledby="headlessui-tabs-tab-voucher2"
                        data-tabs-content style="display: none;">
                        <div
                            class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6">
                            @foreach ($kategori as $category)
                                @if ($category->tipe == 'voucher2')
                                <a href="{{ url('/id/' . $category->kode) }}" class="melpazoom">
                                    <div class="group category-item relative overflow-hidden rounded-xl bg-muted transition-transform duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900"
                                        style="display: block;">
                                        
                                        <div class="w-full aspect-square"><img alt="{{ $category->nama_display }}" fetchpriority="high" decoding="async" data-nimg="1"
                                                class="h-full w-full object-cover object-center"
                                                src="{{ $category->thumbnail }}"
                                                src="{{ $category->thumbnail }}" style="color: transparent;"></div>
                                        <div class="bg-murky-900 py-2">
                                            <div class="flex flex-col px-3 py-1">
                                                <h2 class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $category->nama_display }}</h2>
                                                <p class="truncate text-xxs text-foreground sm:text-xs">{{ $category->sub_nama }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div id="headlessui-tabs-panel-pulsa" role="tabpanel" aria-labelledby="headlessui-tabs-tab-pulsa"
                        data-tabs-content style="display: none;">
                        <div
                            class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6">
                            @foreach ($kategori as $category)
                                @if ($category->tipe == 'pulsa')
                                <a href="{{ url('/id/' . $category->kode) }}" class="melpazoom">
                                    <div class="group category-item relative overflow-hidden rounded-xl bg-muted transition-transform duration-300 ease-in-out hover:shadow-2xl hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-900"
                                        style="display: block;">
                                        
                                        <div class="w-full aspect-square"><img alt="{{ $category->nama_display }}" fetchpriority="high" decoding="async" data-nimg="1"
                                                class="h-full w-full object-cover object-center"
                                                src="{{ $category->thumbnail }}"
                                                src="{{ $category->thumbnail }}" style="color: transparent;"></div>
                                        <div class="bg-murky-900 py-2">
                                            <div class="flex flex-col px-3 py-1">
                                                <h2 class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $category->nama_display }}</h2>
                                                <p class="truncate text-xxs text-foreground sm:text-xs">{{ $category->sub_nama }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    
                    <div id="headlessui-tabs-panel-entertaiment" role="tabpanel"
                        aria-labelledby="headlessui-tabs-tab-entertaiment" data-tabs-content style="display: none;">
                        <div
                            class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-x-6 sm:gap-y-8 lg:grid-cols-5 xl:grid-cols-6">
                        </div>
                    </div>


                </div>
            </div>
    </div>
    </div>

    </section>

    <div id="popupp" x-data="{ open: false, dontShowAgain: localStorage.getItem('dontShowPopup') === 'true' }" x-init="if (!dontShowAgain) { setTimeout(() => open = true, 100) }" @keydown.escape.window="open = false"
        @click.outside="open = false" class="relative z-50 font-sans" x-cloak>

        <div class="fixed inset-0 bg-black/80 transition-opacity" x-show="open"
            x-transition:enter="transition-opacity ease-out duration-500" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak @click="open = false">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto" x-show="open" x-cloak>
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div @click.outside="open = false"
                    class="relative w-full transform overflow-hidden rounded-2xl bg-accent text-left shadow-xl transition-all sm:my-8 sm:max-w-3xl"
                    x-show="open" x-transition:enter="transition-all ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition-all ease-in duration-500"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                    <div class="absolute right-0 top-0 z-40 block pr-4 pt-4"><button type="button" @click="open = false"
                            class="rounded-md bg-murky-500 text-white ring-1 focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-murky-800"><span
                                class="sr-only">Close</span><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg></button></div>

                    <div class="w-full pb-4">
                        <div><img alt="" fetchpriority="high" width="0" height="0" decoding="async"
                                class="object-center" sizes="100vw"
                                src="{{ isset($popup->path) ? $popup->path : '' }}"
                                src="{{ isset($popup->path) ? $popup->path : '' }}"
                                style="color: transparent; width: 100%; height: auto;" /></div>

                        <div class="relative flex flex-col items-center pt-4 text-foreground">
                            <div class="flex w-full items-center justify-center px-4">
                                <div class="col-span-2 -mt-3 flex w-full flex-col items-center justify-center !text-xxs">
                                    <h2 class="max-w-xl pt-1 text-center text-sm font-semibold">PENGUMUMAN</h2>
                                </div>
                            </div>

                            <!-- Gunakan x-html untuk menampilkan HTML mentah dari deskripsi -->
                            <div class="prose prose-sm px-4 pb-4 text-xs text-foreground">
                                <p class="text-white" x-html="`{!! isset($popup->deskripsi)
                                    ? $popup->deskripsi
                                    : '<span class="custom-span">Selamat datang di ' .
                                        htmlspecialchars(ENV('APP_NAME'), ENT_QUOTES, 'UTF-8') .
                                        ' Selamat berbelanja.</span>' !!}`">
                                </p>
                            </div>

                            <div class="flex w-full items-center justify-start px-4 pb-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="dontShowPopup"
                                        class="h-4 w-4 cursor-pointer rounded border bg-background text-primary focus:ring-primary focus:ring-offset-background"
                                        x-model="dontShowAgain"
                                        @change="localStorage.setItem('dontShowPopup', dontShowAgain ? 'true' : 'false'); if(dontShowAgain) { open = false }">
                                    <label
                                        class="block text-xs font-medium text-foreground ml-3 block select-none text-sm text-foreground !text-xxs !ml-2">Jangan
                                        tampilkan lagi</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div id="popupp" x-data="{ open: false, dontShowAgain: localStorage.getItem('dontShowPopup') === 'true' }"
        x-init="if (!dontShowAgain) { setTimeout(() => open = true, 100) }" @keydown.escape.window="open = false"
        @click.outside="open = false" class="relative z-50 font-sans" x-cloak>
    
        <div class="fixed inset-0 bg-black/80 transition-opacity" x-show="open"
            x-transition:enter="transition-opacity ease-out duration-500" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak @click="open = false">
        </div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto" x-show="open" x-cloak>
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div @click.outside="open = false"
                    class="relative w-full transform overflow-hidden rounded-2xl bg-accent text-left shadow-xl transition-all sm:my-8 sm:max-w-3xl"
                    x-show="open" x-transition:enter="transition-all ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition-all ease-in duration-500"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    
                    <div class="absolute right-0 top-0 z-40 block pr-4 pt-4"><button type="button" @click="open = false"
                            class="rounded-md bg-murky-500 text-white ring-1 focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-murky-800"><span
                            class="sr-only">Close</span><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg></button></div>
                    
                    <div class="w-full pb-4">
                        <div><img alt="" fetchpriority="high" width="0" height="0" decoding="async"
                                class="object-center" sizes="100vw"
                                src="{{ isset($popup->path) ? $popup->path : '' }}"
                                src="{{ isset($popup->path) ? $popup->path : '' }}"
                                style="color: transparent; width: 100%; height: auto;" /></div>
                        
                        <div class="relative flex flex-col items-center pt-4 text-foreground">
                            <div class="flex w-full items-center justify-center px-4">
                                <div class="col-span-2 -mt-3 flex w-full flex-col items-center justify-center !text-xxs">
                                    <h2 class="max-w-xl pt-1 text-center text-sm font-semibold">PENGUMUMAN</h2>
                                </div>
                            </div>
                            <div class="prose prose-sm px-4 pb-4 text-xs text-foreground">
                                <p class="text-white">
                                    {!! isset($popup->deskripsi)
                                        ? $popup->deskripsi
                                        : '<span class="custom-span">Selamat datang di ' .
                                        htmlspecialchars(ENV('APP_NAME'), ENT_QUOTES, 'UTF-8') . ' Selamat berbelanja.</span>' !!}
                                </p>
                            </div>

                            <div class="flex w-full items-center justify-start px-4 pb-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="dontShowPopup" 
                                        class="h-4 w-4 cursor-pointer rounded border bg-background text-primary focus:ring-primary focus:ring-offset-background"
                                        x-model="dontShowAgain" 
                                        @change="localStorage.setItem('dontShowPopup', dontShowAgain ? 'true' : 'false'); if(dontShowAgain) { open = false }">
                                    <label class="block text-xs font-medium text-foreground ml-3 block select-none text-sm text-foreground !text-xxs !ml-2">Jangan
                                        tampilkan lagi</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}



    {{-- <div class="popup-structuree popup-slidee flex min-h-full items-center justify-center p-4 text-center sm:p-0 show"
        id="popupp" style="display: block;">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative w-full transform overflow-hidden rounded-lg bg-murky-900 text-left shadow-xl transition-all sm:my-8 sm:max-w-3xl !rounded-2xl opacity-100 translate-y-0 sm:scale-100"
                    id="headlessui-dialog-panel-:rd:" data-headlessui-state="open">
                    <div class="absolute right-0 top-0 block pr-4 pt-4">
                        <button type="button" name="popupp"
                            class="close-popup-buttonn rounded-md bg-murky-700 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-murky-800"
                            data-popup-id="popup-0">
                            <span class="sr-only">Close</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="w-full pb-4 flex flex-col items-center justify-center">
                        <div class="object-center">
                            <img src="{{ isset($popup->path) ? $popup->path : '' }}" class="object-center"
                                style="color: transparent; width: 100%; height: auto;" alt="">
                        </div>
                        <div class="relative flex flex-col items-center pt-4 text-white">
                            <div class="flex w-full items-center justify-center px-4">
                                <div class="col-span-2 -mt-3 flex w-full flex-col items-center justify-center !text-xxs">
                                    <h2 class="max-w-xl pt-1 text-center text-sm font-semibold">PENGUMUMAN</h2>
                                </div>
                            </div>
                            <div class="w-full max-w-none prose prose-sm px-4 py-4 text-xs text-white">
                                <p class="text-center" style="text-align: center;">
                                    {!! isset($popup->deskripsi)
                                        ? $popup->deskripsi
                                        : '<span class="custom-span">Selamat datang di ' .
                                            htmlspecialchars(ENV('APP_NAME'), ENT_QUOTES, 'UTF-8') .
                                            ' Selamat berbelanja.</span>' !!}
                                </p>
                            </div>
                        </div>
                        <div class="flex w-full items-center justify-start px-4 pb-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="dontshowinfoo"
                                    class="h-4 w-4 cursor-pointer rounded border-murky-600 bg-murky-700 text-primary-500 focus:ring-primary-400 focus:ring-offset-murky-900">
                                <label
                                    class="block text-xs font-medium text-white ml-3 block select-none text-sm text-white !text-xxs !ml-2">Jangan
                                    tampilkan lagi</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            let e = document.getElementById("dontshowinfoo"),
                t = document.querySelector(".popup-structuree"),
                o = document.querySelectorAll(".close-popup-buttonn");

            function n() {
                t.style.display = "none", e.checked && localStorage.setItem("hidePopupp", "true")
            }
            o.forEach(e => {
                    e.addEventListener("click", n)
                }), "true" === localStorage.getItem("hidePopupp") ? t.style.display = "none" : t.style.display =
                "block"
        }), document.addEventListener("DOMContentLoaded", function() {
            let e = document.querySelectorAll(".popup-slidee"),
                t = !1;
            0 < e.length && (e[0].classList.add("show"), t = !0), document.addEventListener("click", function(o) {
                t && (Array.from(e).some(e => e.contains(o.target)) || (e.forEach(e => {
                    e.classList.remove("show")
                }), t = !1))
            })
        });
    </script> --}}
    <script src="{{ asset('/assets/js/0x0x00x0xindxx00mmxx.js') }}"></script>
    <style>
        .ring-4 {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(4px + var(--tw-ring-offset-width)) rgb(201 204 208 / 50%);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }
    </style>

    @include('../footer')
    @push('custom_script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let t = document.querySelectorAll("[data-tabs-toggle]"),
                    e = document.querySelectorAll("[data-tabs-content]"),
                    a = t[0],
                    l = document.querySelector(a.getAttribute("data-tabs-toggle"));
                a.classList.add("active"), a.style.backgroundColor = "var(--warna_3)", l.style.display = "block", t
                    .forEach(function(a) {
                        a.addEventListener("click", function() {
                            e.forEach(function(t) {
                                t.style.display = "none"
                            }), t.forEach(function(t) {
                                t.classList.remove("active"), t.style.backgroundColor =
                                    "var(--warna_1)"
                            });
                            let a = this.getAttribute("data-tabs-toggle"),
                                l = document.querySelector(a);
                            l.style.display = "block", this.classList.add("active"), this.style
                                .backgroundColor = "var(--warna_3)"
                        })
                    })
            });
        </script>
        <script>
            function updateTimer() {
                @foreach ($flashsale as $fs)
                    @php
                        $expiredFlashSale = new DateTime($fs->expired_flash_sale);
                        $formattedDate = $expiredFlashSale->format('Y-m-d H:i:s');
                    @endphp
                    var countDownDate = new Date("{{ $formattedDate }}").getTime(),
                        x = setInterval(function() {
                            var t = new Date().getTime(),
                                e = countDownDate - t;

                            if (e > 0) {
                                // Hitung hari, jam, menit, dan detik
                                var days = Math.floor(e / (1000 * 60 * 60 * 24)),
                                    hours = Math.floor((e % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                                    minutes = Math.floor((e % (1000 * 60 * 60)) / (1000 * 60)),
                                    seconds = Math.floor((e % (1000 * 60)) / 1000);

                                // Tampilkan hasil dengan padding nol jika perlu
                                document.getElementById("days").textContent = days.toString().padStart(2, "0");
                                document.getElementById("hours").textContent = hours.toString().padStart(2, "0");
                                document.getElementById("minutes").textContent = minutes.toString().padStart(2, "0");
                                document.getElementById("seconds").textContent = seconds.toString().padStart(2, "0");
                            } else {
                                // Waktu habis
                                clearInterval(x);
                                document.getElementById("days").textContent = "00";
                                document.getElementById("hours").textContent = "00";
                                document.getElementById("minutes").textContent = "00";
                                document.getElementById("seconds").textContent = "00";
                                document.getElementById("expired_time_flash_sale").textContent = "Waktu sudah habis!";
                            }
                        }, 1000);
                @endforeach
            }
            document.addEventListener("DOMContentLoaded", function() {
                updateTimer();
            });
        </script>
    @endpush

@endsection
