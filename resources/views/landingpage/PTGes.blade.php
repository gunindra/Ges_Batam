<x-layout :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''">

  
  @section('title', 'PT. GES')
  <!-- popup -->
  @if(isset($popup) && ($popup->Image_Popup || $popup->Judul_Popup || $popup->Paraf_Popup || $popup->Link_Popup))
    <dialog id="welcome-dialog" class="popup-dialog">
    @if($popup->Image_Popup)
    <img src="{{ asset('storage/images/' . $popup->Image_Popup) }}" alt="Popup Image" class="popup-image">
  @endif

    @if($popup->title_Popup)
    <h2 class="popup-title">{{$popup->title_Popup}}
    </h2>
  @endif

    @if($popup->Paragraph_Popup)
    <p class="popup-text">{!! nl2br(e( $popup->Paragraph_Popup)) !!}</p>
  @endif

    @if($popup->Link_Popup)
    <div class="controls">
      <a class="btn-Go btn btn-primary" href="{{ $popup->Link_Popup }}" style="text-decoration:none; color:white; width:150px;">
          Learn More
      </a>
    </div>
  @endif
    </dialog>
  @endif
  <!-- Carousel -->
 <div id="Home">
    @if(count($listheropage) > 0)
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        @foreach($listheropage as $index => $heropage)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
          <img class="d-block w-100 carousel-image" src="{{ asset('storage/images/' . $heropage->image_heropage) }}"
          alt="{{ $heropage->title_heropage }}">
          <div class="carousel-caption">
            <h5 id="judulCarousel">{{$heropage->title_heropage}}</h5>
            <p id="parafCarousel">{!! nl2br(e($heropage->content_heropage)) !!}</p>
            <a class="bg-primary bg-gradient text-white" href="{{ url('/Slide?id=' . $heropage->id) }}">Learn More</a>
          </div>
        </div>
        @endforeach
      </div>

      @if(count($listheropage) > 1)
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
        @foreach($listheropage as $index => $heropage)
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}"
        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
        aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
      </div>
      @endif
    </div>
    @else
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
    @endif
</div>

  <!-- Main -->
  <div class="main">
<!--information -->
<div class="wrapperGallery @if(count($listinformation) == 1) single-gallery @endif">
  <h1 class="section-header">Information</h1>
  <div class="main-content">
    @if(count($listinformation) > 0)
      @foreach($listinformation as $info)
        <div class="box-gallery">
          <img src="{{ asset('storage/images/' . $info->image_informations) }}"
          alt="{{ $info->title_informations ?? '-'}}" class="img-fluid">
          <div class="img-text">
            <div class="contentGallery">
              <h2>{{ $info->title_informations ?? '-'}}</h2>
              <p>{!! nl2br( e($info->content_informations ?? '-')) !!}</p>
            </div>
          </div>
        </div>
      @endforeach
    @else
    <div class="box-gallery text-center no-info">
        <img src="{{ asset('/img/Noimage.png') }}" alt="No Image Available" class="img-fluid">
        <div class="img-text">
          <div class="contentGallery">
            <h2>No Information Available</h2>
            <p>There is no information to display.</p>
          </div>
        </div>
      </div>
      <div class="box-gallery text-center no-info">
        <img src="{{ asset('/img/Noimage.png') }}" alt="No Image Available" class="img-fluid">
        <div class="img-text">
          <div class="contentGallery">
            <h2>No Information Available</h2>
            <p>There is no information to display.</p>
          </div>
        </div>
      </div>
    @endif
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
        <p id="parafAbout">{!! nl2br( e( $dataPtges->Paragraph_AboutUs ?? '-')) !!}</p>
        <a href="/About" class="btn">Learn More</a>
      </div>
      <div class="image" id="imageAbout">
        @if (!empty($dataPtges->Image_AboutUs))
          <img src="{{ asset('storage/images/' . $dataPtges->Image_AboutUs) }}" style="border-radius:30px;">
        @else
          <img src="{{ asset('/img/Default.jpg') }}" alt="No Image Available" style="border-radius:30px;">
        @endif
      </div>
    </div>
  </div>
</section>

  <!-- WHY US -->
  <div class="whyus">
    <div class="wrapperwhy" id="Why">
      <div class="why">
        <div class="image-sectionwhy">
        @if (!empty($dataPtges->Image_WhyUs))
          <img src="{{ asset('storage/images/' . $dataPtges->Image_WhyUs) }}" id="imageWhy">
          @else
          <img src="{{ asset('/img/Default.jpg') }}" alt="No Image Available">
        @endif
        </div>
        <article>
          <h3 id="judulWhy">Why Choose Us</h3>
          <p id="parafWhy">{!! nl2br( e( $dataPtges->Paragraph_WhyUs ?? '-' )) !!}</p>
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
        @if(count($listservices) > 0)
          @foreach($listservices as $service)
            <div class="col-md-4 mb-4"> 
              <div class="box">
                <img src="{{ asset('storage/images/' . $service->image_service) }}"
                  alt="{{ $service->title_service ?? '-' }}">
                <div class="overlay">
                  <h3>{{$service->title_service ?? '-' }}</h3>
                  <p>{!! nl2br( e( $service->content_service ?? '-')) !!}</p>
                  <div class="button-container">
                    <a href="{{ url('/Services?id=' . $service->id) ?? '-' }}" class="btn-modern">Read More</a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-md-4 d-flex flex-column justify-content-center align-items-center text-center">
            <div class="box" style="height: 300px; width: 400px;">
              <img src="{{ asset('/img/default.jpg') }}" alt="No Image Available" class="mb-3">
              <div class="overlay">
                <h3>No Services Available</h3>
                <p>There are currently no services to display.</p>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>




  <!-- iklan slide -->
  <div class="logos {{ count($listiklan) > 0 ? '' : 'hidden' }}" style="margin-top:50px;">
  <div class="logos-slide">
    @foreach($listiklan as $iklan)
      <img src="{{ asset('storage/images/' . $iklan->image_Advertisement) }}" alt="{{ $iklan->title_Advertisement ?? '-' }}">
    @endforeach
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
            <p><i class="ph ph-envelope fa-xl"></i> {{ $dataPtges->email ?? '-'}}</p>
            <p><i class="ph ph-phone fa-xl"></i> {{ is_object($dataPtges) && isset($dataPtges->phone) ? '+62' . $dataPtges->phone : '-' }}</p>
            <p><i class="ph ph-phone-plus fa-xl"></i> {{ is_object($dataPtges) && isset($dataPtges->phones) ? '+62' . $dataPtges->phones : '-' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  @section('script')
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

  @endsection


</x-layout>