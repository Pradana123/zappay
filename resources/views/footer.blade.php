<style>
body {
  font-family: 'IBM Plex Sans Condensed', sans-serif;
  font-weight: bold;
}
[x-cloak] { display: none !important; }
</style>

@php
    $siteName = env('APP_NAME', 'ZAPPAY');
    $supportUrl = (!$config || empty($config->url_wa)) ? '#' : trim($config->url_wa);
    $supportMessage = rawurlencode("Halo Admin {$siteName}, saya butuh bantuan.\n\nNama:\nNomor Pesanan:\nProduk:\nKendala:");
    $complaintMessage = rawurlencode("Halo Admin {$siteName}, saya ingin komplain transaksi.\n\nNomor Pesanan:\nProduk:\nID Game / Nomor Tujuan:\nMetode Pembayaran:\nKendala:");
    $waSeparator = str_contains($supportUrl, '?') ? '&' : '?';
    $supportChatUrl = $supportUrl === '#' ? '#' : $supportUrl . $waSeparator . 'text=' . $supportMessage;
    $complaintChatUrl = $supportUrl === '#' ? '#' : $supportUrl . $waSeparator . 'text=' . $complaintMessage;
@endphp

<svg width="100%" height="100%" id="svg" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg" class="-mt-8 transition delay-150 duration-300 ease-in-out print:hidden md:-mt-24">
    <path d="M 0,400 C 0,400 0,200 0,200 C 40.34909359970402,243.0042668639783 80.69818719940804,286.0085337279566 135,268 C 189.30181280059196,249.9914662720434 257.5563448020718,170.97013195215192 311,159 C 364.4436551979282,147.02986804784808 403.07643359230485,202.11093846343573 452,207 C 500.92356640769515,211.88906153656427 560.137920828709,166.58611419410528 626,169 C 691.862079171291,171.41388580589472 764.3718830928599,221.54460476014305 826,248 C 887.6281169071401,274.45539523985695 938.3745467998519,277.2354667653225 985,246 C 1031.625453200148,214.7645332346775 1074.1299297077323,149.51352817856701 1122,137 C 1169.8700702922677,124.48647182143299 1223.1057343692194,164.71042052040943 1277,184 C 1330.8942656307806,203.28957947959057 1385.4471328153904,201.64478973979527 1440,200 C 1440,200 1440,400 1440,400 Z" stroke="none" stroke-width="0" fill="#000" fill-opacity="1" class="path-0 transition-all bg-gradient-to-l delay-150 duration-300 ease-in-out"></path>
</svg>

