@extends('layouts.master')
@section('title')
    {{ ENV('APP_NAME') }} - Kelola Member
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
                <h4 class="mb-sm-0">Member</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('member') }}">Member</a></li>
                        <li class="breadcrumb-item active">Kelola Member</li>
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
  <div class="col-xxl-6"> 
    <div class="card"> 
      <div class="card-header"> 
        <h5 class="card-title mb-0">Kelola Member</h5> 
      </div> 
      <div class="card-body"> 
        <form id="addMemberForm" action="{{ route('member.post') }}" method="POST" class="needs-validation" novalidate> 
          @csrf 
          <div class="row"> 
            <div class="col-6"> 
              <div class="mb-3"> 
                <label for="nama" class="form-label">Nama Lengkap</label> 
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan nama lengkap" required> 
                <div class="invalid-feedback"> Nama lengkap harus diisi. </div> 
              </div> 
            </div><!--end col--> 
            <div class="col-6"> 
              <div class="mb-3"> 
                <label for="username" class="form-label">Username</label> 
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukan username" required> 
                <div class="invalid-feedback"> Username harus diisi. </div> 
              </div> 
            </div><!--end col--> 
            <div class="col-6"> 
              <div class="mb-3"> 
                <label for="email" class="form-label">Email</label> 
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email" required> 
                <div class="invalid-feedback"> Email harus diisi dengan format yang valid. </div> 
              </div> 
            </div><!--end col--> 
            <div class="col-6"> 
              <div class="mb-3"> 
                <label for="password" class="form-label">Password</label> 
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password" required> 
                <div class="invalid-feedback"> Password harus diisi. </div> 
              </div> 
            </div><!--end col--> 
            <div class="col-6"> 
              <div class="mb-3"> 
                <label for="no_wa" class="form-label">No. Whatsapp</label> 
                <input type="number" class="form-control" id="no_wa" name="no_wa" placeholder="628xxxxxxxx" required> 
                <div class="invalid-feedback"> No. Whatsapp harus diisi dengan nomor yang valid. </div> 
              </div> 
            </div><!--end col--> 
            <div class="col-6"> 
              <div class="mb-3"> 
                <label for="role" class="form-label">Role</label> 
                <select class="form-control" id="role" name="role" required> 
                  <option value="Member">Member</option> 
                  <option value="Platinum">Platinum</option> 
                  <option value="Gold">Gold</option> 
                </select> 
                <div class="invalid-feedback"> Role harus dipilih. </div> 
              </div> 
            </div><!--end col--> 
            <div class="col-lg-12"> 
              <div class="text-end"> 
                <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light">Buat Member</button> 
                <button type="reset" class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button> 
              </div> 
            </div><!--end col--> 
          </div><!--end row--> 
        </form> 
      </div> 
    </div> 
  </div>

        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kirim saldo</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('saldo.post') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Masukan username" required>
                            <div class="invalid-feedback">
                                Username harus diisi.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="balance" class="form-label">Nominal saldo</label>
                            <input type="number" class="form-control" id="balance" name="balance"
                                placeholder="Masukan nominal saldo" min="1000" required>
                            <div class="invalid-feedback">
                                Nominal saldo harus diisi dan minimal Rp1.000.
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit"
                                class="btn btn-primary bg-gradient waves-effect waves-light">Kirim</button>
                            <button type="reset"
                                class="btn btn-dark bg-gradient waves-effect waves-light">Reset</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Daftar Member
                    </h5>
                </div>
                <div class="card-body">
                    <div id="loadingSpinner" class="text-center">
                        <div class="spinner-grow text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive d-none" id="dataTableContainer">
                        <table id="userTable" class="table table-bordered nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>No Whatsapp</th>
                                    <th>Saldo</th>
                                    <th>Level</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->no_wa }}</td>
                                        <td>Rp. {{ number_format($user->balance, 0, ',', '.') }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->created_at }}</td>
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
                                                            onclick="confirmEdit('{{ $user->username }}', '{{ route('member.detail', [$user->id]) }}')">
                                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <!-- Delete Action -->
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item"
                                                            onclick="openDeleteModal('{{ $user->id }}')">
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
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
    <div id="removeItemModal" class="modal fade zoomIn" tabindex="-1" aria-modal="true" role="dialog">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmAddMember() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pastikan data member yang anda buat sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, buat!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('addMemberForm').submit();
                }
            })
        }

        function openDeleteModal(userId) {
            // Buat URL berdasarkan ID pengguna
            const deleteUrl = `/member/${userId}/delete`;

            // Set URL ke tombol konfirmasi delete di modal
            document.getElementById('confirmDeleteButton').setAttribute('href', deleteUrl);

            // Tampilkan modal
            const deleteModal = new bootstrap.Modal(document.getElementById('removeItemModal'));
            deleteModal.show();
        }

        function confirmEdit(username, url) {
            Swal.fire({
                title: 'Edit User',
                text: `Apakah Anda yakin ingin mengedit pengguna ${username}?`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, edit!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    openModal(username, url);
                }
            })
        }

        function openModal(name, link) {
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
    </script>

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
