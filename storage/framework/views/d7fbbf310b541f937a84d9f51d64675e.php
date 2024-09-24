<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('main'); ?>
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Reset Password for <?php echo e($username); ?></h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="resetPasswordForm">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="token" value="<?php echo e($token); ?>">
                            <input type="hidden" name="email" value="<?php echo e($email); ?>">

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input id="password" class="form-control" type="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('#resetPasswordForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: '<?php echo e(route('password.update')); ?>',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successful',
                            text: 'Your password has been updated successfully!',
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            window.location.href = '<?php echo e(route('dashboard')); ?>';
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            for (const key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMessage += errors[key][0] + '<br>';
                                }
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: errorMessage,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong',
                                text: 'Please try again later.',
                                showConfirmButton: true
                            });
                        }
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\auth\reset-password.blade.php ENDPATH**/ ?>