<footer class="bg-gradient-to-t from-secondary bg-black text-text-color relative">
    <div class="container pb-8">
        <div class="pt-4 text-center">
            <a href="{{ route('home') }}" aria-label="Kembali ke beranda {{ $siteName }}">
                <img src="{{ !$config ? '' : $config->logo_footer }}" alt="Logo {{ $siteName }}" width="100" class="w-auto h-16 mb-4 mx-auto">
            </a>
            <p class="text-sm leading-6 mb-12 max-w-3xl mx-auto">
                {{ !$config ? '' : $config->deskripsi_web }}
            </p>
        </div>

        <div id="footer_sns_wrp" class="text-center mb-10 mt-8">
            <div id="footer_sns" class="sns_list">
                <div class="flex flex-row space-x-3 font-light justify-center">
                    <a target="_blank" class="social-item flex h-11 w-11 items-center justify-center text-xl text-followus-foreground transition hover:text-primary-200" href="{{ $supportChatUrl }}" rel="noopener noreferrer" aria-label="WhatsApp {{ $siteName }}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>
                    </a>
                    <a target="_blank" class="social-item flex h-11 w-11 items-center justify-center text-xl text-followus-foreground transition hover:text-primary-200" href="{{ !$config ? '#' : $config->url_ig }}" rel="noopener noreferrer" aria-label="Instagram {{ $siteName }}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg>
                    </a>
                    <a target="_blank" class="social-item flex h-11 w-11 items-center justify-center text-xl text-followus-foreground transition hover:text-primary-200" href="{{ !$config ? '#' : $config->url_fb }}" rel="noopener noreferrer" aria-label="Facebook {{ $siteName }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0,0,256,256" style="fill:#FFFFFF;"><g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"><g transform="scale(5.12,5.12)"><path d="M41,4h-32c-2.76,0 -5,2.24 -5,5v32c0,2.76 2.24,5 5,5h32c2.76,0 5,-2.24 5,-5v-32c0,-2.76 -5,-5 -5,-5zM37,19h-2c-2.14,0 -3,0.5 -3,2v3h5l-1,5h-4v15h-5v-15h-4v-5h4v-3c0,-4 2,-7 6,-7c2.9,0 4,1 4,1z"></path></g></g></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="container mx-auto xl:grid xl:grid-cols-2 xl:gap-8 p-4 md:p-6 lg:p-8">
            <div class="grid grid-cols-2 gap-8 xl:col-span-2 md:grid-cols-4">
                <div>
                    <div class="relative">
                        <h3 class="text-sm font-bold leading-6 text-text-color">Bantuan</h3>
                        <div class="h-1 w-1/4 rounded-full bg-primary-400"></div>
                    </div>
                    <ul role="list" class="mt-6 space-y-4">
                        <li><a href="{{ route('zappay.cara-top-up') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Cara Top Up</a></li>
                        <li><a href="{{ route('cari') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Cek Pesanan</a></li>
                        <li><a href="{{ $supportChatUrl }}" target="_blank" rel="noopener noreferrer" class="text-sm leading-6 text-text-color hover:text-primary-200">Hubungi Admin</a></li>
                        <li><a href="{{ $complaintChatUrl }}" target="_blank" rel="noopener noreferrer" class="text-sm leading-6 text-text-color hover:text-primary-200">Komplain Transaksi</a></li>
                    </ul>
                </div>

                <div>
                    <div class="relative">
                        <h3 class="text-sm font-bold leading-6 text-text-color">Informasi</h3>
                        <div class="h-1 w-1/4 rounded-full bg-primary-400"></div>
                    </div>
                    <ul role="list" class="mt-6 space-y-4">
                        <li><a href="{{ route('price') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Daftar Harga</a></li>
                        <li><a href="{{ route('zappay.promo') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Promo</a></li>
                        <li><a href="{{ route('zappay.faq') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">FAQ</a></li>
                        <li><a href="{{ route('zappay.tentang-kami') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Tentang Kami</a></li>
                    </ul>
                </div>

                <div>
                    <div class="relative">
                        <h3 class="text-sm font-bold leading-6 text-text-color">Produk Populer</h3>
                        <div class="h-1 w-1/4 rounded-full bg-primary-400"></div>
                    </div>
                    <ul role="list" class="mt-6 space-y-4">
                        <li><a href="{{ url('/id/mobile-legends') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Mobile Legends</a></li>
                        <li><a href="{{ url('/id/free-fire') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Free Fire</a></li>
                        <li><a href="{{ url('/id/honor-of-kings') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Honor of Kings</a></li>
                        <li><a href="{{ url('/id/pubg-mobile') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">PUBG Mobile</a></li>
                    </ul>
                </div>

                <div>
                    <div class="relative">
                        <h3 class="text-sm font-bold leading-6 text-text-color">Kebijakan</h3>
                        <div class="h-1 w-1/4 rounded-full bg-primary-400"></div>
                    </div>
                    <ul role="list" class="mt-6 space-y-4">
                        <li><a href="{{ route('terms') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Syarat &amp; Ketentuan</a></li>
                        <li><a href="{{ route('policy') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('zappay.refund-policy') }}" class="text-sm leading-6 text-text-color hover:text-primary-200">Kebijakan Refund</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 border-t border-bg-color pt-8 sm:mt-20 lg:mt-24 text-center">
        <p class="text-xs leading-5 text-text-color">2025 {{ $siteName }}. All rights reserved.</p>
    </div>
    <div class="h-8"></div>
</footer>

<!-- Customer Service WhatsApp Popup -->
<div class="fixed bottom-5 right-4 z-50 flex flex-col items-end gap-3" x-data="{ open: true }">
    <div x-cloak x-show="open" x-transition class="w-[19rem] max-w-[calc(100vw-2rem)] rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-2xl overflow-hidden">
        <div class="flex items-start gap-3 border-b border-slate-200 px-4 py-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-400 text-white overflow-hidden">
                @if(!$config || empty($config->logo_favicon))
                    <span class="text-sm font-black">Z</span>
                @else
                    <img src="{{ $config->logo_favicon }}" alt="Logo {{ $siteName }}" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-extrabold leading-4">{{ $siteName }} Support <span class="text-green-500">●</span></p>
                <p class="text-xs text-slate-500">Bantuan pesanan resmi</p>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-700" x-on:click="open = false" aria-label="Tutup WhatsApp support">×</button>
        </div>
        <div class="px-4 py-3">
            <p class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs leading-5 text-slate-700">
                Butuh bantuan terkait pesanan, pembayaran, atau komplain transaksi? Chat admin {{ $siteName }} melalui WhatsApp resmi yang tercantum di website ini.
            </p>
            <div class="mt-3 grid gap-2">
                <a href="{{ $supportChatUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-green-600">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157z"></path></svg>
                    Mulai Chat
                </a>
                <a href="{{ $complaintChatUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    Komplain Transaksi
                </a>
            </div>
            <p class="mt-3 text-[11px] leading-4 text-slate-500">Admin tidak pernah meminta password akun game, OTP, atau PIN pembayaran.</p>
        </div>
    </div>

    <button type="button" x-on:click="open = !open" class="flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-3xl text-white shadow-2xl transition-transform duration-300 hover:scale-110" aria-label="Buka WhatsApp support {{ $siteName }}">
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6z"></path></svg>
    </button>
</div>
<!-- Customer Service WhatsApp Popup End -->
