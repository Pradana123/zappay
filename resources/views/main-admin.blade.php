<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ !$config ? '' : $config->judul_web }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="{{ url('manifest.json') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="{{ !$config ? '' : $config->deskripsi_web }}" name="description" />
    <meta content="ZAPPAY." name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard admin " />
    <link rel="icon" type="image/x-icon" href="{{ ENV('APP_IMAGE') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <Style>
        .menu-vertical .menu-block,
        .menu-vertical .menu-item .menu-link {
            padding: .225rem 1rem
        }

        .bg-menu-theme .menu-inner>.menu-item.active:before {
            background: #494949
        }

        .bg-menu-theme .menu-sub>.menu-item.active>.menu-link:not(.menu-toggle):before {
            left: -.125rem;
            width: .875rem;
            height: .875rem
        }

        .bg-menu-theme .menu-sub>.menu-item>.menu-link:before {
            content: "";
            position: absolute;
            left: .375rem;
            width: .375rem;
            height: .375rem;
            border-radius: 50%
        }

        .layout-wrapper:not(.layout-horizontal) .bg-menu-theme .menu-inner>.menu-item.active:before {
            content: "";
            position: absolute;
            right: 0;
            width: .25rem;
            height: 30px;
            border-radius: .375rem 0 0 .375rem
        }

        .menu-vertical,
        .menu-vertical .menu-block,
        .menu-vertical .menu-inner>.menu-header,
        .menu-vertical .menu-inner>.menu-item {
            width: 15.25rem
        }

        .bg-menu-theme .menu-link,
        .bg-menu-theme .menu-horizontal-prev,
        .bg-menu-theme .menu-horizontal-next {
            color: #ffffff;
        }

        .bg-menu-theme {
            background-color: #666666 !important;
            color: #ffffff;
        }

        .bg-footer-theme {
            background-color: #666666 !important;
            color: #ffffff;
        }

        .table-dark {
            --bs-table-bg: #8f8f8f;
            --bs-table-striped-bg: #626466;
            --bs-table-striped-color: #fff;
            --bs-table-active-bg: #9a9a9a;
            --bs-table-active-color: #fff;
            --bs-table-hover-bg: #8f8f8f;
            --bs-table-hover-color: #fff;
            color: #fff;
            border-color: #bdbebe;
        }

        .bg-navbar-theme .navbar-nav>.nav-link,
        .bg-navbar-theme .navbar-nav>.nav-item>.nav-link,
        .bg-navbar-theme .navbar-nav>.nav>.nav-item>.nav-link {
            color: #fefefe;
        }

        .bg-menu-theme .menu-item.open:not(.menu-item-closing)>.menu-toggle,
        .bg-menu-theme .menu-item.active>.menu-link {
            color: #abc0f2;
        }

        .bg-menu-theme .menu-inner>.menu-item.active>.menu-link {
            color: #eef70b;
            background-color: rgb(255 255 255 / 22%) !important;
        }

        body {
            margin: 0;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: #dbdbdb;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgb(72 139 212 / 51%);
        }

        a.bg-primary:hover,
        a.bg-primary:focus {
            background-color: #a9a9a9 !important;
        }
    </Style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme bg-footer-theme"
                    id="layout-navbar">
                    
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!--<h1 class="text-center">Created By ZAPPAY</h1>-->
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme mt-5">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                @
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                Copyright
                                <a href="/" target="_blank"
                                    class="footer-link fw-bolder text-white">ZAPPAY.</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->





    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>



    <!-- third party js -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <!-- third party js ends -->

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 200,       // Tinggi editor
                tabsize: 2,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#colorbackground").spectrum({
                preferredFormat: "hex",
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ["#ff0000", "#00ff00", "#0000ff"],
                    ["#ffffff", "#000000", "#cccccc"]
                ],
                chooseText: "Pilih",
                cancelText: "Batal",
                change: function(color) {
                    console.log("Warna terpilih:", color.toHexString());
                }
            });
            $("#colorInputmel").spectrum({
                preferredFormat: "hex",
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ["#ff0000", "#00ff00", "#0000ff"],
                    ["#ffffff", "#000000", "#cccccc"]
                ],
                chooseText: "Pilih",
                cancelText: "Batal",
                change: function(color) {
                    console.log("Warna terpilih:", color.toHexString());
                }
            });
            $("#colorInputtih").spectrum({
                preferredFormat: "hex",
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ["#ff0000", "#00ff00", "#0000ff"],
                    ["#ffffff", "#000000", "#cccccc"]
                ],
                chooseText: "Pilih",
                cancelText: "Batal",
                change: function(color) {
                    console.log("Warna terpilih:", color.toHexString());
                }
            });
            $("#colorInputt").spectrum({
                preferredFormat: "hex",
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ["#ff0000", "#00ff00", "#0000ff"],
                    ["#ffffff", "#000000", "#cccccc"]
                ],
                chooseText: "Pilih",
                cancelText: "Batal",
                change: function(color) {
                    console.log("Warna terpilih:", color.toHexString());
                }
            });
            $("#colorInputtt").spectrum({
                preferredFormat: "hex",
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ["#ff0000", "#00ff00", "#0000ff"],
                    ["#ffffff", "#000000", "#cccccc"]
                ],
                chooseText: "Pilih",
                cancelText: "Batal",
                change: function(color) {
                    console.log("Warna terpilih:", color.toHexString());
                }
            });
            $("#colorInputi").spectrum({
                preferredFormat: "hex",
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ["#ff0000", "#00ff00", "#0000ff"],
                    ["#ffffff", "#000000", "#cccccc"]
                ],
                chooseText: "Pilih",
                cancelText: "Batal",
                change: function(color) {
                    console.log("Warna terpilih:", color.toHexString());
                }
            });
        });
    </script>



    <!-- Load Spectrum CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">

    <!-- Load Spectrum JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js"></script>

</body>



</html>
