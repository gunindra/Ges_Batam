<?php $__env->startSection('title', 'Master Data | User'); ?>

<?php $__env->startSection('main'); ?>
<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="modal fade" id="modalTambahUsers" tabindex="-1" role="dialog" aria-labelledby="modalTambahUsersTitle"
        aria-hidden="true">
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
                            <label for="nameUsers" class="form-label fw-bold">Nama</label>
                            <input type="text" class="form-control" id="nameUsers" value=""
                                placeholder="Masukkan nama user">
                            <div id="nameUsersError" class="text-danger mt-1 d-none">Silahkan isi Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="emailUsers" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="emailUsers" value=""
                                placeholder="Masukkan email user">
                            <div id="emailUsersError" class="text-danger mt-1 d-none">Silahkan isi Email</div>
                        </div>
                        <div class="password-field mt-3">
                            <label for="passwordUsers" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="passwordUsers"
                                    placeholder="Masukkan password">
                                <span id="togglePassword" class="input-group-text" style="cursor: pointer;">
                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                            <div id="passwordUsersError" class="text-danger mt-1 d-none">Password must be at least 6
                                characters.</div>
                        </div>
                        <div class="mt-3">
                            <label for="passwordConfirmationUsers" class="form-label fw-bold">Konfirmasi
                                Password</label>
                            <input type="password" class="form-control" id="passwordConfirmationUsers"
                                placeholder="Konfirmasi password">
                            <div id="passwordConfirmationError" class="text-danger mt-1 d-none">Konfirmasi password
                                tidak cocok</div>
                        </div>
                        <div class="mt-3">
                            <label for="roleUsers" class="form-label fw-bold">Role</label>
                            <select class="form-control" id="roleUsers" style="width: 466px;">
                                <option value="" selected disabled>Pilih Status</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">SuperAdmin</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="pickup">Pickup</option>
                            </select>
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
    <div class="modal fade" id="modalEditUsers" tabindex="-1" role="dialog" aria-labelledby="modalEditUsersTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditUsersTitle">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="nameUsersEdit" class="form-label fw-bold">Nama</label>
                        <input type="text" class="form-control" id="nameUsersEdit" value=""
                            placeholder="Masukkan nama user">
                        <div id="nameUsersErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Nama</div>
                    </div>
                    <div class="mt-3">
                        <label for="emailUsersEdit" class="form-label fw-bold">Email</label>
                        <input type="text" class="form-control" id="emailUsersEdit" value=""
                            placeholder="Masukkan email user">
                        <div id="emailUsersErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Email</div>
                    </div>
                    <div class="mt-3">
                        <label for="passwordUsersEdit" class="form-label fw-bold">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="passwordUsersEdit"
                                placeholder="Masukkan new password">
                            <span id="togglePassword1" class="input-group-text" style="cursor: pointer;">
                                <i class="fa fa-eye" id="eyeIcon1"></i>
                            </span>
                        </div>
                        <div id="passwordUsersErrorEdit" class="text-danger mt-1 d-none">Password must be at least 6
                            characters.
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="passwordConfirmationUsersEdit" class="form-label fw-bold">Konfirmasi
                            Password</label>
                        <input type="password" class="form-control" id="passwordConfirmationUsersEdit"
                            placeholder="Konfirmasi password">
                        <div id="passwordConfirmationErrorEdit" class="text-danger mt-1 d-none">Konfirmasi password
                            tidak cocok</div>
                    </div>
                    <div class="mt-3">
                        <label for="roleUsersEdit" class="form-label fw-bold">Role</label>
                        <select class="form-control" id="roleUsersEdit" style="width: 466px;">
                            <option value="" selected disabled>Pilih Status</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">SuperAdmin</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="pickup">Pickup</option>
                        </select>
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
                    <div class="float-left ml-2">
                        <select class="form-control" id="filterRole" style="width: 200px;">
                            <option value="" selected disabled>Pilih Role</option>
                            <?php $__currentLoopData = $listRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->role); ?>"><?php echo e(ucwords($role->role)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="float-left ml-1">
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div class="d-flex mb-3 mr-3 float-right">
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahUsers" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah User</button>
                    </div>
                    <div id="containerUser" class="table-responsive px-3 ">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getListUser = () => {
            const txtSearch = $('#txSearch').val();
            const filterRole = $('#filterRole').val();

            $.ajax({
                url: "<?php echo e(route('getlistUser')); ?>",
                method: "GET",
                data: {
                    txSearch: txtSearch,
                    role: filterRole
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
        $('#txSearch').keyup(function (e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getListUser();
            }
        });
        $('#filterRole').change(function () {
            getListUser();
        });

        $('#saveUsers').click(function () {
            var nameUsers = $('#nameUsers').val().trim();
            var emailUsers = $('#emailUsers').val().trim();
            var passwordUsers = $('#passwordUsers').val();
            var passwordConfirmation = $('#passwordConfirmationUsers').val();
            var roleUsers = $('#roleUsers').val();

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (nameUsers === '') {
                $('#nameUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#nameUsersError').addClass('d-none');
            }
            if (emailUsers === '') {
                $('#emailUsersError').text('Silahkan isi Email').removeClass('d-none');
                isValid = false;
            } else if (!emailRegex.test(emailUsers)) {
                $('#emailUsersError').text('Format Email tidak valid').removeClass('d-none');
                isValid = false;
            } else if (!emailUsers.endsWith('@gmail.com')) {
                $('#emailUsersError').text('Email harus menggunakan @gmail.com').removeClass('d-none');
                isValid = false;
            } else {
                $('#emailUsersError').addClass('d-none');
            }
            if (passwordUsers.length < 6) {
                $('#passwordUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#passwordUsersError').addClass('d-none');
            }
            if (passwordUsers !== passwordConfirmation) {
                $('#passwordConfirmationError').removeClass('d-none');
                isValid = false;
            } else {
                $('#passwordConfirmationError').addClass('d-none');
            }

            if (!roleUsers || roleUsers === '' || roleUsers === '0') {
                $('#roleUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#roleUsersError').addClass('d-none');
            }

            if (!roleUsers || roleUsers === '' || roleUsers === '0') {
                $('#roleUsersError').removeClass('d-none');
                isValid = false;
            } else {
                $('#roleUsersError').addClass('d-none');
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
                            text: 'Please wait while we process your user.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '/masterdata/user/store',
                            method: 'POST',
                            data: {
                                nameUsers: nameUsers,
                                emailUsers: emailUsers,
                                passwordUsers: passwordUsers,
                                passwordUsers_confirmation: passwordConfirmation,
                                roleUsers: roleUsers,
                                _token: '<?php echo e(csrf_token()); ?>',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success",
                                        "Berhasil ditambahkan");
                                    $('#modalTambahUsers').modal('hide');
                                    getListUser();
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
        $('#modalTambahUsers').on('hidden.bs.modal', function () {
            $('#nameUsers,#emailUsers,#passwordUsers,#passwordConfirmationUsers,#roleUsers').val('');
            if (!$('#nameUsersError').hasClass('d-none')) {
                $('#nameUsersError').addClass('d-none');
            }
            if (!$('#emailUsersError').hasClass('d-none')) {
                $('#emailUsersError').addClass('d-none');
            }
            if (!$('#passwordUsersError').hasClass('d-none')) {
                $('#passwordUsersError').addClass('d-none');
            }
            if (!$('#passwordConfirmationError').hasClass('d-none')) {
                $('#passwordConfirmationError').addClass('d-none');
            }
            if (!$('#roleUsersError').hasClass('d-none')) {
                $('#roleUsersError').addClass('d-none');
            }
        });
        $(document).on('click', '.btnUpdateUsers', function (e) {
            var userid = $(this).data('id');
            $.ajax({
                url: '/masterdata/user/' + userid,
                method: 'GET',
                success: function (response) {
                    $('#nameUsersEdit').val(response.name);
                    $('#emailUsersEdit').val(response.email);
                    $('#passwordUsersEdit').val(response.password);
                    $('#roleUsersEdit').val(response.role);
                    $('#modalEditUsers').modal('show');
                    $('#saveEditUsers').data('id', userid);
                    if (response.role === 'driver' || response.role === 'customer') {
                        $('#roleUsersEdit').hide();
                        $('#roleUsersEdit').val('');
                        $('label[for="roleUsersEdit"]').hide();
                    } else {
                        $('#roleUsersEdit').show();
                        $('label[for="roleUsersEdit"]').show();
                    }
                },
                error: function () {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data");
                }
            });
    });
    $('#saveEditUsers').on('click', function () {
        var userid = $(this).data('id');
        var nameUsers = $('#nameUsersEdit').val();
        var emailUsers = $('#emailUsersEdit').val();
        var passwordUsers = $('#passwordUsersEdit').val();
        var passwordConfirmation = $('#passwordConfirmationUsersEdit').val();
        var roleUsers = $('#roleUsersEdit').val();

        var isValid = true;
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (nameUsers === '') {
            $('#nameUsersErrorEdit').removeClass('d-none');
            isValid = false;
        } else {
            $('#nameUsersErrorEdit').addClass('d-none');
        }
        if (emailUsers === '') {
            $('#emailUsersErrorEdit').text('Silahkan isi Email').removeClass('d-none');
            isValid = false;
        } else if (!emailRegex.test(emailUsers)) {
            $('#emailUsersErrorEdit').text('Format Email tidak valid').removeClass('d-none');
            isValid = false;
        } else if (!emailUsers.endsWith('@gmail.com')) {
            $('#emailUsersErrorEdit').text('Email harus menggunakan @gmail.com').removeClass('d-none');
            isValid = false;
        } else {
            $('#emailUsersErrorEdit').addClass('d-none');
        }
        if (passwordUsers.length > 0) {

            if (passwordUsers.length < 6) {
                $('#passwordUsersErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#passwordUsersErrorEdit').addClass('d-none');
            }
            if (passwordUsers !== passwordConfirmation) {
                $('#passwordConfirmationErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#passwordConfirmationErrorEdit').addClass('d-none');
            }
        }

        if (isValid) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
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
                        text: 'Please wait while we are updating the data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        url: '/masterdata/user/update/' + userid,
                        method: 'PUT',
                        data: {
                            nameUsers: nameUsers,
                            emailUsers: emailUsers,
                            passwordUsers: passwordUsers,
                            passwordUsers_confirmation: passwordConfirmation,
                            roleUsers: roleUsers,
                            _token: '<?php echo e(csrf_token()); ?>',
                        },
                        success: function (response) {
                            Swal.close();
                            if (response.success) {
                                showMessage("success", "Berhasil diperbarui");
                                $('#modalEditUsers').modal('hide');
                                getListUser();
                            }
                        },
                        error: function (response) {
                            Swal.close();
                            showMessage("error", "Terjadi kesalahan, coba lagi nanti");
                        }
                    });
                }
            });
        }
    });
    $('#modalEditUsers').on('hidden.bs.modal', function () {
        $('#nameUsersEdit,#emailUsersEdit,#passwordUsersEdit,#passwordConfirmationUsersEdit,#roleUsersEdit').val('');
        if (!$('#nameUsersErrorEdit').hasClass('d-none')) {
            $('#nameUsersErrorEdit').addClass('d-none');
        }
        if (!$('#emailUsersErrorEdit').hasClass('d-none')) {
            $('#emailUsersErrorEdit').addClass('d-none');
        }
        if (!$('#passwordUsersErrorEdit').hasClass('d-none')) {
            $('#passwordUsersErrorEdit').addClass('d-none');
        }
        if (!$('#passwordConfirmationErrorEdit').hasClass('d-none')) {
            $('#passwordConfirmationErrorEdit').addClass('d-none');
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
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process delete your user.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/masterdata/user/destroy/' + id,
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
    $(document).ready(function () {
        $('#togglePassword').on('click', function () {
            const passwordField = $('#passwordUsers');
            const eyeIcon = $('#eyeIcon');

            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);

            // Toggle the eye icon
            eyeIcon.toggleClass('fa-eye fa-eye-slash');
        });

        $('#togglePassword1').on('click', function () {
            const passwordField = $('#passwordUsersEdit');
            const eyeIcon1 = $('#eyeIcon1');

            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);

            // Toggle the eye icon
            eyeIcon1.toggleClass('fa-eye fa-eye-slash');
        });
    });

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views/masterdata/user/indexmasteruser.blade.php ENDPATH**/ ?>