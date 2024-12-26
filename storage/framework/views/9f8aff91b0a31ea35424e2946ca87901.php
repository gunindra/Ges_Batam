<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        body {
            background-image: url('<?php echo e(asset('img/LogisticBackground.jpg')); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                    </div>
                                    <form id="loginForm" class="user" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="name"
                                                required aria-describedby="nameHelp" placeholder="Enter Name or Marking">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required placeholder="Password">
                                        </div>
                                        <a href="#" id="lupaPassword" class="my-2">Forgot Password?</a>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                                        </div>
                                        <div class="form-group text-center">
                                            <a href="<?php echo e(route('PTGes')); ?>" class="btn btn-secondary btn-block">Back to
                                                Home</a>
                                        </div>
                                    </form>
                                    <div id="errorMessage" class="text-danger text-center mt-2" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Checking...',
                    html: 'Please wait while we check your credentials.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "<?php echo e(route('login.ajax')); ?>",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.close();

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            // $('#errorMessage').text(response.message).show();
                            showMessage("error", response.message)
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Server error occurred. Please try again.',
                            showConfirmButton: true
                        });
                    }
                });
            });

            $('#lupaPassword').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Forgot Password',
                    text: 'Please contact your IT support for assistance.',
                    showConfirmButton: true
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views/login/indexlogin.blade.php ENDPATH**/ ?>