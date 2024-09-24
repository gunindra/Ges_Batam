<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/PTGes">
                <img src="/img/logo4.png" width="100px" class="navbar-logo" alt="Logo"> 
        </a>
        <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Route::is('PTGes') ? 'active' : ''); ?>" href="/PTGes">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Route::is('About') ? 'active' : ''); ?>" href="/About">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Route::is('Why') ? 'active' : ''); ?>" href="/Why">Why Us</a>
                </li>
                <?php if(!Route::is('About') && !Route::is('Why') && !Route::is('Slide') &&  !Route::is('Tracking')): ?>
                <li class="nav-item">
                    <a class="nav-link btn <?php echo e(request()->is('Services')? 'active' : ''); ?>" onclick="scrollToSection('Services')" >Service</a>
                </li>
                <?php if(!Route::is('Services') && !Route::is('Services1') && !Route::is('Services2')): ?>
                <li class="nav-item">
                    <a class="nav-link btn <?php echo e(request()->is('#Contact')  ? 'active' : ''); ?>"  onclick="scrollToSection('Contact')">Contact Us</a>
                </li>
                <?php endif; ?>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->is('Tracking') ? 'active' : ''); ?>" href="/Tracking">Tracking</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('login')); ?>" style="text-decoration:none; color:white;">
                        <button type="button" class="btn btn-outline-primary text-white"><b>LOGIN</b></button>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\components\navbar.blade.php ENDPATH**/ ?>