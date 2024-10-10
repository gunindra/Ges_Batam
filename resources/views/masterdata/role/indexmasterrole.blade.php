@extends('layout.main')

@section('title', 'Master Data | Role')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahRole" tabindex="-1" role="dialog" aria-labelledby="modalTambahRoleTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahRole">Tambah Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="roleMaster" class="form-label fw-bold">Role</label>
                        <input type="text" class="form-control" id="roleMaster" value="" placeholder="Masukkan Role">
                        <div id="roleMasterError" class="text-danger mt-1 d-none">Silahkan isi Role</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveRoleMaster" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditRole" tabindex="-1" role="dialog" aria-labelledby="modalEditRoleTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditRoleTitle">Edit Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="roleMaster" class="form-label fw-bold">Role</label>
                        <input type="text" class="form-control" id="roleMasterEdit" value=""
                            placeholder="Masukkan role">
                        <div id="roleMasterErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Role</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditRole" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalMenuAkses" tabindex="-1" role="dialog" aria-labelledby="modalTambahMenuAksesTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMenuAksesTitle">Menu Akses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="menuAksesId">
                    <div class="CheckAll mt-1">
                        <input type="checkbox" class="largerCheckbox" id="chkAll" />
                        <label for="selectAll" class="form-label fw-bold">Select All</label>
                    </div>
                    <!-- dashboard -->
                    <div class="dashboard mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="dashboard" class="form-label fw-bold">Dashboard</label>
                    </div>
                    <!-- customer -->
                    <div class="customer mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="customer" class="form-label fw-bold">Customer</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="invoice" class="form-label fw-bold">Invoice</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="delivery" class="form-label fw-bold">Delivery</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="pickup" class="form-label fw-bold">Pickup</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="payment" class="form-label fw-bold">Payment</label>
                    </div>
                    <!-- vendor -->
                    <div class="mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="vendor" class="form-label fw-bold">Vendor</label>
                    </div>
                    <!-- content -->
                    <div class="content mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="content" class="form-label fw-bold">Content</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="about" class="form-label fw-bold">About</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="why" class="form-label fw-bold">Why</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="service" class="form-label fw-bold">Service</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="information" class="form-label fw-bold">Information</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="carousel" class="form-label fw-bold">Carousel</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="iklan" class="form-label fw-bold">Iklan</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="popup" class="form-label fw-bold">Popup</label>
                    </div>
                    <!-- Tracking -->
                    <div class="mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="tracking" class="form-label fw-bold">Tracking</label>
                    </div>
                    <!-- master data -->
                    <div class="masterData mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="masterData" class="form-label fw-bold">Master Data</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="costumers" class="form-label fw-bold">Costumers</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="driver" class="form-label fw-bold">Driver</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="rekening" class="form-label fw-bold">Rekening</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="rate" class="form-label fw-bold">Rate</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="category" class="form-label fw-bold">Category</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="role" class="form-label fw-bold">Role</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="user" class="form-label fw-bold">User</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveMenuAkses" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Role</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Role</li>
        </ol>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-links nav-link active" aria-current="page" href="#" data-tab="roleTab">Role
                Master</a>
        </li>
        <li class="nav-item">
            <a class="nav-links nav-link" href="#" data-tab="aksesTab">Menu Akses</a>
        </li>
    </ul>
    <div class="tab-content mt-3">
        <!-- Role Content -->
        <div id="roleTab" class="tab-pane fade show active">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="float-left mb-3">
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                            </div>
                            <div class="d-flex mb-2 mr-3 float-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalTambahRole" id="#modalCenter"><span class="pr-2"><i
                                            class="fas fa-plus"></i></span>Tambah Role</button>
                            </div>
                            <div id="containerRole" class="table-responsive px-3 ">
                                <!-- <table class="table align-items-center table-flush table-hover" id="tableRole">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Pemilik</th>
                                                <th>No. Rekening</th>
                                                <th>Bank</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Ilham</td>
                                                <td>291037292</td>
                                                <td>BCA</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary"><i
                                                            class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tio</td>
                                                <td>1192837432</td>
                                                <td>Mandiri</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary"><i
                                                            class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Akses Content -->
        <div id="aksesTab" class="tab-pane fade">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="float-left mb-3">
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                            </div>
                            <div id="containerMenuAkses" class="table-responsive px-3 ">
                                <!-- <table class="table align-items-center table-flush table-hover" id="tableAkses">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>nama</th>
                                                <th>halo</th>
                                                <th>Bcuk</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Ilhamd</td>
                                                <td>291037292sadasdada</td>
                                                <td>BCAdwadaw</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary"><i
                                                            class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tiodwadaw</td>
                                                <td>1192837432dwadaw</td>
                                                <td>awdawdaw</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary"><i
                                                            class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('.nav-links').on('click', function (e) {
            e.preventDefault();
            var tab = $(this).data('tab');

            $('.tab-pane').removeClass('show active');
            $('#' + tab).addClass('show active');

            $('.nav-links').removeClass('active');
            $(this).addClass('active');
        });
        // Select All functionality
        $('#chkAll').click(function () {
            $('input.tblChk').prop('checked', this.checked);
        });

        // Function to update Select All status
        function updateSelectAllStatus() {
            var allChecked = $('input.tblChk').length === $('input.tblChk:checked').length;
            $('#chkAll').prop('checked', allChecked);
        }

        // Customer checkboxes group
        $('.customer input[type="checkbox"]').click(function () {
            var isChecked = $(this).is(':checked');
            // Check/uncheck all checkboxes in customer and check1 group
            $('.customer input[type="checkbox"], .check1 input[type="checkbox"]').prop('checked',
                isChecked);
            // Update Select All status
            updateSelectAllStatus();
        });

        $('.check1 input[type="checkbox"]').click(function () {
            var allChecked = $('.check1 input[type="checkbox"]').length === $(
                '.check1 input[type="checkbox"]:checked').length;
            if (allChecked) {
                $('.customer input[type="checkbox"]').prop('checked', true);
            } else {
                $('.customer input[type="checkbox"]').prop('checked', false);
            }
            // Update Select All status
            updateSelectAllStatus();
        });

        // Content checkboxes group
        $('.content input[type="checkbox"]').click(function () {
            var isChecked = $(this).is(':checked');
            $('.content input[type="checkbox"], .check2 input[type="checkbox"]').prop('checked',
                isChecked);
            updateSelectAllStatus();
        });

        $('.check2 input[type="checkbox"]').click(function () {
            var allChecked = $('.check2 input[type="checkbox"]').length === $(
                '.check2 input[type="checkbox"]:checked').length;
            if (allChecked) {
                $('.content input[type="checkbox"]').prop('checked', true);
            } else {
                $('.content input[type="checkbox"]').prop('checked', false);
            }
            updateSelectAllStatus();
        });

        // Master Data checkboxes group
        $('.masterData input[type="checkbox"]').click(function () {
            var isChecked = $(this).is(':checked');
            $('.masterData input[type="checkbox"], .check3 input[type="checkbox"]').prop('checked',
                isChecked);
            updateSelectAllStatus();
        });

        $('.check3 input[type="checkbox"]').click(function () {
            var allChecked = $('.check3 input[type="checkbox"]').length === $(
                '.check3 input[type="checkbox"]:checked').length;
            if (allChecked) {
                $('.masterData input[type="checkbox"]').prop('checked', true);
            } else {
                $('.masterData input[type="checkbox"]').prop('checked', false);
            }
            updateSelectAllStatus();
        });

        // When any individual checkbox in tblChk is clicked, update Select All status
        $('input.tblChk').click(function () {
            updateSelectAllStatus();
        });
    });
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistRole = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistRole') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerRole').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerRole').html(res)
                    $('#tableRole').DataTable({
                        searching: false,
                        lengthChange: false,
                        "bSort": true,
                        "aaSorting": [],
                        pageLength: 7,
                        "lengthChange": false,
                        responsive: true,
                        language: {
                            search: ""
                        }
                    });
                })
        }

        getlistRole();
        $('#txSearch').keyup(function (e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getlistRole();
            }
        });

        $('#saveRoleMaster').click(function () {
            var roleMaster = $('#roleMaster').val().trim();

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (roleMaster === '') {
                $('#roleMasterError').removeClass('d-none');
                isValid = false;
            } else {
                $('#roleMasterError').addClass('d-none');
            }
            if (isValid) {
                Swal.fire({
                    title: "Apakah Kamu Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your role.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: '/masterdata/role/store',
                            method: 'POST',
                            data: {
                                roleMaster: roleMaster,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success",
                                        "berhasil ditambahkan");
                                        $('#modalTambahRole').modal('hide');
                                        getlistRole();
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                if (response.status === 422) {
                                    showMessage("error", "Role yang dimasukkan sudah ada. Silakan masukkan role yang berbeda.");
                                } else {
                                showMessage("error","Terjadi kesalahan, coba lagi nanti");
                                }
                            }
                        });
                    }
                });
            }
        });
        $('#modalTambahRole').on('hidden.bs.modal', function () {
            $('#roleMaster').val('');
            if (!$('#roleMasterError').hasClass('d-none')) {
                $('#roleMasterError').addClass('d-none');

            }
            if (!$('#roleMasterError').hasClass('d-none')) {
                $('#roleMasterError').addClass('d-none');

            }
        });
        $(document).on('click', '.btnUpdateRole', function (e) {
            var RoleId = $(this).data('id');
            $.ajax({
                url: '/masterdata/role/' + RoleId,
                method: 'GET',
                success: function (response) {
                    $('#roleMasterEdit').val(response.role);
                    $('#modalEditRole').modal('show');
                    $('#saveEditRole').data('id', RoleId);
                },
                error: function () {
                    showMessage("error", "Terjadi kesalahan saat mengambil data");
                }
            });
        });

        $('#saveEditRole').on('click', function () {
            var RoleId = $(this).data('id');
            var roleMaster = $('#roleMasterEdit').val();

            let isValid = true;

            if (roleMaster === '') {
                $('roleMasterErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('roleMasterErrorEdit').addClass('d-none');
            }

            if (isValid) {
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Anda akan memperbarui ini",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Harap tunggu, sedang memperbarui data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '/masterdata/role/update/' + RoleId,
                            method: 'PUT',
                            data: {
                                roleMaster: roleMaster,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", "berhasil diperbarui")
                                        .then(
                                            () => {
                                                location.reload();
                                            });
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                            }
                        });
                    }
                });
            }
        });
        $('#modalEditRole').on('hidden.bs.modal', function () {
            $('#roleMasterEdit').val('');
            if (!$('#roleMasterErrorEdit').hasClass('d-none')) {
                $('#roleMasterErrorEdit').addClass('d-none');

            }
            if (!$('#roleMasterErrorEdit').hasClass('d-none')) {
                $('#roleMasterErrorEdit').addClass('d-none');

            }
        });
        $(document).on('click', '.btnDestroyRole', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Kamu Yakin Ingin Hapus Ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your role.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: '/masterdata/role/destroy/'+ id,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: id,
                        },
                        success: function (response) {
                            Swal.close();

                            if (response.url) {
                                window.open(response.url, '_blank');
                            } else if (response.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error
                                });
                            }
                            if (response.status === 'success') {
                                showMessage("success", "Berhasil menghapus");
                                getlistRole();
                                getlistMenu();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
            });

        });

        const getlistMenu = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistMenu') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerMenuAkses').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerMenuAkses').html(res)
                    $('#tableMenuAkses').DataTable({
                        searching: false,
                        lengthChange: false,
                        "bSort": true,
                        "aaSorting": [],
                        pageLength: 7,
                        "lengthChange": false,
                        responsive: true,
                        language: {
                            search: ""
                        }
                    });
                })
        }

        getlistMenu();

        $(document).ready(function () {
            // Saat tombol edit ditekan
            $(document).on('click', '.btnUpdateMenuAkses', function () {
                var id = $(this).data('id'); // ambil data-id dari tombol
                var role = $(this).data('role'); // ambil data-role dari tombol

                // Set nilai id dan role ke dalam input di modal
                $('#menuAksesId').val(id);
                $('#modalMenuAksesTitle').text('Edit Menu Akses'); // Set judul modal

                // Tampilkan modal
                $('#modalMenuAkses').modal('show');
            });
        });

    });
</script>
@endsection