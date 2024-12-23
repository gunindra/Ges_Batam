<?php $__env->startSection('title', 'Content | Service'); ?>

<?php $__env->startSection('main'); ?>

<div class="container-fluid" id="container-wrapper">
    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahService" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahServiceTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahService">Tambah Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleService" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="titleService" value=""
                            placeholder="Masukkan judul service">
                        <div id="titleServiceError" class="text-danger mt-1 d-none">Silahkan isi Judul
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="contentService" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="contentService" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="contentServiceError" class="text-danger mt-1 d-none">Silahkan isi Content</div>
                    </div>
                    <div class="mt-3">
                        <label for="imageService" class="form-label fw-bold">Gambar</label>
                        <input type="file" class="form-control" id="imageService" value="">
                        <div id="imageServiceError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveService" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditService" tabindex="-1" role="dialog" aria-labelledby="modalEditServiceTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditServiceTitle">Edit Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleService" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="titleServiceEdit" value=""
                            placeholder="Masukkan judul service">
                        <div id="titleServiceErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                    </div>
                    <div class="mt-3">
                        <label for="contentService" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="contentServiceEdit" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="contentServiceErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Content
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageService" class="form-label fw-bold">Gambar</label>
                        <p class="">Nama Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageServiceEdit" value="">
                        <div id="imageServiceErrorEdit" class="text-danger mt-1 d-none">Please fill in the Gambar
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditService" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Service</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Service</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahService" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Service</button>
                    </div>
                    <div id="containerService" class="table-responsive px-3">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableAboutUs">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Distinctio natus aspernatur eligendi, aperiam voluptatibus quia! Facere eveniet consequuntur nostrum molestias, asperiores cupiditate quibusdam dolore molestiae quod modi? Assumenda, tenetur repudiandae?</td>
                                        <td><img src="/img/Aboutus.jpg" width="50px"></td>
                                        <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="btn btn-sm btn-primary btnGambar"><i class="fas fa-eye"></i></a>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
            <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
        </div> `;

        const getlistService = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "<?php echo e(route('getlistService')); ?>",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerService').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerService').html(res)
                    $('#tableService').DataTable({
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

        getlistService();

        $('#saveService').click(function () {
            var titleService = $('#titleService').val().trim();
            var contentService = $('#contentService').val().trim();
            var imageService = $('#imageService')[0].files[0];

            var isValid = true;

            if (titleService === '') {
                $('#titleServiceError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleServiceError').addClass('d-none');
            }
            if (contentService === '') {
                $('#contentServiceError').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentServiceError').addClass('d-none');
            }
            if (imageService) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                if (!validExtensions.includes(imageService.type)) {
                    $('#imageServiceError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan.')
                        .removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageServiceError').addClass('d-none');
                }
            } else {
                $('#imageServiceError').removeClass('d-none');
                isValid = false;
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
                            text: 'Please wait while we are saving the data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        var formData = new FormData();
                        formData.append('titleService', titleService);
                        formData.append('contentService', contentService);
                        formData.append('imageService', imageService);
                        formData.append('_token', '<?php echo e(csrf_token()); ?>');

                        $.ajax({
                            url: '/content/services/store',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success",
                                        "Berhasil ditambahkan");
                                    $('#modalTambahService').modal('hide');
                                    getlistService();
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                if (response.status === 422) {
                                    showMessage("error", "Judul yang dimasukkan sudah ada. Silakan masukkan judul yang berbeda.");
                                } else {
                                showMessage("error","Terjadi kesalahan, coba lagi nanti");
                                }
                            }
                        });
                    }
                });
            }
        });
        $(document).on('click', '.btnUpdateService', function (e) {
            var serviceid = $(this).data('id');
            $.ajax({
                url: '/content/services/' + serviceid,
                method: 'GET',
                success: function (response) {
                    $('#titleServiceEdit').val(response.title_service);
                    $('#contentServiceEdit').val(response.content_service);
                    $('#textNamaEdit').text(response.image_service);
                    $('#modalEditService').modal('show');
                    $('#saveEditService').data('id', serviceid);
                },
                error: function () {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data");
                }
            });
        });
        $('#saveEditService').on('click', function () {
            var serviceid = $(this).data('id');
            var titleService = $('#titleServiceEdit').val();
            var contentService = $('#contentServiceEdit').val();
            var imageService = $('#imageServiceEdit')[0].files[0];

            let isValid = true;

            if (titleService === '') {
                $('#titleServiceErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleServiceErrorEdit').addClass('d-none');
            }

            if (contentService === '') {
                $('#contentServiceErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentServiceErrorEdit').addClass('d-none');
            }


            if (imageService) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageService.type)) {
                    $('#imageServiceErrorEdit').text(
                        'Hanya file JPG, JPEG, atau PNG yang diizinkan.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageServiceErrorEdit').addClass('d-none');
                }
            } else if ($('#textNamaEdit').text() === '') {
                $('#imageServiceErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageServiceErrorEdit').addClass('d-none');
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
                        var formData = new FormData();
                        formData.append('titleService', titleService);
                        formData.append('contentService', contentService);
                        if (imageService) {
                            formData.append('imageService', imageService);
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            }
                        });

                        $.ajax({
                            url: '/content/services/update/' + serviceid,
                            method: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", response
                                        .message);
                                    $('#modalEditService').modal('hide');
                                    getlistService();
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

        $('#modalTambahService').on('hidden.bs.modal', function () {
            $('#titleService,#contentService,#imageService').val('');
            if (!$('#titleServiceError').hasClass('d-none')) {
                $('#titleServiceError').addClass('d-none');
            }
            if (!$('#contentServiceError').hasClass('d-none')) {
                $('#contentServiceError').addClass('d-none');
            }
            if (!$('#imageServiceError').hasClass('d-none')) {
                $('#imageServiceError').addClass('d-none');
            }
        });
        $('#modalEditService').on('hidden.bs.modal', function () {
            $('#titleServiceEdit,#contentServiceEdit,#imageServiceEdit').val('');
            if (!$('#titleServiceErrorEdit').hasClass('d-none')) {
                $('#titleServiceErrorEdit').addClass('d-none');
            }
            if (!$('#contentServiceErrorEdit').hasClass('d-none')) {
                $('#contentServiceErrorEdit').addClass('d-none');
            }
            if (!$('#imageServiceErrorEdit').hasClass('d-none')) {
                $('#imageServiceErrorEdit').addClass('d-none');
            }
        });



        $(document).on('click', '.btnDestroyService', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus ini?",
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
                        text: 'Please wait while we process delete your data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: '/content/services/destroy/' + id,
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
                                    title: 'Kesalahan',
                                    text: response.error
                                });
                            }
                            if (response.status === 'success') {
                                showMessage("success",
                                    "Berhasil dihapus");
                                getlistService();
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views\content\services\indexservice.blade.php ENDPATH**/ ?>