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

  
  <?php $__env->startSection('title', 'PT. GES'); ?>
  <!-- popup -->
  <?php if(isset($popup) && ($popup->Image_Popup || $popup->Judul_Popup || $popup->Paraf_Popup || $popup->Link_Popup)): ?>
    <dialog id="welcome-dialog" class="popup-dialog">
    <?php if($popup->Image_Popup): ?>
    <img src="<?php echo e(asset('storage/images/' . $popup->Image_Popup)); ?>" alt="Popup Image" class="popup-image">
  <?php endif; ?>

    <?php if($popup->title_Popup): ?>
    <h2 class="popup-title"><?php echo e($popup->title_Popup); ?></h2>
  <?php endif; ?>

    <?php if($popup->Paragraph_Popup): ?>
    <p class="popup-text"><?php echo e($popup->Paragraph_Popup); ?></p>
  <?php endif; ?>

    <?php if($popup->Link_Popup): ?>
    <div class="controls">
      <a class="btn-Go btn btn-primary" href="<?php echo e($popup->Link_Popup); ?>" style="text-decoration:none; color:white; width:150px;">
          Learn More
      </a>
    </div>
  <?php endif; ?>
    </dialog>
  <?php endif; ?>
  <!-- Carousel -->
 <div id="Home">
    <?php if(count($listheropage) > 0): ?>
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php $__currentLoopData = $listheropage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $heropage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
          <img class="d-block w-100 carousel-image" src="<?php echo e(asset('storage/images/' . $heropage->image_heropage)); ?>"
          alt="<?php echo e($heropage->title_heropage); ?>">
          <div class="carousel-caption">
            <h5 id="judulCarousel"><?php echo e($heropage->title_heropage); ?></h5>
            <p id="parafCarousel"><?php echo e($heropage->content_heropage); ?></p>
            <a class="bg-primary bg-gradient text-white" href="<?php echo e(url('/Slide?id=' . $heropage->id)); ?>">Learn More</a>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <?php if(count($listheropage) > 1): ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
      data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
      </button>
      <div class="carousel-indicators">
        <?php $__currentLoopData = $listheropage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $heropage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo e($index); ?>"
        class="<?php echo e($index === 0 ? 'active' : ''); ?>" aria-current="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
        aria-label="Slide <?php echo e($index + 1); ?>"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <div id="carouselExampleCaptions" class="carousel slide">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100 carousel-image" src="img/Default.jpg"
          alt="No Image">
          <div class="carousel-caption">
            <h5 id="judulCarousel">No Image Available</h5>
            <p id="parafCarousel">There are no images to display.</p>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
</div>

  <!-- Main -->
  <div class="main">
