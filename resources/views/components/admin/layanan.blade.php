@extends('layouts.master')
@section('title')
{{ ENV('APP_NAME') }} - Kelola Layanan
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
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Produk</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('produk') }}">Produk</a></li>
                    <li class="breadcrumb-item active">Layanan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@if (session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

@if (session('info'))
<div class="alert alert-info" role="alert">
    {{ session('info') }}
</div>
@endif

@if (session('danger'))
<div class="alert alert-danger" role="alert">
    {{ session('danger') }}
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Buat Layanan</h5>
            </div>
            <div class="mb-3">
                <div class="card-body">
                    <div class="clearfix">
                        
                        <div style="clear: both;"></div>
                        <hr style="margin: 10px 0;">
                        <form action="{{ route('replace-data') }}" method="post">
                            @csrf
                            <div class="m-2" style="float: left;">
                                <label>Replace nama layanan :</label>
                                <textarea name="kata_replace" placeholder="Contoh : MOBILELEGEND - " class="form-control rounded-pill" style="height: 38px; width: 200px; padding: 8px; resize: none;"></textarea>
                                <button type="submit" class="btn btn-danger bg-gradient btn-label waves-effect waves-light rounded-pill inline-flex mt-2">
                                    <i class="ri-refresh-line label-icon align-middle rounded-pill fs-16 me-2" style="float: left;"></i> Replace Data
                                </button>
                                <small class="text-muted d-block mt-2">Misalkan layanan yang ingin direplace "MOBILELEGEND - 3 Diamond", masukkan saja "MOBILELEGEND - " kedalam kolom replace, maka data layanan "MOBILELEGEND - 3 Diamond" akan menjadi "3 Diamond" saja.</small>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    {{-- <form id="addServiceForm" action="{{ route('layanan.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="LayananInput" class="form-label">Nama Layanan</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="LayananInput" name="nama" placeholder="Nama Layanan" required>
                            <div class="invalid-feedback">
                                Nama Layanan harus diisi.
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="kategori" class="form-label">Kategori</label>
                        </div>
                        <div class="col-lg-10">
                            <select class="form-select" name="kategori" required>
                                <option disabled>--Pilih Kategori--</option>
                                @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Kategori harus pilih.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="kategori" class="form-label">Provider</label>
                        </div>
                        <div class="col-lg-10">
                            <select class="form-select" name="provider" required>
                                <option disabled>--Pilih Provider--</option>
                                <option value="digiflazz">Digiflazz</option>
                                <option value="vip">Vip Reseller</option>
                                <option value="manual">Manual</option>
                            </select>
                            <div class="invalid-feedback">
                                Provider harus pilih.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="LayananInput" class="form-label">Provider ID</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="provider_id" name="provider_id"
                                placeholder="ML5" required>
                            <div class="invalid-feedback">
                                Provider id harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="harga" class="form-label">Harga Umum</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="10000" required>
                            <div class="invalid-feedback">
                                Harga umum harus diisi.
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <label for="harga_member" class="form-label">Harga Member</label>
                        </div>
                        <div class="col-lg-5">
                            <input type="number" class="form-control" id="harga_member"
                                name="harga_member" placeholder="10000" required>
                            <div class="invalid-feedback">
                                Harga member harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="harga_platinum" class="form-label">Harga Platinum</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" id="harga_platinum"
                                name="harga_platinum" placeholder="10000" required>
                            <div class="invalid-feedback">
                                Harga platinum harus diisi.
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <label for="harga_gold" class="form-label">Harga Gold</label>
                        </div>
                        <div class="col-lg-5">
                            <input type="number" class="form-control" id="harga_gold"
                                name="harga_gold" placeholder="10000" required>
                            <div class="invalid-feedback">
                                Harga gold harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="profit" class="form-label">Profit Umum</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" id="profit" name="profit"
                                placeholder="5" required>
                            <div class="invalid-feedback">
                                Profit umum harus diisi.
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <label for="harga_gold" class="form-label">Profit Member</label>
                        </div>
                        <div class="col-lg-5">
                            <input type="number" class="form-control" id="profit_member" name="profit_member"
                                placeholder="4" required>
                            <div class="invalid-feedback">
                                Profit member harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="profit_platinum" class="form-label">Profit Platinum</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" id="profit_platinum" name="profit_platinum"
                                placeholder="3" required>
                            <div class="invalid-feedback">
                                Profit platinum harus diisi.
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <label for="profit_platinum" class="form-label">Profit Gold</label>
                        </div>
                        <div class="col-lg-5">
                            <input type="number" class="form-control" id="profit_platinum" name="profit_platinum"
                                placeholder="2" required>
                            <div class="invalid-feedback">
                                Profit gold harus diisi.
                            </div>
                        </div>
                    </div>


                    <small style="color:rgba(255, 0, 0, 0.678); ">*AKTIFKAN JIKA KAMU SEDANG MENGADAKAN
                        FLASHSALE</small>
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="LayananInput" class="form-label">FLASHSALE ?</label>
                        </div>
                        <div class="col-lg-10">
                            <select class="form-select" name="flash_sale">
                                <option disabled>--Pilih Flashsale--</option>
                                <option value="0">Tidak</option>
                                <option value="1">Iya</option>
                            </select>
                            <div class="invalid-feedback">
                                Flahsalae harus dipilih.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="judul_flash_sale" class="form-label">Judul Flashsale</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="judul_flash_sale" name="judul_flash_sale"
                                placeholder="Judul Flashsale">
                            <div class="invalid-feedback">
                                Judul Flashsale harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="harga_flash_sale" class="form-label">Harga Flashsale</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="number" class="form-control number-format" id="harga_flash_sale"
                                name="harga_flash_sale" placeholder="10000">
                            <div class="invalid-feedback">
                                Harga Flashsale harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="stock_flash_sale" class="form-label">Stok Flashsale</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="number" class="form-control" id="stock_flash_sale" name="stock_flash_sale"
                                placeholder="100">
                            <div class="invalid-feedback">
                                Stok Flashsale harus diisi.
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label for="stock_flash_sale" class="form-label">Flashsale Berakhir Sampai</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="datetime-local" class="form-control" name="expired_flash_sale"
                                data-provider="flatpickr" data-date-format="d.m.y" data-enable-time>
                            <div class="invalid-feedback">
                                Flashsale berakhir harus dipiplih.
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit"
                            class="btn btn-primary bg-gradient waves-effect waves-light" onclick="confirmAddService()">Simpan</button>
                        <button type="reset"
                            class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                    </div>

                    </form> --}}
                    <form id="addServiceForm" action="{{ route('layanan.post') }}" method="POST"
                        enctype="multipart/form-data"> @csrf <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label" for="example-fileinput">Nama Layanan</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" name="nama" placeholder="Nama Layanan"> @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label">Kategori</label>
                            <div class="col-lg-10">
                                <select class="form-select" name="kategori">
                                    <option disabled>--Pilih Game--</option>
                                    @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label">Provider</label>
                            <div class="col-lg-10">
                                <select class="form-select" name="provider" required>
                                    <option disabled>--Pilih Provider--</option>
                                    <option value="digiflazz">Digiflazz</option>
                                    <option value="vip">Vip Reseller</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-lg-2 col-form-label">Provider ID</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control @error('provider_id') is-invalid @enderror"
                                    value="{{ old('provider_id') }}" name="provider_id" placeholder="ML10">
                                @error('provider_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="harga" class="col-lg-1 col-form-label">Harga</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga') }}" name="harga" id="harga" placeholder="10000">
                                @error('harga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <label for="harga_member" class="col-lg-1 col-form-label">Harga Member</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('harga_member') is-invalid @enderror"
                                    value="{{ old('harga_member') }}" name="harga_member" id="harga_member"
                                    placeholder="10000">
                                @error('harga_member')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="harga_platinum" class="col-lg-1 col-form-label">Harga Platinum</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('harga_platinum') is-invalid @enderror"
                                    value="{{ old('harga_platinum') }}" name="harga_platinum" id="harga_platinum"
                                    placeholder="10000">
                                @error('harga_platinum')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <label for="harga_gold" class="col-lg-1 col-form-label">Harga Gold</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('harga_gold') is-invalid @enderror"
                                    value="{{ old('harga_gold') }}" name="harga_gold" id="harga_gold"
                                    placeholder="10000">
                                @error('harga_gold')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-lg-1 col-form-label">Profit</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('profit') is-invalid @enderror"
                                    value="{{ old('profit') }}" name="profit" placeholder="5"> @error('profit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <label for="" class="col-lg-1 col-form-label">Profit Member</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('profit_member') is-invalid @enderror"
                                    value="{{ old('profit_member') }}" name="profit_member" placeholder="5">
                                @error('profit_member')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-lg-1 col-form-label">Profit Platinum</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('profit_platinum') is-invalid @enderror"
                                    value="{{ old('profit_platinum') }}" name="profit_platinum" placeholder="5">
                                @error('profit_platinum')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <label for="" class="col-lg-1 col-form-label">Profit Gold</label>
                            <div class="col-lg-5">
                                <input type="number" class="form-control @error('profit_gold') is-invalid @enderror"
                                    value="{{ old('profit_gold') }}" name="profit_gold" placeholder="5">
                                @error('profit_gold')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <small style="color:red; ">*AKTIFKAN JIKA KAMU SEDANG MENGADAKAN FLASHSALE</small>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label">Flash Sale?</label>
                            <div class="col-lg-10">
                                <select class="form-select" name="flash_sale">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label" for="example-fileinput">Judul Flash Sale</label>
                            <div class="col-lg-10">
                                <input type="text"
                                    class="form-control @error('judul_flash_sale') is-invalid @enderror"
                                    value="{{ old('judul_flash_sale') }}" name="judul_flash_sale">
                                @error('judul_flash_sale')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label" for="example-fileinput">Harga Flash Sale</label>
                            <div class="col-lg-10">
                                <input type="number"
                                    class="form-control @error('harga_flash_sale') is-invalid @enderror" value="0"
                                    name="harga_flash_sale" id="harga_flash_sale"> @error('harga_flash_sale')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label" for="example-fileinput">Stock Flash Sale</label>
                            <div class="col-lg-10">
                                <input type="number"
                                    class="form-control @error('stock_flash_sale') is-invalid @enderror" value="0"
                                    name="stock_flash_sale" id="stock_flash_sale"> @error('stock_flash_sale')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <script>
                            document.querySelectorAll('input[type="number"]').forEach(function(input) {
                                input.addEventListener('input', function() {
                                    if (this.value < 0) {
                                        this.value = 0;
                                    }
                                });
                            });
                        </script>
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label" for="example-fileinput">Expired Flash Sale</label>
                            <div class="col-lg-10">
                                <input type="datetime-local"
                                    class="form-control @error('expired_flash_sale') is-invalid @enderror"
                                    value="{{ old('expired_flash_sale') }}" name="expired_flash_sale"
                                    data-provider="flatpickr" data-date-format="d.m.y" data-enable-time>
                                @error('expired_flash_sale')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit"
                                class="btn btn-primary bg-gradient waves-effect waves-light">Simpan</button>
                            <button type="reset"
                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">List Layanan</h5>
                </div>
                <div class="card-body">
                    <div id="loadingSpinner" class="text-center">
                        <div class="spinner-grow text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive d-none" id="dataTableContainer">
                        <table
                            class="table table-bordered nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                            id="layanan-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kategori</th>
                                    <th>Layanan</th>
                                    <th>Provider</th>
                                    <th>PID</th>
                                    <th>Harga</th>
                                    <th>Harga Member</th>
                                    <th>Harga Platinum</th>
                                    <th>Harga Gold</th>
                                    <th>Profit</th>
                                    <th>Profit Member</th>
                                    <th>Profit Platinum</th>
                                    <th>Profit Gold</th>
                                    <th>Harga Flash Sale</th>
                                    <th>Flash Sale?</th>
                                    <th>Judul Flash Sale</th>
                                    <th>Expired Flash Sale</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                    {{-- <th>Tanggal</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                @php
                                $label_pesanan = '';
                                if ($data->status == 'available') {
                                $label_pesanan = 'success';
                                } elseif ($data->status == 'unavailable') {
                                $label_pesanan = 'danger';
                                }
                                @endphp
                                <tr>
                                    <th scope="row">{{ $data->id }}</th>
                                    <td>{{ $data->nama_kategori }}</td>
                                    <td>{{ $data->layanan }}</td>
                                    <td>{{ $data->provider }}</td>
                                    <td>{{ $data->provider_id }}</td>
                                    <td>Rp. {{ number_format($data->harga, 0, '.', ',') }}</td>
                                    <td>Rp. {{ number_format($data->harga_member, 0, '.', ',') }}</td>
                                    <td>Rp. {{ number_format($data->harga_platinum, 0, '.', ',') }}</td>
                                    <td>Rp. {{ number_format($data->harga_gold, 0, '.', ',') }}</td>
                                    <td>{{ number_format($data->profit, 0, '.', ',') }}% (Rp.
                                        {{ $data->harga * ($data->profit / 100) }})
                                    </td>
                                    <td>{{ number_format($data->profit_member, 0, '.', ',') }}% (Rp.
                                        {{ $data->harga_member * ($data->profit_member / 100) }})
                                    </td>
                                    <td>{{ number_format($data->profit_platinum, 0, '.', ',') }}% (Rp.
                                        {{ $data->harga_platinum * ($data->profit_platinum / 100) }})
                                    </td>
                                    <td>{{ number_format($data->profit_gold, 0, '.', ',') }}% (Rp.
                                        {{ $data->harga_gold * ($data->profit_gold / 100) }})
                                    </td>
                                    <td>Rp. {{ number_format($data->harga_flash_sale, 0, '.', ',') }}</td>
                                    <td>{{ $data->is_flash_sale == 0 ? 'No' : 'Yes' }}</td>
                                    <td>{{ $data->judul_flash_sale }}</td>
                                    <td>{{ $data->expired_flash_sale }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-{{ $label_pesanan }}">{{ $data->status }}</button>
                                            <button type="button"
                                                class="btn btn-{{ $label_pesanan }} dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:;"
                                                        onclick="changeStatus('{{ $data->id }}', 'available')">available</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:;"
                                                        onclick="changeStatus('{{ $data->id }}', 'unavailable')">unavailable</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"
                                                        onclick="modal('{{ $data->layanan }}', '{{ route('layanan.detail', [$data->id]) }}')">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"
                                                        onclick="openDeleteModal('{{ $data->id }}')">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    {{-- <td>{{ $data->created_at }}</td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal untuk Edit -->
            <div class="modal fade zoomIn" id="modal-detail" tabindex="-1" aria-labelledby="modal-detail-title" aria-hidden="true">
                <div class="modal-dialog modal-lg ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-detail-title">Detail Layanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal-detail-body">
                            <!-- Konten akan dimuat di sini melalui AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Delete -->
    <div id="removeItemModal" class="modal fade flip" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this user?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <a id="confirmDeleteButton" href="#" class="btn w-sm btn-danger">Yes, Delete It!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            'use strict'
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#loadingSpinner').addClass('d-none');
                $('#dataTableContainer').removeClass('d-none');
                $('#layanan-table').DataTable();
            }, 500); // Adjust the delay as needed
        });
    </script>

    <script type="text/javascript">
        function openDeleteModal(dataId) {
            // Buat URL berdasarkan ID pengguna
            const deleteUrl = `/layanan/hapus/${dataId}`;

            // Set URL ke tombol konfirmasi delete di modal
            document.getElementById('confirmDeleteButton').setAttribute('href', deleteUrl);

            // Tampilkan modal
            const deleteModal = new bootstrap.Modal(document.getElementById('removeItemModal'));
            deleteModal.show();
        }

        function changeStatus(id, status) {
            Swal.fire({
                title: 'Ubah Status Layanan',
                text: "Apakah Anda yakin ingin mengubah status layanan ini menjadi " + status + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/layanan-status/' + id + '/' + status;
                }
            });
        }

        function confirmAddService() {
            Swal.fire({
                title: 'Tambah Layanan',
                text: "Apakah Anda yakin ingin menambah layanan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('addServiceForm').submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Layanan',
                text: "Apakah Anda yakin ingin menghapus layanan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/layanan/hapus/' + id;
                }
            });
        }

        $(document).ready(function() {
            $('.table').DataTable();
        });

        function modal(name, link) {
            var myModal = new bootstrap.Modal($('#modal-detail'))
            $.ajax({
                type: "GET",
                url: link,
                beforeSend: function() {
                    $('#modal-detail-title').html(name);
                    $('#modal-detail-body').html('Loading...');
                },
                success: function(result) {
                    $('#modal-detail-title').html(name);
                    $('#modal-detail-body').html(result);
                    $('#modal-detail-body').css({
                        'overflow-y': 'auto',
                        'max-height': '70vh'
                    });
                },
                error: function() {
                    $('#modal-detail-title').html(name);
                    $('#modal-detail-body').html('There is an error...');
                }
            });
            myModal.show();
        }
    </script>

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('assets/admin/js/pages/datatables.init.js') }}"></script>
    @endsection
