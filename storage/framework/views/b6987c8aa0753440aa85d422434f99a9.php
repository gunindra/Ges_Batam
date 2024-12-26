<?php $__env->startSection('title', 'Verify Email'); ?>

<?php $__env->startSection('main'); ?>
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Email Verification</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <?php if($verified): ?>
                            <div class="alert alert-success col-6">
                                Your email is already verified.
                            </div>
                            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">Go to Dashboard</a>
                        <?php else: ?>
                            <div class="alert alert-danger col-6">
                                Your email is not verified yet. Please check your email for the verification link.
                            </div>
                            <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>