<!--information -->
<div class="wrapperGallery <?php if(count($listinformation) == 1): ?> single-gallery <?php endif; ?>">
  <h1 class="section-header">Information</h1>
  <div class="main-content">
    <?php if(count($listinformation) > 0): ?>
      <?php $__currentLoopData = $listinformation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="box-gallery">
          <img src="<?php echo e(asset('storage/images/' . $info->image_informations)); ?>"
          alt="<?php echo e($info->title_informations ?? '-'); ?>" class="img-fluid">
          <div class="img-text">
            <div class="contentGallery">
              <h2><?php echo e($info->title_informations ?? '-'); ?></h2>
              <p><?php echo e($info->content_informations ?? '-'); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
    <div class="box-gallery text-center no-info">
        <img src="<?php echo e(asset('/img/Noimage.png')); ?>" alt="No Image Available" class="img-fluid">
        <div class="img-text">
          <div class="contentGallery">
            <h2>No Information Available</h2>
            <p>There is no information to display.</p>
          </div>
        </div>
      </div>
      <div class="box-gallery text-center no-info">
        <img src="<?php echo e(asset('/img/Noimage.png')); ?>" alt="No Image Available" class="img-fluid">
        <div class="img-text">
          <div class="contentGallery">
            <h2>No Information Available</h2>
            <p>There is no information to display.</p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

    
  </div>
  </div>
  <!-- About us -->
  <section id="About" class="AboutSection">
  <div class="containerAbout">
    <div class="wrapper-About">
      <div class="content">
        <div class="heading">
          <h1 style="font-size:32px;">About Us</h1>
        </div>
        <h2>What they say about us</h2>
        <p id="parafAbout"><?php echo e($aboutus->Paragraph_AboutUs ?? '-'); ?></p>
        <a href="/About" class="btn">Learn More</a>
      </div>
      <div class="image" id="imageAbout">
        <?php if(!empty($aboutus->Image_AboutUs)): ?>
          <img src="<?php echo e(asset('storage/images/' . $aboutus->Image_AboutUs)); ?>" style="border-radius:30px;">
        <?php else: ?>
          <img src="<?php echo e(asset('/img/Default.jpg')); ?>" alt="No Image Available" style="border-radius:30px;">
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

  <!-- WHY US -->
  <div class="whyus">
    <div class="wrapperwhy" id="Why">
      <div class="why">
        <div class="image-sectionwhy">
        <?php if(!empty($whyus->Image_WhyUs)): ?>
          <img src="<?php echo e(asset('storage/images/' . $whyus->Image_WhyUs)); ?>" id="imageWhy">
          <?php else: ?>
          <img src="<?php echo e(asset('/img/Default.jpg')); ?>" alt="No Image Available">
        <?php endif; ?>
        </div>
        <article>
          <h3 id="judulWhy">Why Choose Us</h3>
          <p id="parafWhy"><?php echo e($whyus->Paragraph_WhyUs ?? '-'); ?></p>
          <div class="buttonwhy">
            <a href="/Why">Learn More</a>
          </div>
        </article>
      </div>
    </div>
  </div>
  <!-- Services -->
  <div class="section" id="Services">
  <div class="title">
    <h1>Our Services</h1>
  </div>
  <div id="card-area" class="wrapper">
    <div class="container mt-5">
      <div class="row d-flex justify-content-center align-items-center" style="min-height: 300px;">
        <?php if(count($listservices) > 0): ?>
          <?php $__currentLoopData = $listservices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-4"> 
              <div class="box">
                <img src="<?php echo e(asset('storage/images/' . $service->image_service)); ?>"
                  alt="<?php echo e($service->title_service ?? '-'); ?>">
                <div class="overlay">
                  <h3><?php echo e($service->title_service ?? '-'); ?></h3>
                  <p><?php echo e($service->content_service ?? '-'); ?></p>
                  <div class="button-container">
                    <a href="<?php echo e(url('/Services?id=' . $service->id) ?? '-'); ?>" class="btn-modern">Read More</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
          <div class="col-md-4 d-flex flex-column justify-content-center align-items-center text-center">
            <div class="box" style="height: 300px; width: 400px;">
              <img src="<?php echo e(asset('/img/default.jpg')); ?>" alt="No Image Available" class="mb-3">
              <div class="overlay">
                <h3>No Services Available</h3>
                <p>There are currently no services to display.</p>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>




  <!-- iklan slide -->
  <div class="logos <?php echo e(count($listiklan) > 0 ? '' : 'hidden'); ?>">
  <div class="logos-slide">
    <?php $__currentLoopData = $listiklan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $iklan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <img src="<?php echo e(asset('storage/images/' . $iklan->image_Advertisement)); ?>" alt="<?php echo e($iklan->title_Advertisement ?? '-'); ?>">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>

  </div>
  <div class="Contact" id="Contact">
    <div class="container" style="margin-bottom:30px; margin-top:80px;">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-12 mb-4">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.0312703091595!2d103.99927157494255!3d1.1380710622200323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98b04082b3703%3A0x182bec3b6cdd92d5!2sGES%20PT%20Global%20Expedisi%20Solusi!5e0!3m2!1sen!2sid!4v1724405271539!5m2!1sen!2sid"
            width="90%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-lg-5 col-md-12">
          <p class="textMaps" style="margin-top:10px; font-size:40px; font-family: sans-serif;">Contact <i
              style="color:#80AF81;">Us</i></p>
          <p class="pt-3" style="color:#1679AB; font-size: 20px; font-family: sans-serif;">42Q2+6PH, Unnamed Road,
            Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau</p>
          <div class="Contact-Us" style="font-size:17px; color:#1679AB;">
            <p><i class="ph ph-envelope fa-xl"></i> <?php echo e($contact->email ?? '-'); ?></p>
            <p><i class="ph ph-phone fa-xl"></i> <?php echo e(is_object($contact) && isset($contact->phone) ? '+62' . $contact->phone : '-'); ?></p>
            <p><i class="ph ph-phone-plus fa-xl"></i> <?php echo e(is_object($contact) && isset($contact->phones) ? '+62' . $contact->phones : '-'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <?php $__env->startSection('script'); ?>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      if (window.location.hash) {
        const urlWithoutHash = window.location.href.split('#')[0];
        window.history.replaceState(null, null, urlWithoutHash);
      }
    });
    document.addEventListener('DOMContentLoaded', function () {
      var myCarousel = document.querySelector('#carouselExampleCaptions')
      var carouselSlide = new bootstrap.Carousel(myCarousel, {
        interval: 4100,
        ride: 'carouselSlide'
      });
    });
    

  </script>

  <?php $__env->stopSection(); ?>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $attributes = $__attributesOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__attributesOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $component = $__componentOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__componentOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?><?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\landingpage\PTGes.blade.php ENDPATH**/ ?>