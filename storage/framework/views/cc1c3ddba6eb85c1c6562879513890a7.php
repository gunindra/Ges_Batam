<?php $__env->startSection('title', 'Content | Why'); ?>

<?php $__env->startSection('main'); ?>

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Why</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Why</li>
        </ol>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div id="containerWhy" class="table-responsive px-3">
                    </div>
                    <div class="mt-3">
                        <label for="imageWhy" class="form-label fw-bold p-1">Gambar</label>
                        <input type="file" class="form-control" id="imageWhy">
                        <div id="imageWhyError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                        <p>Nama Image=<span id="imageName"><?php echo e($whyData->Image_WhyUs ?? '-'); ?></span> </p>
                    </div>
                    <div class="input-group pt-2 mt-3">
                        <label for="contentWhy" class="form-label fw-bold p-3">Content</label>
                        <textarea id="contentWhy" class="form-control" aria-label="With textarea"
                            placeholder="Masukkan content"><?php echo e($whyData->Paragraph_WhyUs ?? ''); ?></textarea>
                    </div>
                    <div id="contentWhyError" class="text-danger mt-1 d-none">Silahkan isi Content</div>
                    <button type="button" class="btn btn-primary mt-3" id="saveWhy">
                        <span class="pr-3"><i class="fas fa-save"></i></span> Save
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
                        <?php if($whyData): ?>
                            <?php if($whyData->Image_WhyUs): ?>
                                <img src="<?php echo e(asset('storage/images/' . $whyData->Image_WhyUs)); ?>" width="600px"
                                    style="padding:5px 30px;">
                                <p style="margin-left:30px;"><?php echo nl2br( e( $whyData->Paragraph_WhyUs ?? '' )); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="p-3">No content available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---Container Fluid-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
 $(document).ready(function () {
    $(document).on('click', '#saveWhy', function (e) {
        e.preventDefault();

        var contentWhy = $('#contentWhy').val().trim();
        var imageWhy = $('#imageWhy')[0].files[0];

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        var isValid = true;

        if (contentWhy === '') {
            $('#contentWhyError').removeClass('d-none');
            isValid = false;
        } else {
            $('#contentWhyError').addClass('d-none');
        }
        if (imageWhy) {
            var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validExtensions.includes(imageWhy.type)) {
                $('#imageWhyError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan, dan gambar tidak boleh kosong.').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageWhyError').addClass('d-none');
            }
        } else if (!$('#previewContainer img').length) {
            $('#imageWhyError').removeClass('d-none');
            isValid = false;
        } else {
            $('#imageWhyError').addClass('d-none');
        }

        if (isValid) {
            Swal.fire({
                title: "Apakah Anda yakin?",
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
                    formData.append('contentWhy', contentWhy);
                    if (imageWhy) {
                        formData.append('imageWhy', imageWhy);
                    }
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
                        type: "POST",
                        url: "<?php echo e(route('addWhy')); ?>",
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
                                    title: 'Kesalahan',
                                    text: response.error
                                });
                            }
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    var previewContainer = $('#previewContainer');
                                    previewContainer.html('');

                                    if (response.data.imageWhy) {
                                        previewContainer.append('<img src="<?php echo e(asset("storage/images/")); ?>/' + response.data.imageWhy + '" width="600px" style="padding:5px 30px;">');
                                    }

                                    if (response.data.contentWhy) {
                                        previewContainer.append('<p style="margin-left:30px;">' + response.data.contentWhy + '</p>');
                                    }
                                    $('#imageName').text(response.data.imageWhy || ' -');
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal menambahkan data",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: "Kesalahan",
                                text: "Terjadi kesalahan: " + error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: "Periksa input",
                text: "Silakan periksa apakah ada input yang kosong atau tidak valid",
                icon: "warning"
            });
        }
    });
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\content\whys\indexwhy.blade.php ENDPATH**/ ?>