<?php $__env->startSection('title', ' Content | Popup'); ?>

<?php $__env->startSection('main'); ?>

<!-- Container Fluid -->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Popup</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Popup</li>
        </ol>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div id="containerPopup" class="table-responsive px-3"></div>
                    <div class="mt-3">
                        <label for="imagePopup" class="form-label fw-bold p-1">Gambar</label>
                        <input type="file" class="form-control" id="imagePopup">
                        <div id="imagePopupError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                        <p>Nama Image= <span id="imageName"><?php echo e($popupData->Image_Popup ?? ' -'); ?></span></p>
                    </div>
                    <div class="mt-3">
                        <label for="titlePopup" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="titlePopup"
                            value="<?php echo e(isset($popupData->title_Popup) ? $popupData->title_Popup : ''); ?>"
                            placeholder="Masukkan judul Popup">
                        <div id="titlePopupError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                    </div>
                    <div class="input-group pt-2 mt-3">
                        <label for="paragraphPopup" class="form-label fw-bold p-3">Content</label>
                        <textarea id="paragraphPopup" class="form-control" aria-label="With textarea"
                            placeholder="Masukkan content"><?php echo e(isset($popupData->Paragraph_Popup) ? $popupData->Paragraph_Popup : ''); ?></textarea>
                    </div>
                    <div id="parafPopupError" class="text-danger mt-1 d-none">Silahkan isi Content</div>
                    <div class="mt-3">
                        <label for="linkPopup" class="form-label fw-bold">Link</label>
                        <input type="text" class="form-control" id="linkPopup"
                            value="<?php echo e(isset($popupData->Link_Popup) ? $popupData->Link_Popup : ''); ?>"
                            placeholder="Masukkan link">
                        <div id="linkPopupError" class="text-danger mt-1 d-none">Silahkan isi Link</div>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" id="savePopup">
                        <span class="pr-3"><i class="fas fa-save"></i></span> Save
                    </button>
                    <button type="button" class="btn btn-danger mt-3" id="destroyPopup"
                        data-id="<?php echo e(isset($popupData->id) ? $popupData->id : ''); ?>">
                        <span class="pr-3"><i class="fas fa-trash"></i></span> Delete
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary p-2">Preview</h6>
                    <div class="preview" id="previewContainer"
                        style="border:1px solid black; height: auto; border-radius:10px;">
                        <?php if($popupData): ?>
                            <?php if($popupData->Image_Popup): ?>
                                <img src="<?php echo e(asset('storage/images/' . $popupData->Image_Popup)); ?>" width="600px"
                                    style="padding:5px 30px;">
                                <p style="padding-left:30px;"><?php echo e($popupData->title_Popup); ?></p>
                            <?php endif; ?>
                            <p style="padding-left:30px;"><?php echo nl2br( e( $popupData->Paragraph_Popup )); ?></p>
                            <p class="text-primary" style="padding-left:30px;"><?php echo e($popupData->Link_Popup); ?></p>
                        <?php else: ?>
                            <p class="p-3">No content available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Container Fluid -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function () {
        $(document).on('click', '#savePopup', function (e) {
            e.preventDefault();

            var titlePopup = $('#titlePopup').val().trim();
            var paragraphPopup = $('#paragraphPopup').val().trim();
            var linkPopup = $('#linkPopup').val().trim();
            var imagePopup = $('#imagePopup')[0].files[0];
            var existingImage = $('#imageName').text();

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (titlePopup === '') {
                $('#titlePopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titlePopupError').addClass('d-none');
            }
            if (paragraphPopup === '') {
                $('#paragraphPopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#paragraphPopupError').addClass('d-none');
            }
            if (linkPopup === '') {
                $('#linkPopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#linkPopupError').addClass('d-none');
            }
            if (imagePopup) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imagePopup.type)) {
                    $('#imagePopupError').text('Hanya file JPG, JPEG, atau PNG yang diperbolehkan, dan gambar tidak boleh kosong.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imagePopupError').addClass('d-none');
                }
            } else if (!$('#previewContainer img').length) {
                $('#imagePopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imagePopupError').addClass('d-none');
            }

            if (isValid) {
                var formData = new FormData();
                formData.append('titlePopup', titlePopup);
                formData.append('paragraphPopup', paragraphPopup);
                formData.append('linkPopup', linkPopup);
                formData.append('imagePopup', imagePopup ? imagePopup : existingImage);
                formData.append('_token', csrfToken);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process save your data.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: '<?php echo e(route("addPopup")); ?>',
                    method: 'POST',
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
                            $('#imageName').text(response.data.imagePopup);
                            $('#previewContainer').html(`
                            <img src="<?php echo e(asset('storage/images/')); ?>/${response.data.imagePopup}" width="600px" style="padding:5px 30px;">
                            <p style="padding-left:30px;">${response.data.titlePopup}</p>
                            <p style="padding-left:30px;">${response.data.paragraphPopup}</p>
                            <p class="text-primary" style="padding-left:30px;">${response.data.linkPopup}</p>
                        `);

                            $('#destroyPopup').data('id', response.data.id);
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Disimpan',
                                text: response.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal menyimpan data'
                        });
                    }
                });
            }
        });

        $(document).on('click', '#destroyPopup', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            if (!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'tidak ada data.'
                });
                return;
            }

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
                        url: '/content/popup/destroy/'+ id,
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content')
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
                                $('#titlePopup').val('');
                                $('#paragraphPopup').val('');
                                $('#linkPopup').val('');
                                $('#imagePopup').val('');
                                $('#previewContainer').html('<p class="p-3">Tidak ada konten tersedia</p>');
                                $('#imageName').text('Belum ada gambar');
                                $('#destroyPopup').data('id', '');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Berhasil dihapus'
                                });
                            } else if (response.status === 'info') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Info',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus'
                            });
                        }
                    });
                }
            });
        });
    });



</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\content\popup\indexpopup.blade.php ENDPATH**/ ?>