<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['dataPtges' => $dataPtges ?? '','wa' => $wa ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dataPtges' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dataPtges ?? ''),'wa' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wa ?? '')]); ?>
    <?php $__env->startSection('title', 'PT. GES | Services'); ?>
    <body class="navigasi-page">
        <div class="container d-flex justify-content-center mb-5">
            <div class="card col-lg-8 col-md-10 col-sm-12 mb-4" style="margin-top:100px; border-radius:20px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-5">
                        <img src="<?php echo e(asset('storage/images/' . $dataService[0]->image_service)); ?>" alt="<?php echo e($dataService[0]->title_service); ?>" class="img-fluid lazyload" style="max-width: 100%; height: 500px; border-radius:20px;" loading="lazy">
                    </div>
                    <div class="col-12">
                        <h3 class="text-primary"><?php echo e($dataService[0]->title_service); ?></h3>
                        <p class="text-start m-1"><?php echo nl2br( e( $dataService[0]->content_service )); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $attributes = $__attributesOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__attributesOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $component = $__componentOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__componentOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\landingpage\Services.blade.php ENDPATH**/ ?>