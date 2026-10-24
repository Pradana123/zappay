<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Content-Security-Policy" content="object-src 'none'">
    <meta http-equiv="Content-Security-Policy" content="base-uri 'self'">
    <meta http-equiv="Strict-Transport-Security" content="max-age=31536000; includeSubDomains; preload">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <!-- SEO Meta Tags -->
    <meta name="theme-color" content="#ffffff">
    <meta property="og:title" content="{{ !$config ? '' : $config->judul_web }}">
    <meta property="og:type" content="website">
    <meta property="og:keywords" content="{{ !$config ? '' : $config->keywords }}">
    <meta property="og:description" content="{{ !$config ? '' : $config->deskripsi_web }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ !$config ? '' : $config->logo_favicon }}">
    <meta name="title" content="{{ !$config ? '' : $config->judul_web }}">
    <meta name="description" content="{{ !$config ? '' : $config->deskripsi_web }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="te6_kjcXFcgjdrdjiu-K15FFkNv-PntWR4IqJtvHFtQ" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('') }}{{ !$config ? '' : $config->logo_favicon }}">
    <!-- Title -->
    <title>{{ !$config ? '' : $config->judul_web }}</title>
    <!-- Stylesheets and Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link rel="stylesheet" href="{{ asset('/assets/css/pisceskooky.css') }}"> --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;700&display=swap');

        :root {
            --warna_1:
                <?=$config->warna1 ?>;
            --warna_2:
                <?=$config->warna2 ?>;
            --warna_3:
                <?=$config->warna3 ?>;
            --warna_4:
                <?=$config->warna4 ?>;
            --gradient-theme: linear-gradient(to right, var(--warna_1) 0%, var(--warna_2) 50%, var(--warna_2) 100%);
        }
    </style>
</head> 

<link rel="stylesheet" href="{{ asset('/assets/css/x0xx0x0x0x0xx0.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/css/0xx00xxx0x00x0tmplttm0x0xxzx0xxXx0x0xx0xx.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/css/0x0x0x00ordr0x0x.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/css/0x0x0x0x00xx00x0x0x0x00x0x0x0x0x0x00xxx.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/css/oxooxoxoxoxordxoxoxxo.css')}}">

@yield('custom_style')

<body  class="bg-gradient-theme text-white antialiased" :class="{ 'overflow-hidden': isSearchModalOpen }" x-data="{ 'isSearchModalOpen': false }" x-on:keydown.escape="isSearchModalOpen=false"
    x-on:keydown.escape="isSearchModalOpen=false">
    <div class="relative z-50" role="dialog" tabindex="-1" x-show="isSearchModalOpen" x-on:click.away="isSearchModalOpen = false" x-cloak x-transition>
        <div class="fixed inset-0 z-50 overflow-hidden p-4 py-20 sm:py-20 sm:px-6 md:p-20">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity opacity-100" x-show="isSearchModalOpen" x-cloak x-on:click="isSearchModalOpen=false"></div>
          <div class="mx-auto max-w-2xl transform divide-y divide-gray-500 divide-opacity-10 overflow-hidden rounded-md bg-murky-700 bg-opacity-80 shadow-2xl ring-1 ring-black ring-opacity-5 backdrop-blur backdrop-filter transition-all opacity-100 scale-100" id="dialog-panel-:r4g:" data-state="open">
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-white text-opacity-40">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"></path>
              </svg>
              <form>
                <input class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-white focus:ring-0 sm:text-sm" placeholder="Cari Game, item, Voucher" id="searchProds" role="combobox" type="text" name="q" aria-expanded="false" aria-autocomplete="list" value="" aria-controls="combobox-options-:r4i:" tabindex="0">
              </form>
            </div>
            <ul class="resultsearch max-h-80 scroll-py-2 divide-y divide-gray-500 divide-opacity-10 overflow-y-auto">
              <div class="flex flex-col gap-2 items-center justify-center py-5" id="lottie-container">
                <span class="text-base text-center opacity-70 py-4">Produk Belum Anda Cari</span>
              </div>
            </ul>
          </div>
        </div>
      </div>
    <main class="relative">
        <div id="app"> @yield('content') </div>
    </main>
    <script src="{{ asset('assets/js/oo324ddod2323sd2d2mppmx.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- <script src="/assets/js/oo324ddod2323sd2d.js" nonce="YOUR_GENERATED_NONCE"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" nonce="YOUR_GENERATED_NONCE"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha256-CDOy6cOibCWEdsRiZuaHf8dSGGJRYuBGC+mjoJimHGw=" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" nonce="YOUR_GENERATED_NONCE"></script>
    <script src="//cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer nonce="YOUR_GENERATED_NONCE">
    </script>
    <script src="//cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer nonce="YOUR_GENERATED_NONCE"></script>
    <script src="//cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer nonce="YOUR_GENERATED_NONCE"></script>
    <script nonce="YOUR_GENERATED_NONCE">
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    </script>
    <script>
        var delay = function() {
          var e = 0;
          return function(r, a) {
            clearTimeout(e), e = setTimeout(r, a)
          }
        }();
        $("#searchProds").keyup(function() {
          let e = $(this).val();
          e.length < 1 ? ($(".resultsearch").removeClass("show"), $(".resultsearch li").remove()) : delay(function() {
            $.ajax({
              url: "{{ url('/id/cari/index') }}",
              method: "POST",
              data: {
                data: e
              },
              beforeSend: function() {
                $(".resultsearch li").remove()
              },
              success: function(e) {
                $(".resultsearch").append(e), $(".resultsearch").addClass("show")
              }
            })
          }, 100)
        });
    </script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    <script>
        document.getElementById('searchProds').addEventListener('input', function() {
            var lottieContainer = document.getElementById('lottie-container');
            if (lottieContainer) {
                lottieContainer.style.display = 'none';
            }
        });
    </script> @stack('custom_script')
</body>
</html>
