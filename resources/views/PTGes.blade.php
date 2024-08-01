<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTGes</title>
    <link rel="stylesheet" href="/css/style.css" >
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body style="background-color:#E2DAD6;">
    <!-- popup -->
  <dialog>
        <h2>Selamat Datang</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Pariatur dolorum porro nulla deleniti atque cumque,
             minus laudantium autem quas veritatis repudiandae ut soluta eveniet alias vero quaerat tempora unde odio!</p>
        <div class="controls">
            <button class="close-btn">Close</button>
            <button>Ok</button>
        </div>
    </dialog>
    <!-- navbar -->
<x-navbar></x-navbar>
<!-- Carousel -->
<div id="carouselExampleCaptions" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="/img/Truck.jpg"  class="d-block w-100" alt="...">
      <div class="carousel-caption" style="bottom:240px; z-index:2;">
        <h5>First Slide Label</h5>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Obcaecati asperiores dignissimos numquam maxime debitis iusto deserunt molestias quisquam voluptas. Provident placeat culpa voluptate doloribus voluptatibus incidunt vero obcaecati, id facere?</p>
        <p><a href="#" class="btn btn-warning mt-3">Learn More</a></p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="/img/Werehouse.jpg"  class="d-block w-100" alt="...">
      <div class="carousel-caption" style="bottom:240px; z-index:2;">
        <h5>Second slide label</h5>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Inventore quisquam nam quidem ducimus modi totam! Aperiam similique totam architecto, voluptatum, itaque nostrum reprehenderit harum nisi minima sed aliquid voluptatem earum?</p>
        <p><a href="#" class="btn btn-warning mt-3">Learn More</a></p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="/img/Ship.jpg"   class="d-block w-100" alt="...">
      <div class="carousel-caption" style="bottom:240px; z-index:2;">
        <h5>Third slide label</h5>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae optio molestiae molestias magnam dolores itaque doloremque ea aperiam, soluta, sequi sit cupiditate rem perspiciatis assumenda sed a, nemo possimus omnis.</p>
        <p><a href="#" class="btn btn-warning mt-3">Learn More</a></p>
        </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- <div class="parallax-image parallax-image-01">
</div>
      -->
    <!-- Main -->
    <div class="main">

      <!-- About us -->
      <section id="About" class ="AboutSection">
        <div class="headingAbout">
          <h2>About Us</h2>
        </div>
        <div class ="About">
          <div class="About-content">
            <h2>Welcome To Our Website</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
               Odit possimus cumque repellat laborum magni, vitae inventore rerum repellendus aut sunt,
                deleniti saepe esse vero sapiente sit, beatae ratione necessitatibus! Doloremque!</p>
            <a class="cta-button" href="/About">
              learn more &raquo;
            </a>
            </div>
              <div class="About-image">
                <img src="/img/Aboutus.jpg" width="500px">
              </div>
          </div>
      </section>

      <!-- WHY US -->
      <section id="Why" class="SectionWhy">
        <div class="headingWhy">
          <h2>Why Us</h2>
        </div>
        <div class ="Why">
          <div class="Why-content">
            <h2>Welcome To Our Website</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
               Odit possimus cumque repellat laborum magni, vitae inventore rerum repellendus aut sunt,
                deleniti saepe esse vero sapiente sit, beatae ratione necessitatibus! Doloremque!</p>
            <a class="cta-button" href="/Why">
              learn more &raquo;
            </a>
            </div>
              <div class="Why-image">
                <img src="/img/Whyus.jpg" width="500px">
              </div>
          </div>
      </section>

      <!-- Services -->
      <section id="Service" class="ServicesSection">
        <div class="headingServices">
          <h2>Services</h2>
        </div>
        <div class="card-container">
          <div class="card">
            <img src="/img/Logistik.jpg">
            <div class="card-content">
              <h3>Card 1</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                 Maxime error, tenetur enim tempora quasi possimus? Illum porro velit temporibus.
                 Ab temporibus voluptas a cumque sequi provident vel, explicabo nesciunt molestias?</p>
                 <a href="" class="btn">Read More &raquo;</a>
            </div>
          </div>
          <div class="card">
            <img src="/img/TruckLorry.jpg" >
            <div class="card-content">
              <h3>Card 2</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                 Maxime error, tenetur enim tempora quasi possimus? Illum porro velit temporibus.
                 Ab temporibus voluptas a cumque sequi provident vel, explicabo nesciunt molestias?</p>
                 <a href="" class="btn">Read More &raquo;</a>
            </div>
          </div>
          <div class="card">
            <img src="/img/Industrial.jpg" >
            <div class="card-content">
              <h3>Card 2</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                 Maxime error, tenetur enim tempora quasi possimus? Illum porro velit temporibus.
                 Ab temporibus voluptas a cumque sequi provident vel, explicabo nesciunt molestias?</p>
                 <a href="" class="btn">Read More &raquo;</a>
            </div>
          </div>
        </div>
      </section>
      <section class="Iklansection">
        <div class="iklan1">
          <img src="/img/Iklan.jpg" width="200px">
        </div>
        <div class="iklan1">
          <img src="/img/Iklan2.jpg" width="200px">
        </div>
      </section>
    </div>

    <!-- Maps -->
    <div class="Maps">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.0312703091595!2d103.99927157494255!3d1.1380710622200323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98b04082b3703%3A0x182bec3b6cdd92d5!2sGES%20PT%20Global%20Expedisi%20Solusi!5e0!3m2!1sen!2sid!4v1722416419776!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

        <!-- Footer -->
  <x-footer></x-footer>
        <!-- Footer -->
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f89fc2c44e.js" crossorigin="anonymous"></script>
    <script src="/js/script.js"></script>
  </body>
</html>
