<?php $__env->startSection('title', 'Content | About'); ?>

<?php $__env->startSection('main'); ?>

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">About</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">About</li>
        </ol>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div id="containerAbout" class="table-responsive px-3">
                    </div>
                    <div class="mt-3">
                        <label for="imageAbout" class="form-label fw-bold p-1">Image</label>
                        <input type="file" class="form-control" id="imageAbout" value="">
                        <div id="imageAboutError" class="text-danger mt-1 d-none">Please fill in the Image</div>
                        <p>Name Image = <span id="imageName"><?php echo e($aboutData->Image_AboutUs ?? ' -'); ?></span></p>
                    </div>
                    <div class="input-group pt-2 mt-3">
                        <label for="contentAbout" class="form-label fw-bold p-3">Content</label>
                        <textarea id="contentAbout" class="form-control" aria-label="With textarea"
                            placeholder="Masukkan content"><?php echo e($aboutData->Paragraph_AboutUs ?? ''); ?></textarea>
                        <!-- <div id="editor">

                            </div> -->
                    </div>
                    <div id="contentAboutError" class="text-danger mt-1 d-none">Please fill in the Content</div>
                    <button type="button" class="btn btn-primary mt-3" id="saveAbout">
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
                        <?php if($aboutData): ?>
                            <?php if($aboutData->Image_AboutUs): ?>
                                <img src="<?php echo e(asset('storage/images/' . $aboutData->Image_AboutUs)); ?>" width="600px"
                                    style="padding:5px 30px;">
                                <p style="margin-left:30px;"><?php echo e($aboutData->Paragraph_AboutUs ?? ''); ?></p>
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
<script src="ckeditor/ckeditor5.js"></script>
<!---Container Fluid-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function () {
        $(document).on('click', '#saveAbout', function (e) {
            e.preventDefault();

            var contentAbout = $('#contentAbout').val().trim();
            var imageAbout = $('#imageAbout')[0].files[0];
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (contentAbout === '') {
                $('#contentAboutError').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentAboutError').addClass('d-none');
            }

            // Check if imageAbout is empty or not valid
            if (imageAbout) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageAbout.type)) {
                    $('#imageAboutError').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageAboutError').addClass('d-none');
                }
            } else if (!$('#previewContainer img').length) {
                $('#imageAboutError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageAboutError').addClass('d-none');
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
                        var formData = new FormData();
                        formData.append('contentAbout', contentAbout);
                        if (imageAbout) {
                            formData.append('imageAbout', imageAbout);
                        }
                        formData.append('_token', csrfToken);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your data about.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "<?php echo e(route('addAbout')); ?>",
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

                                    Swal.fire({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success"
                                    }).then(() => {
                                        var previewContainer = $('#previewContainer');
                                        previewContainer.html('');

                                        if (response.data.imageAbout) {
                                            previewContainer.append('<img src="<?php echo e(asset("storage/images/")); ?>/' + response.data.imageAbout + '" width="600px" style="padding:5px 30px;">');
                                        }
                                        if (response.data.contentAbout) {
                                            previewContainer.append('<p style="margin-left:30px;">' + response.data.contentAbout + '</p>');
                                        }
                                        $('#imageName').text(response.data.imageAbout || ' -');
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Failed to add data.",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    title: "Error",
                                    text: "An error occurred: " + error,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "Check input",
                    text: "Please check for empty or invalid input",
                    icon: "warning"
                });
            }
        });

    });

    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    });



</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\content\abouts\indexabout.blade.php ENDPATH**/ ?>