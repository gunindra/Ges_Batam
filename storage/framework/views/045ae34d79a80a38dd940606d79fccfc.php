<?php $__env->startSection('title', ' Content | Information'); ?>

<?php $__env->startSection('main'); ?>

<div class="container-fluid" id="container-wrapper">


    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahInformations" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahInformationsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahInformations">Tambah Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="informationForm" enctype="multipart/form-data">
                        <div class="mt-3">
                            <label for="titleInformations" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="titleInformations" value=""
                                placeholder="Masukkan judul information">
                            <div id="titleInformationsError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="contentInformations" class="form-label fw-bold">Content</label>
                            <textarea class="form-control" id="contentInformations" rows="3"
                                placeholder="Masukkan content"></textarea>
                            <div id="contentInformationsError" class="text-danger mt-1 d-none">Silahkan isi Content
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="imageInformations" class="form-label fw-bold">Gambar</label>
                            <input type="file" class="form-control" id="imageInformations" value="">
                            <div id="imageInformationsError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                            <button type="button" id="saveInformations" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditInformations" tabindex="-1" role="dialog"
        aria-labelledby="modalEditInformationsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditInformationsTitle">Edit Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleInformations" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="titleInformationsEdit" value=""
                            placeholder="Masukkan judul information">
                        <div id="titleInformationsErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                    </div>
                    <div class="mt-3">
                        <label for="contentInformations" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="contentInformationsEdit" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="contentInformationsErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Content
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageInformations" class="form-label fw-bold">Gambar</label>
                        <p class="">Nama Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageInformationsEdit" value="">
                        <div id="imageInformationsErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Gambar
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditInformations" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Information</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahInformations" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Information</button>
                    </div>
                    
                    <div id="containerInformations" class="table-responsive px-2">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableinformations">
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

        const getlistInformations = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "<?php echo e(route('getlistInformations')); ?>",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerInformations').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerInformations').html(res)
                    $('#tableInformations').DataTable({
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

        getlistInformations();
        $('#titleInformations, #contentInformations', 'imageInformations').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        $('#saveInformations').click(function () {
            var titleInformations = $('#titleInformations').val().trim();
            var contentInformations = $('#contentInformations').val().trim();
            var imageInformations = $('#imageInformations')[0].files[0];

            var isValid = true;
            var errorMessage = '';

            if (titleInformations === '') {
                $('#titleInformationsError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleInformationsError').addClass('d-none');
            }

            if (titleInformations.length > 120) {
                errorMessage += 'Judul tidak boleh lebih dari 120 karakter.<br>';
                isValid = false;
            }

            if (contentInformations === '') {
                $('#contentInformationsError').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentInformationsError').addClass('d-none');
            }

            if (contentInformations.length > 260) {
                errorMessage += 'Konten tidak boleh lebih dari 260 karakter.<br>';
                isValid = false;
            }

            if (imageInformations) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageInformations.type)) {
                    $('#imageInformationsError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan.')
                        .removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageInformationsError').addClass('d-none');
                }
            } else {
                $('#imageInformationsError').removeClass('d-none');
                isValid = false;
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage || 'Silakan periksa input Anda.',
                    confirmButtonText: 'OK',
                });
                return;
            }

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
                    formData.append('titleInformations', titleInformations);
                    formData.append('contentInformations', contentInformations);
                    formData.append('imageInformations', imageInformations);
                    formData.append('_token', '<?php echo e(csrf_token()); ?>');

                    $.ajax({
                        url: '/content/informations/store',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.close();
                            if (response.success) {
                                showMessage("success", "Information berhasil ditambahkan");
                                $('#modalTambahInformations').modal('hide');
                                getlistInformations();
                            }
                        },
                        error: function (response) {
                            Swal.close();
                            if (response.status === 422) {
                                showMessage("error", "Judul yang dimasukkan sudah ada. Silakan masukkan judul yang berbeda.");
                            } else {
                                showMessage("error", "Terjadi kesalahan, coba lagi nanti");
                            }
                        }
                    });
                }
            });
        });
        
        $(document).on('click', '.btnUpdateInformations', function (e) {
            var informationid = $(this).data('id');
            $.ajax({
                url: '/content/informations/' + informationid,
                method: 'GET',
                success: function (response) {
                    $('#titleInformationsEdit').val(response.title_informations);
                    $('#contentInformationsEdit').val(response.content_informations);
                    $('#textNamaEdit').text(response.image_informations);
                    $('#modalEditInformations').modal('show');
                    $('#saveEditInformations').data('id', informationid);
                },
                error: function () {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data informations");
                }
            });
        });

        $('#saveEditInformations').on('click', function () {
            var informationid = $(this).data('id');
            var titleInformations = $('#titleInformationsEdit').val().trim();
            var contentInformations = $('#contentInformationsEdit').val().trim();
            var imageInformations = $('#imageInformationsEdit')[0].files[0];

            let isValid = true;
            var errorMessage = '';

            if (titleInformations === '') {
                $('#titleInformationsErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleInformationsErrorEdit').addClass('d-none');
            }

            if (titleInformations.length > 120) {
                errorMessage += 'Judul tidak boleh lebih dari 120 karakter.<br>';
                isValid = false;
            }
            if (contentInformations === '') {
                $('#contentInformationsErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentInformationsErrorEdit').addClass('d-none');
            }

            if (contentInformations.length > 260) {
                errorMessage += 'Konten tidak boleh lebih dari 260 karakter.<br>';
                isValid = false;
            }

            if (imageInformations) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageInformations.type)) {
                    $('#imageInformationsErrorEdit').text('Hanya file JPG, JPEG, atau PNG yang diizinkan.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageInformationsErrorEdit').addClass('d-none');
                }
            } else if ($('#textNamaEdit').text() === '') {
                $('#imageInformationsErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageInformationsErrorEdit').addClass('d-none');
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
                        formData.append('titleInformations', titleInformations);
                        formData.append('contentInformations', contentInformations);
                        if (imageInformations) {
                            formData.append('imageInformations', imageInformations);
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            }
                        });

                        $.ajax({
                            url: '/content/informations/update/' + informationid,
                            method: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", response
                                        .message);
                                    $('#modalEditInformations').modal('hide');
                                    getlistInformations();
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
        $('#modalTambahInformations').on('hidden.bs.modal', function () {
            $('#titleInformations,#contentInformations,#imageInformations').val('');
            if (!$('#titleInformationsError').hasClass('d-none')) {
                $('#titleInformationsError').addClass('d-none');
            }
            if (!$('#contentInformationsError').hasClass('d-none')) {
                $('#contentInformationsError').addClass('d-none');
            }
            if (!$('#imageInformationsError').hasClass('d-none')) {
                $('#imageInformationsError').addClass('d-none');
            }
        });
        $('#modalEditInformations').on('hidden.bs.modal', function () {
            $('#titleInformationsEdit,#contentInformationsEdit,#imageInformationsEdit').val('');
            if (!$('#titleInformationsErrorEdit').hasClass('d-none')) {
                $('#titleInformationsErrorEdit').addClass('d-none');
            }
            if (!$('#contentInformationsErrorEdit').hasClass('d-none')) {
                $('#contentInformationsErrorEdit').addClass('d-none');
            }
            if (!$('#imageInformationsErrorEdit').hasClass('d-none')) {
                $('#imageInformationsErrorEdit').addClass('d-none');
            }
        });




        $(document).on('click', '.btnDestroyInformations', function (e) {
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
                        text: 'Please wait while we process Delete your data.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: '/content/informations/destroy/' + id,
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
                                showMessage("success", "Berhasil dihapus");
                                getlistInformations();
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



<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views\content\informations\indexinformation.blade.php ENDPATH**/ ?>