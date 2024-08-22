<x-layout>

  @section('title', 'PT. GES')
  <!-- popup -->
  <dialog id="welcome-dialog">
    <h2>Selamat Datang</h2>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
      Pariatur dolorum porro nulla deleniti atque cumque,
      minus laudantium autem quas veritatis repudiandae ut soluta eveniet alias vero quaerat tempora unde odio!</p>
    <div class="controls">
      <button class="about-btn">Go to About</button>
    </div>
    <div class="controls">
      <button class="close-btn">Close</button>
    </div>
  </dialog>

  <!-- Carousel -->
  <div id="Home">
  <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      @foreach($listcarousel as $index => $carousel)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
          <img class="d-block w-100 bg-1" src="{{ asset('storage/images/' . $carousel->image_carousel) }}" alt="{{ $carousel->judul_carousel }}">
          <div class="carousel-caption">
            <h5 id="judulCarousel">{{ $carousel->judul_carousel }}</h5>
            <p id="parafCarousel">{{ $carousel->isi_carousel }}</p>
            <a class="bg-primary bg-gradient text-white" href="/Slide">Learn More</a>
          </div>
        </div>
      @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
    <div class="carousel-indicators">
      @foreach($listcarousel as $index => $carousel)
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
      @endforeach
    </div>
  </div>
</div>
    <!-- Main -->
    <div class="main">
      <!-- Gallery -->
      <div class="wrapperGallery @if(count($listinformation) == 1) single-gallery @endif">
        <h1 class="section-header">Information</h1>
        <div class="main-content">
          @foreach($listinformation as $info)
        <div class="box-gallery">
        <img src="{{ asset('storage/images/' . $info->image_informations) }}" alt="{{ $info->judul_informations }}">
        <div class="img-text">
          <div class="contentGallery">
          <h2>{{ $info->judul_informations }}</h2>
          <p>{{ $info->isi_informations }}</p>
          </div>
        </div>
        </div>
      @endforeach
        </div>
      </div>
      {{-- <div class="box">
        <img src="" alt="">
        <div class="img-text">
          <div class="contentGallery">
            <h2>Plane</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut,
              minima.</p>
          </div>
        </div>
      </div>
      <div class="box">
        <img src="" alt="">
        <div class="img-text">
          <div class="contentGallery">
            <h2>Box</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut,
              minima.</p>
          </div>
        </div>
      </div>
      <div class="box">
        <img src="" alt="">
        <div class="img-text">
          <div class="contentGallery">
            <h2>Ship</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut,
              minima.</p>
          </div>
        </div>
      </div>
      <div class="box">
        <img src="" alt="">
        <div class="img-text">
          <div class="contentGallery">
            <h2>Logistic</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut,
              minima.</p>
          </div>
        </div>
      </div>
      <div class="box">
        <img src="" alt="">
        <div class="img-text">
          <div class="contentGallery">
            <h2>Marketing</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut,
              minima.</p>
          </div>
        </div>
      </div> --}}
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
          <p id="parafAbout">{{ $aboutus->Paraf_AboutUs }}</p>
          <a href="/About" class="btn">Learn More</a>
        </div>
        <div class="image" id="imageAbout">
          <img src="{{ asset('storage/images/' . $aboutus->Image_AboutUs) }}" style="border-radius:30px;">
        </div>
      </div>
    </div>
  </section>

  <!-- WHY US -->
  <div class="whyus">
    <div class="wrapperwhy" id="Why">
      <div class="why">
        <div class="image-sectionwhy">
          <img src="{{ asset('storage/images/' . $whyus->Image_WhyUs) }}" id="imageWhy">
        </div>
        <article>
          <h3 id="judulWhy">Why Choose Us</h3>
          <p id="parafWhy">{{ $whyus->Paraf_WhyUs }}</p>
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
    <div id="card-area" class="@if(count($listservices) == 1) single-service @elseif(count($listservices) == 2) double-service @elseif(count($listservices) == 3) triple-service @endif">
      <div class="wrapper">
        <div class="box-area">
          @foreach($listservices as $service)
            <div class="box">
              <img alt="" src="{{ asset('storage/images/' . $service->image_service) }}"
                alt="{{ $service->judul_service }}">
              <div class="overlay">
                <h3>{{ $service->judul_service }}</h3>
                <p>{{ $service->isi_service }}</p>
                <div class="button-container">
                  <a href="{{ url('/Services?id=' . $service->id) }}" class="btn-modern">Read More</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
</div>
</div>
    <!-- iklan slide -->
    <div class="logos">
      <div class="logos-slide">
        @foreach($listiklan as $iklan)
      <img src="{{ asset('storage/images/' . $iklan->image_iklan) }}" alt="{{ $iklan->judul_iklan }}">
    @endforeach
      </div>
    </div>

  </div>
  <div class="container d-flex justify-content-center" style="margin-bottom:30px; margin-top:100px;">
    <div class="card col-10">
      <div class="card-body">
        <div class="row">
          <div class="col-4" style="margin-top:10px;">
            <h3 class="textMaps">Maps</h3>
            <p>42Q2+6PH, Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau</p>
          </div>
          <div class="col-8">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.0312703091595!2d103.99927157494255!3d1.1380710622200323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98b04082b3703%3A0x182bec3b6cdd92d5!2sGES%20PT%20Global%20Expedisi%20Solusi!5e0!3m2!1sen!2sid!4v1722416419776!5m2!1sen!2sid"
              width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>


  @section('script')

  @endsection


</x-layout>