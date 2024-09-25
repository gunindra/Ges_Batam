<?php $__env->startSection('title', 'Content | Advertisement'); ?>

<?php $__env->startSection('main'); ?>

<div class="container-fluid" id="container-wrapper">


    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahAdvertisement" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahAdvertisementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAdvertisement">Add Advertisement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleAdvertisement" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="titleAdvertisement" value=""
                            placeholder="Masukkan judul iklan">
                        <div id="titleAdvertisementError" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="imageAdvertisement" class="form-label fw-bold">Image</label>
                        <input type="file" class="form-control" id="imageAdvertisement" value="">
                        <div id="imageAdvertisementError" class="text-danger mt-1 d-none">Please fill in the Image</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveAdvertisement" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditAdvertisement" tabindex="-1" role="dialog"
        aria-labelledby="modalEditAdvertisementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditAdvertisementTitle">Edit Advertisement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="advertisementIdEdit">
                    <div class="mt-3">
                        <label for="titleAdvertisement" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="titleAdvertisementEdit" value=""
                            placeholder="Masukkan judul iklan">
                        <div id="titleAdvertisementErrorEdit" class="text-danger mt-1 d-none">Please fill in the Title
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageAdvertisement" class="form-label fw-bold">Image</label>
                        <p class="">Name Image : <span id="textNamaEdit"></span></p>  
                        <input type="file" class="form-control" id="imageAdvertisementEdit" value="">
                        <div id="imageAdvertisementErrorEdit" class="text-danger mt-1 d-none">Please fill in the Image
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditAdvertisement" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Advertisement</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Advertisement</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahAdvertisement" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Add Advertisement</button>
                    </div>
                    <div id="containerAdvertisement" class="table-responsive px-2">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableIklan">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Isi Iklan</th>
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

        const getlistAdvertisement = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "<?php echo e(route('getlistAdvertisement')); ?>",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerAdvertisement').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerAdvertisement').html(res)
                    $('#tableAdvertisement').DataTable({
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

        getlistAdvertisement();

        $('#saveAdvertisement').click(function () {
            // Ambil nilai input
            var titleAdvertisement = $('#titleAdvertisement').val().trim();
            var imageAdvertisement = $('#imageAdvertisement')[0].files[0];

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (titleAdvertisement === '') {
                $('#titleAdvertisementError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleAdvertisementError').addClass('d-none');
            }
            if (imageAdvertisement) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                if (!validExtensions.includes(imageAdvertisement.type)) {
                    $('#imageAdvertisementError').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageAdvertisementError').addClass('d-none');
                }
            } else if (!imageAdvertisement) {
                $('#imageAdvertisementError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageAdvertisementError').addClass('d-none');
            }

            // Jika semua input valid, lanjutkan aksi simpan
            if (isValid) {
                Swal.fire({
                    title: "Are you sure?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append('titleAdvertisement', titleAdvertisement);
                        formData.append('imageAdvertisement', imageAdvertisement);
                        formData.append('_token', csrfToken);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your data Advertisement.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "<?php echo e(route('addAdvertisement')); ?>",
                            data: formData,
                            contentType: false,
                            processData: false,
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
                                    showMessage("success", "Data successfully saved");
                                    getlistAdvertisement();
                                    $('#modalTambahAdvertisement').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Failed to add data",
                                        text: response
                                            .message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: "Failed to add data",
                                    text: xhr.responseJSON
                                        .message,
                                    icon: "error",
                                });
                            }
                        });
                    }
                });
            } else {
                showMessage("error", "Please check for empty inputs");
            }
        });



        $(document).on('click', '.btnUpdateAdvertisement', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let title_Advertisement = $(this).data('title_advertisement');
            let image_Advertisement  = $(this).data('image_advertisement');

            $('#titleAdvertisementEdit').val(title_Advertisement);
            $('#textNamaEdit').text(image_Advertisement);
            $('#advertisementIdEdit').val(id);

            $(document).on('click', '#saveEditAdvertisement', function (e) {

                let id = $('#advertisementIdEdit').val();
                let titleAdvertisement = $('#titleAdvertisementEdit').val();
                let imageAdvertisement = $('#imageAdvertisementEdit')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (titleAdvertisement === '') {
                    $('#titleAdvertisementErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#titleAdvertisementErrorEdit').addClass('d-none');
                }

                if (imageAdvertisement) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(imageAdvertisement.type)) {
                        $('#imageAdvertisementErrorEdit').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#imageAdvertisementErrorEdit').addClass('d-none');
                    }
                } else if (imageAdvertisement === 0 && $('#textNamaEdit').text() === '') {
                    $('#imageAdvertisementErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageAdvertisementErrorEdit').addClass('d-none');
                }

                if (isValid) {
                    Swal.fire({
                        title: "Are you sure?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let formData = new FormData();
                            formData.append('id', id);
                            formData.append('titleAdvertisement', titleAdvertisement);
                            if(imageHeroPage){
                            formData.append('imageAdvertisement', imageAdvertisement);
                            }
                            formData.append('_token', csrfToken);
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Please wait while we process update your data Heropage.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "<?php echo e(route('updateAdvertisement')); ?>",
                                data: formData,
                                contentType: false,
                                processData: false,
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
                                            "Data successfully updated");
                                            getlistAdvertisement();
                                        $('#modalEditAdvertisement').modal(
                                            'hide');
                                    } else {
                                        Swal.fire({
                                            title: "Failed to update",
                                            icon: "error"
                                        });
                                    }
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Please check for empty inputs");
                }
            })
            $('#modalEditAdvertisement').modal('show');
        });
        $('#modalTambahAdvertisement').on('hidden.bs.modal', function () {
            $('#titleAdvertisement,#imageAdvertisement').val('');
            if (!$('#titleAdvertisementError').hasClass('d-none')) {
                $('#titleAdvertisementError').addClass('d-none');

            }
            if (!$('#imageAdvertisementError').hasClass('d-none')) {
                $('#imageAdvertisementError').addClass('d-none');

            }
        });
        $('#modalEditAdvertisement').on('hidden.bs.modal', function () {
            $('#titleAdvertisementEdit,#imageAdvertisementEdit').val('');
            if (!$('#titleAdvertisementErrorEdit').hasClass('d-none')) {
                $('#titleAdvertisementErrorEdit').addClass('d-none');

            }
            if (!$('#imageAdvertisementErrorEdit').hasClass('d-none')) {
                $('#imageAdvertisementErrorEdit').addClass('d-none');

            }
        });




        $(document).on('click', '.btnDestroyAdvertisement', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Are you sure you want to delete this?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your data Advertisement.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "<?php echo e(route('destroyAdvertisement')); ?>",
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
                                showMessage("success", "Successfully deleted");
                                getlistAdvertisement();
                            } else {
                                showMessage("error", "Failed to delete");
                            }
                        }
                    });
                }
            });

        });

    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\content\advertisement\indexadvertisement.blade.php ENDPATH**/ ?>