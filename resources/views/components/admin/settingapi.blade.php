@extends('layouts.master')
@section('title')
    {{ ENV('APP_NAME') }} - Settings API
@endsection
@section('css')
    <style>
        #loading-spinner {
            margin: 20px auto;
        }
    </style>
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <!-- Breadcrumb -->
    <div class="row g-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Konfigurasi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/setting/api') }}">Konfigurasi</a></li>
                        <li class="breadcrumb-item active">Setting Apikey</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Tabs Navigation dengan nav-tabs-custom -->
    <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-primary mb-3" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="api-tab" data-bs-toggle="tab" data-bs-target="#api" type="button"
                role="tab" aria-controls="api" aria-selected="true">
                <i class="ri-fire-line"></i> Konfigurasi API
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="configTabsContent">
        <!-- Tab 1: Konfigurasi API -->
        <div class="tab-pane fade show active" id="api" role="tabpanel" aria-labelledby="api-tab">

            <div class="row g-4">
                <div class="col-xxl-6">
                    <div class="card">
                        <form action="{{ url('/setting/paydisini') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Konfigurasi API Paydisini</h5>
                                <span class="text-danger">https://web.paydisini.co.id/</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <label class="form-label">Paydisini Api Key<span
                                                class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control"
                                                value="{{ $api->paydisini_apikey }}" name="paydisini_apikey"
                                                id="paydisiniApiKey">
                                            <span onclick="toggleVisibility('paydisiniApiKey', this)"
                                                style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <label class="form-label"><span class="text-danger">URL Callback</span></label>
                                        <div class="form-icon right">
                                            <input type="text" class="form-control" value="{{ ENV('APP_URL') }}/paydisini/callback" id="urlCallbackPaydisini" readonly>
                                            <span onclick="copyToClipboard('urlCallbackPaydisini', this)" style="cursor: pointer;">
                                                <h4><i class="ri-file-copy-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end">
                                        <div class="hstack justify-content-end gap-2">
                                            <button type="submit"
                                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-xxl-6">
                    <div class="card">
                        <form action="{{ url('/setting/tokopay') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Konfigurasi API Tokopay</h5>
                                <span class="text-danger">https://tokopay.id/</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <label class="form-label">Tokopay Merchant ID<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                            value="{{ $api->tokopay_merchant_id }}" name="tokopay_merchant_id">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">Tokopay Secret Key<span
                                                class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control"
                                                value="{{ $api->tokopay_secret_key }}" name="tokopay_secret_key"
                                                id="tokopaySecretKey">
                                            <span onclick="toggleVisibility('tokopaySecretKey', this)"
                                                style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <label class="form-label"><span class="text-danger">URL Callback</span></label>
                                        <div class="form-icon right">
                                            <input type="text" class="form-control" value="{{ ENV('APP_URL') }}/tokopay/callback" id="urlCallbackTokopay" readonly>
                                            <span onclick="copyToClipboard('urlCallbackTokopay', this)" style="cursor: pointer;">
                                                <h4><i class="ri-file-copy-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end">
                                        <div class="hstack justify-content-end gap-2">
                                            <button type="submit"
                                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-xxl-6">
                    <div class="card">
                        <form action="{{ url('/setting/tripay') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Konfigurasi API Tripay</h5>
                                <span class="text-danger">https://tripay.co.id/</span>
                            </div>
                            <div class="card-body">                           
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <label class="form-label">Tripay Merchant ID<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                            value="{{ $api->tripay_merchant_code }}" name="tripay_merchant_code">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">Tripay Api Key<span
                                                class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control"
                                                value="{{ $api->tripay_api }}" name="tripay_api"
                                                id="tripayApi">
                                            <span onclick="toggleVisibility('tripayApi', this)"
                                                style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">Tripay Private Key<span
                                                class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control"
                                                value="{{ $api->tripay_private_key }}" name="tripay_private_key"
                                                id="tripayPrivateKey">
                                            <span onclick="toggleVisibility('tripayPrivateKey', this)"
                                                style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <label class="form-label"><span class="text-danger">URL Callback</span></label>
                                        <div class="form-icon right">
                                            <input type="text" class="form-control" value="{{ ENV('APP_URL') }}/tripay/callback" id="urlCallbackTripay" readonly>
                                            <span onclick="copyToClipboard('urlCallbackTripay', this)" style="cursor: pointer;">
                                                <h4><i class="ri-file-copy-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end">
                                        <div class="hstack justify-content-end gap-2">
                                            <button type="submit"
                                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card">
                        <form action="{{ url('/setting/digiflazz') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Konfigurasi API Digiflazz (Buyer)</h5>
                                <span class="text-danger">https://digiflazz.com/</span>
                                <!--<p></p>-->
                                <!--<span class="text-danger">SECRETCODE: ZAPPAY</span>-->
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <label class="form-label">Username Digiflazz<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $api->username_digi }}"
                                            name="username_digi">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">Api Key Digiflazz<span
                                                class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control" value="{{ $api->api_key_digi }}"
                                                name="api_key_digi" id="digiApiKey">
                                            <span onclick="toggleVisibility('digiApiKey', this)" style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <label class="form-label"><span class="text-danger">URL Webhook</span></label>
                                        <div class="form-icon right">
                                            <input type="text" class="form-control" value="{{ ENV('APP_URL') }}/digiflazz/callback" id="urlWebhookDigiflazz" readonly>
                                            <span onclick="copyToClipboard('urlWebhookDigiflazz', this)" style="cursor: pointer;">
                                                <h4><i class="ri-file-copy-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label"><span class="text-danger">SECRET CODE</span></label>
                                        <div class="form-icon right">
                                            <input type="text" class="form-control" value="ZAPPAY" id="secrectCodeDigiflazz" readonly>
                                            <span onclick="copyToClipboard('secrectCodeDigiflazz', this)" style="cursor: pointer;">
                                                <h4><i class="ri-file-copy-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end">
                                        <div class="hstack justify-content-end gap-2">
                                            <button type="submit"
                                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-xxl-6 mt-2">
                    <div class="card">
                        <form action="{{ url('/setting/vip') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Konfigurasi Vip Reseller</h5>
                                <span class="text-danger">https://vip-reseller.co.id/</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <label class="form-label">VIP Api ID<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $api->vip_apiid }}"
                                            name="vip_apiid">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">VIP Api Key<span class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control" value="{{ $api->vip_apikey }}" name="vip_apikey" id="vipApiKey">
                                            <span onclick="toggleVisibility('vipApiKey', this)" style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <label class="form-label"><span class="text-danger">URL Webhook</span></label>
                                        <div class="form-icon right">
                                            <input type="text" class="form-control" value="{{ ENV('APP_URL') }}/vip/callback" id="urlWebhookVip" readonly>
                                            <span onclick="copyToClipboard('urlWebhookVip', this)" style="cursor: pointer;">
                                                <h4><i class="ri-file-copy-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end">
                                        <div class="hstack justify-content-end gap-2">
                                            <button type="submit"
                                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                
                
                <div class="col-lg-12 mt-2">
                    <div class="card">
                        <form action="{{ url('/setting/wagateway') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5 class="card-title mb-0">Konfigurasi Fonnte</h5>
                                 <span class="text-danger">https://fonnte.com</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <label class="form-label">No. Admin<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $api->nomor_admin }}"
                                            name="nomor_admin" id="nomor">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">Whatsapp Number<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $api->wa_number }}"
                                            name="wa_number" id="nomor">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label">Whatsapp Key<span class="text-danger">*</span></label>
                                        <div class="form-icon right">
                                            <input type="password" class="form-control" value="{{ $api->wa_key }}"
                                                name="wa_key" id="wAKey">
                                            <span onclick="toggleVisibility('wAKey', this)" style="cursor: pointer;">
                                                <h4><i class="ri-eye-off-line"></i></h4>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end">
                                        <div class="hstack justify-content-end gap-2">
                                            <button type="submit"
                                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- JavaScript Toggle Visibility -->
    <script>
        function copyToClipboard(id, el) {
            const input = document.getElementById(id);
            input.select();
            input.setSelectionRange(0, 99999); // Untuk mobile

            navigator.clipboard.writeText(input.value)
                .then(() => {
                    const icon = el.querySelector('i');
                    icon.classList.replace('ri-file-copy-line', 'ri-check-line');

                    setTimeout(() => {
                        icon.classList.replace('ri-check-line', 'ri-file-copy-line');
                    }, 2000);
                })
                .catch(err => {
                    console.error('Gagal menyalin: ', err);
                    alert('Gagal menyalin teks.');
                });
        }
    </script>
    
    <script>
        function toggleVisibility(id, el) {
            const input = document.getElementById(id);
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
            } else {
                input.type = 'password';
                icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
            }
        }
    </script>

    <script>
        const nomorInputs = document.querySelectorAll("#nomor");

        nomorInputs.forEach(input => {
            input.addEventListener("input", function() {
                let nomor = this.value;
                if (nomor.startsWith("08")) {
                    nomor = "62" + nomor.slice(1);
                    this.value = nomor;
                }
            });
        });
    </script>
@endsection
