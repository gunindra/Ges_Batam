@extends('layout.main')

@section('title', 'Master Data | User')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
<div class="modal fade" id="modalTambahUsers" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahUsersTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahUsers">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="usersForm" enctype="multipart/form-data">
                        <div class="mt-3">
                            <label for="nameUsers" class="form-label fw-bold">name</label>
                            <input type="text" class="form-control" id="nameUsers" value=""
                                placeholder="Masukkan nama user">
                            <div id="nameUsersError" class="text-danger mt-1 d-none">Silahkan isi Name</div>
                        </div>
                        <div class="mt-3">
                            <label for="emailUsers" class="form-label fw-bold">Email</label>
                            <input type="text" class="form-control" id="emailUsers" value=""
                                placeholder="Masukkan email user">
                            <div id="emailUsersError" class="text-danger mt-1 d-none">Silahkan isi Email</div>
                        </div>
                        <div class="mt-3">
                            <label for="roleUsers" class="form-label fw-bold">Role</label>
                            <input type="text" class="form-control" id="roleUsers" value=""
                                placeholder="Masukkan role user">
                            <div id="roleUsersError" class="text-danger mt-1 d-none">Silahkan isi Role</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                            <button type="button" id="saveUsers" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditUsers" tabindex="-1" role="dialog"
        aria-labelledby="modalEditUsersTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditUsersTitle">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="usersIdEdit">
                    <div class="mt-3">
                        <label for="nameUsersEdit" class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" id="nameUsersEdit" value=""
                            placeholder="Masukkan nama user">
                        <div id="nameUsersErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Name</div>
                    </div>
                    <div class="mt-3">
                        <label for="emailUsersEdit" class="form-label fw-bold">Email</label>
                        <input type="text" class="form-control" id="emailUsersEdit" value=""
                            placeholder="Masukkan email user">
                        <div id="emailUsersErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Email</div>
                    </div>
                    <div class="mt-3">
                        <label for="roleUsersEdit" class="form-label fw-bold">Role</label>
                        <input type="text" class="form-control" id="roleUsersEdit" value=""
                            placeholder="Masukkan role user">
                        <div id="roleUsersErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Role</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditUsers" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">User</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="float-left">
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                    </div>
                    <div class="d-flex mb-3 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahUsers" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah User</button>
                    </div>
                    <div id="containerUser" class="table-responsive px-3 ">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableUser">
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
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tio</td>
                                    <td>1192837432</td>
                                    <td>Mandiri</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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

@endsection


@section('script')
<script>
      $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getListUser = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistUser') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerUser').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerUser').html(res)
                        $('#tableUser').DataTable({
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

            getListUser();

            $('#saveUsers').click(function () {
            // Ambil nilai input
            var nameUsers = $('#nameUsers').val().trim();
            var emailUsers = $('#emailUsers').val().trim();
            var roleUsers = $('#roleUsers').val().trim();

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (nameUsers === '') {
                $('#nameUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#nameUsersError').addClass('d-none');
            }
            if (emailUsers === '') {
                $('#emailUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#emailUsersError').addClass('d-none');
            }
            if (roleUsers === '') {
                $('#roleUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#roleUsersError').addClass('d-none');
            }

            // Jika semua input valid, lanjutkan aksi simpan
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
                        var formData = new FormData();
                        formData.append('nameUsers', nameUsers);
                        formData.append('emailUsers', emailUsers);
                        formData.append('roleUsers', roleUsers);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addUsers') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Data Berhasil Disimpan");
                                        getListUser();
                                    $('#modalTambahUsers').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
                                        text: response
                                            .message,
                                        icon: "error",
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: "Gagal Menambahkan Data",
                                    text: xhr.responseJSON
                                        .message,
                                    icon: "error",
                                });
                            }
                        });
                    }
                });
            } else {
                showMessage("error", "Mohon periksa input yang kosong");
            }
        });

        $('#modalTambahUsers').on('hidden.bs.modal', function () {
            $('#nameUsers,#emailUsers,#roleUsers').val('');
            if (!$('#nameUsersError').hasClass('d-none')) {
                $('#nameUsersError').addClass('d-none');
            }
            if (!$('#emailUsersError').hasClass('d-none')) {
                $('#emailUsersError').addClass('d-none');
            }
            if (!$('#roleUsersError').hasClass('d-none')) {
                $('#roleUsersError').addClass('d-none');
            }
        });
        $(document).on('click', '.btnUpdateUsers', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            let email = $(this).data('email');
            let role = $(this).data('role');

            $('#nameUsersEdit').val(name);
            $('#emailUsersEdit').val(email);
            $('#roleUsersEdit').val(role);
            $('#usersIdEdit').val(id);

            $(document).on('click', '#saveEditUsers', function (e) {

                let id = $('#usersIdEdit').val();
                let nameUsers = $('#nameUsersEdit').val();
                let emailUsers = $('#emailUsersEdit').val();
                let roleUsers = $('#roleUsersEdit').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (nameUsers === '') {
                    $('#nameUsersErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nameUsersErrorEdit').addClass('d-none');
                }

                // Validasi Content
                if (emailUsers === '') {
                    $('#emailUsersErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#emailUsersErrorEdit').addClass('d-none');
                }

                if (roleUsers === '') {
                    $('#roleUsersErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#roleUsersErrorEdit').addClass('d-none');
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
                            let formData = new FormData();
                            formData.append('id', id);
                            formData.append('nameUsers', nameUsers);
                            formData.append('emailUsers', emailUsers);
                            formData.append('roleUsers', roleUsers);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateUsers') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function (response) {
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Diubah");
                                            getListUser();
                                        $('#modalEditUsers').modal(
                                            'hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan",
                                            icon: "error"
                                        });
                                    }
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Mohon periksa input yang kosong");
                }
            })

            // validateInformationsInput('modalEditInformations');
            $('#modalEditUsers').modal('show');
        });
        $('#modalEditUsers').on('hidden.bs.modal', function () {
            $('#nameUsersEdit,#emailUsersEdit,#roleUsersEdit').val('');
            if (!$('#nameUsersErrorEdit').hasClass('d-none')) {
                $('#nameUsersErrorEdit').addClass('d-none');
            }
            if (!$('#emailUsersErrorEdit').hasClass('d-none')) {
                $('#emailUsersErrorEdit').addClass('d-none');
            }
            if (!$('#roleUsersErrorEdit').hasClass('d-none')) {
                $('#roleUsersErrorEdit').addClass('d-none');
            }
        });
        $(document).on('click', '.btnDestroyUsers', function (e) {
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
                    $.ajax({
                        type: "GET",
                        url: "{{ route('destroyUsers') }}",
                        data: {
                            id: id,
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                showMessage("success",
                                    "Berhasil menghapus");
                                    getListUser();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
            });

        });
    });
    
</script>

@endsection