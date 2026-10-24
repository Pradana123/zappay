@extends('layouts.master')
@section('title')
    {{ ENV('APP_NAME') }} - Payment
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
                <h4 class="mb-sm-0">Konfigurasi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Konfigurasi</li>
                        <li class="breadcrumb-item"><a href="{{ url('method') }}">Payment</a></li>
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('method.post') }}" method="POST" enctype="multipart/form-data" id="berita"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="nama_pembayarn">Nama Pembayaran</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Masukan nama pembayaran" required>
                                </div>
                                <div class="invalid-feedback">
                                    Nama pembayaran harus diisi.
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="code">Kode Pembayaran</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="code" placeholder="Masukan kode pembayaran" required>
                                    <span class='small text-danger'>pakai code (SALDO) untuk metode saldo</span>
                                </div>
                                <div class="invalid-feedback">
                                    Kode pembayaran harus diisi.
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="code">Keterangan</label>
                                <div class="col-lg-10">
                                    {{-- <input type="text" class="form-control" name="code" required> --}}
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Masukan keterangan"
                                        required></textarea>
                                </div>
                                <div class="invalid-feedback">
                                    Keterangan harus diisi.
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Payment Provider</label>
                                <div class="col-lg-10">
                                    <select class="form-select" name="payment" id="payment" required>
                                        <option value="tokopay">Tokopay</option>
                                        <option value="paydisini">Paydisini</option>
                                        <option value="tripay">Tripay</option>
                                        <option value="SALDO">Saldo</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback">
                                    Payment provider harus dipilih.
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Payment Tipe</label>
                                <div class="col-lg-10">
                                    <select class="form-select" name="tipe" id="tipe" required>
                                        <option value="SALDO">Saldo Account</option>
                                        <option value="qris">QRIS</option>
                                        <option value="e-walet">E-Wallet</option>
                                        <option value="virtual-account">Virtual Account</option
                                        >
                                    </select>
                                </div>
                                <div class="invalid-feedback">
                                    Payment tipe harus dipilih.
                                </div>
                            </div>
                            <div class="mb-3 row" id="payment_number_div" style="display: none;">
                                <label class="col-lg-2 col-form-label" id="payment_number_label">Payment Number</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="payment_number" id="payment_number" placeholder="Enter payment number">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="fix_fee" class="form-label">Fix Fee</label>
                                </div>
                                <div class="col-lg-4">
                                    <input type="number" class="form-control" id="fix_fee" name="fix_fee" placeholder="3" required>
                                    <div class="invalid-feedback">Fix Fee is required.</div>
                                </div>
                                <div class="col-lg-1">
                                    <label for="fee_percent" class="form-label">Fee Percent</label>
                                </div>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" id="fee_percent" name="fee_percent" placeholder="1 / 0.1 / 0.01" required>
                                    <span class="text-danger">Use (.) not (,)</span>
                                    <div class="invalid-feedback">Fee Percent is required.</div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="images">Logo</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control" name="images" required>
                                </div>
                                <div class="invalid-feedback">
                                    Logo harus diisi.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light">Tambah
                                    Pembayaran</button>
                                <button type="reset"
                                    class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                            </div>
                        </div><!--end col-->
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Semua Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div id="loadingSpinner" class="text-center">
                        <div class="spinner-grow text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive d-none" id="dataTableContainer">
                        <table id="paymentTable"
                            class="table table-bordered nowrap table-striped align-middle dataTable no-footer dtr-inline mb-0"
                            style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Fix Fee</th>
                                    <th>Fee Percent</th>
                                    <th>Provider Pembayaran</th>
                                    <th>Kode Pembayaran</th>
                                    <th>Informasi Pembayaran</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Logo Pembayaran</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $datas)
                                    <tr>
                                        <th scope="row">{{ $datas->id }}</th>
                                        <td>{{ $datas->name }}
                                            <div style="margin-top: 5px; font-size: 0.9em; color: #6c757d;">
                                                {{ $datas->created_at }}
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('method.toggleStatus', $datas->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button disabled type="submit" class="btn btn-sm {{ $datas->statuspayment === 'Activate' ? 'btn-success' : 'btn-warning' }}">
                                                    {{ $datas->statuspayment === 'Activate' ? 'Activated' : 'Disabled' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $datas->fix_fee }}</td>
                                        <td>{{ $datas->fee_percent }}</td>
                                        <td>{{ $datas->payment }}</td>
                                        <td>{{ $datas->code }}</td>
                                        <td>{{ $datas->keterangan }}</td>
                                        <td>{{ $datas->tipe }}</td>
                                        <td>

                                            <img src="{{ asset($datas->images) }}" width="55"
                                                style="border-radius: 10px;">
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <!-- Edit Action -->
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item"
                                                            onclick="modal('{{ $datas->nama }}', '{{ route('method.detail', [$datas->id]) }}')">
                                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <!-- Delete Action -->
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item"
                                                            onclick="openDeleteModal('{{ $datas->id }}')">
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <a href="javascript:;"
                                                onclick="modal('{{ $datas->nama }}', '{{ route('method.detail', [$datas->id]) }}')"
                                                class="btn-sm btn-info mb-3">Edit</a>
                                            <br>
                                            <br>
                                            <a class="btn-sm btn-danger mt-2"
                                                href="/method/hapus/{{ $datas->id }}">Hapus</a>
                                        </td> --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="modal-detail"
        style="border-radius:7%">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-detail-title"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-detail-body"></div>
            </div>
        </div>
    </div>
    <!-- Modal Delete -->
    <div id="removeItemModal" class="modal flip" tabindex="-1" aria-modal="true" role="dialog">
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
    <script>
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
                },
                error: function() {
                    $('#modal-detail-title').html(name);
                    $('#modal-detail-body').html('There is an error...');
                }
            });
            myModal.show();
        }

        function openDeleteModal(dataId) {
            // Buat URL berdasarkan ID pengguna
            const deleteUrl = `/method/hapus/${dataId}`;

            // Set URL ke tombol konfirmasi delete di modal
            document.getElementById('confirmDeleteButton').setAttribute('href', deleteUrl);

            // Tampilkan modal
            const deleteModal = new bootstrap.Modal(document.getElementById('removeItemModal'));
            deleteModal.show();
        }
    </script>
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
                $('#userTable').DataTable();
            }, 500); // Adjust the delay as needed

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#payment').change(function() {
                var payment = $(this).val();
                var tipeSelect = $('#tipe');
                if (payment === 'manual') {
                    tipeSelect.html('<option value="Bank Transfer">Bank Transfer</option><option value="DANA">DANA</option>');
                    $('#payment_number_div').show();
                    updateLabel();
                } else {
                    tipeSelect.html('<option value="SALDO">Saldo Account</option><option value="qris">QRIS</option><option value="e-walet">E-Wallet</option>');
                    $('#payment_number_div').hide();
                }
            });
            $('#tipe').change(function() {
                updateLabel();
            });
            function updateLabel() {
                var tipe = $('#tipe').val();
                if (tipe === 'Bank Transfer') {
                    $('#payment_number_label').text('No Rekening');
                    $('#payment_number').attr('placeholder', 'Masukkan no rekening');
                } else if (tipe === 'DANA') {
                    $('#payment_number_label').text('Nomor DANA');
                    $('#payment_number').attr('placeholder', 'Masukkan nomor DANA');
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.table').DataTable({
                order: [],
                columnDefs: [{
                    targets: 0,
                    orderable: true,
                    orderData: [0],
                    orderSequence: ['desc']
                }]
            });
        });
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
