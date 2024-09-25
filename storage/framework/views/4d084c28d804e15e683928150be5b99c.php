<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['contact' => $contact ?? '','wa' => $wa ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['contact' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($contact ?? ''),'wa' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wa ?? '')]); ?>
    <?php $__env->startSection('title', 'PT. GES | Heropage'); ?>
<body class="navigasi-page">
<div class="JudulSlide text-primary" style="margin-top:100px; text-align:center;  font-family: cursive;  ">
    <h2 style="font-size:70px;"><?php echo e($dataHeropage[0]->title_heropage); ?></h2>
</div>
    <div class="mb-5 mt-3 ms-5">
        <p>
        <?php echo e($dataHeropage[0]->content_heropage); ?>

        </p>
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
<?php endif; ?>
<?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\landingpage\Slide.blade.php ENDPATH**/ ?>