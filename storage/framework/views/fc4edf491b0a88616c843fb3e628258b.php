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
                                        <h1 class="h4 text-gray-900 mb-4">Forgot Password</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <?php if(session('status')): ?>
                                                        <div class="alert alert-success">
                                                            <?php echo e(session('status')); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <form method="POST" action="<?php echo e(route('password.email')); ?>">
                                                        <?php echo csrf_field(); ?>

                                                        <div class="form-group">
                                                            <label for="email">Email Address</label>
                                                            <input id="email" class="form-control" type="email" name="email" required autofocus>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                                                    </form>

                                                    <div class="form-group text-center mt-3">
                                                        <a href="<?php echo e(route('login')); ?>" class="btn btn-secondary btn-block">Back to
                                                            login</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>