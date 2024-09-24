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
  <?php $__env->startSection('title', 'PT. GES | Why Us'); ?>
  <body class="navigasi-page">
    <div class="Judulwhy">
      <h2>Why Us</h2>
    </div>
    <?php if($dataWhy): ?>
    <div class="content-containerwhy">
      <div class="imageWhy">
        <img src="<?php echo e(asset('storage/images/' . $dataWhy->Image_WhyUs)); ?>" id="imageWhy" alt="Why Us Image">
      </div>
      <div class="contentwhy">
        <h2 id="judulWhy">Why Choose Us</h2>
        <p id="parafWhy"><?php echo e($dataWhy->Paragraph_WhyUs); ?></p>
      </div>
    </div>
    <?php else: ?>
    <div class="content-containerwhy">
    <div class="imageWhy">
    <img src="<?php echo e(asset('/img/default.jpg')); ?>" alt="Why Us Image" style="height:350px; width:500px;">
      </div>
     
    <div class="contentwhy">
      <p>-</p>
    </div>
    </div>
    
    <?php endif; ?>
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
<?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views/landingpage/Why.blade.php ENDPATH**/ ?>