<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport"
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    
    <title>PT. GES | <?php echo $__env->yieldContent('title'); ?></title>
    <link href="<?php echo e(asset('RuangAdmin/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('RuangAdmin/vendor/bootstrap/css/bootstrap.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('RuangAdmin/css/ruang-admin.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('RuangAdmin/vendor/datatables/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('RuangAdmin/vendor/select2/dist/css/select2.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('RuangAdmin/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css')); ?>"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/flatpickr.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/monthSelect.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/inputTags.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>

</head>

<body id="page-top">

    <?php echo $__env->yieldContent('content'); ?>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <script src="<?php echo e(asset('RuangAdmin/vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/js/ruang-admin.min.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/vendor/chart.js/Chart.min.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/js/demo/chart-area-demo.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('RuangAdmin/vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src=" <?php echo e(asset('RuangAdmin/vendor/select2/dist/js/select2.min.js')); ?>"></script>
    <script src=" <?php echo e(asset('RuangAdmin/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/sweetalert2.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/flatpickr.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/monthSelect.js')); ?>"></script>
    <script src="<?php echo e(asset('js/index.js')); ?>"></script>
    <script src="js/signature_pad.umd.min.js"></script>
    <script src="js/app.js"></script>
    <script>
        function showMessage(type, message) {

            if (!type || type === '' || !message || message === '') {
                return;
            }

            return Swal.fire({
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 2000
            })

        }
    </script>

    <?php echo $__env->yieldContent('script'); ?>
</body>

</html>
<?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\layout\app.blade.php ENDPATH**/ ?>