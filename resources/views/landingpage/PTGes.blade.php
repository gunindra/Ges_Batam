<x-layout>
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
<div class="caraousel" id="Home">
  <div class="carousel slide" data-bs-ride="carousel" id="carouselExampleCaptions">
		<div class="carousel-inner">
			<div class="carousel-item active bg-1">
				<div class="carousel-caption"  style="bottom:240px;">
					<h5 id="judulCarousel">Logistic</h5>
					<p id="parafCarousel">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, culpa.</p>
          <a class="bg-primary bg-gradient text-white" href="/Slide">Learn More</a>
				</div>
			</div>
			<div class="carousel-item bg-2">
				<div class="carousel-caption"  style="bottom:240px;">
					<h5>Truck</h5>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, culpa.</p>
          <a class="bg-primary bg-gradient text-white" href="/Slide">Learn More</a>
				</div>
			</div>
			<div class="carousel-item bg-3">
				<div class="carousel-caption"  style="bottom:240px;">
					<h5>Plane</h5>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, culpa.</p>
          <a class="bg-primary bg-gradient text-white" href="/Slide">Learn More</a>
				</div>
			</div>
		</div><button class="carousel-control-prev" data-bs-slide="prev" data-bs-target="#carouselExampleCaptions" type="button"><span aria-hidden="true" class="carousel-control-prev-icon"></span> <span class="visually-hidden">Previous</span></button> <button class="carousel-control-next" data-bs-slide="next" data-bs-target="#carouselExampleCaptions" type="button"><span aria-hidden="true" class="carousel-control-next-icon"></span> <span class="visually-hidden">Next</span></button> <!--thumbnails-->
		<div class="carousel-indicators">
			      <button aria-label="Slide 1" class="active" data-bs-slide-to="0" data-bs-target="#carouselExampleCaptions" type="button"></button>
            <button aria-label="Slide 2" data-bs-slide-to="1" data-bs-target="#carouselExampleCaptions" type="button"></button>
            <button aria-label="Slide 3" data-bs-slide-to="2" data-bs-target="#carouselExampleCaptions" type="button"></button>
		</div>
	</div>
</div>
    <!-- Main -->
    <div class="main">
    <!-- Gallery -->
    <div class="wrapperGallery">
        <h1 class="section-header">Information</h1>
        <div class="main-content">
            {{-- <div class="box">
                <img src="" alt="">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>Truck</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut, minima.</p>
                    </div>
                </div>
            </div> --}}
            @foreach($listinformation as $info)
            <div class="box">
                <img src="{{ asset('storage/images/' . $info->image_informations) }}" alt="{{ $info->judul_informations }}">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>{{ $info->judul_informations }}</h2>
                        <p>{{ $info->isi_informations }}</p>
                    </div>
                </div>
            </div>
        @endforeach
             {{-- <div class="box">
                <img src="" alt="">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>Plane</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut, minima.</p>
                    </div>
                </div>
            </div>
             <div class="box">
                <img src="" alt="">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>Box</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut, minima.</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="" alt="">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>Ship</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut, minima.</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="" alt="">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>Logistic</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut, minima.</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="" alt="">
                <div class="img-text">
                    <div class="contentGallery">
                        <h2>Marketing</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam assumenda nulla aspernatur enim ut, minima.</p>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
      <!-- About us -->
      <section id="About" class ="AboutSection">
      <div class="containerAbout">
        <div class="wrapper">
            <div class="content">
              <div class="heading">
                <h1 style="font-size:32px;">About Us</h1>
              </div>
                <h2>What they say about us</h2>
                <p id="parafAbout">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Obcaecati pariatur accusamus similique ad minima vel eaque laboriosam, enim mollitia? Porro eius magni eaque deserunt perferendis voluptatum necessitatibus iure, maxime totam?</p>
                <a href="/About" class="btn">Learn More</a>
            </div>
            <div class="image" id="imageAbout">
                <img src="/img/Whyus.jpg" style="border-radius:30px;">
            </div>
        </div>
    </div>
    </section>

      <!-- WHY US -->
    <div class="whyus">
      <div class="wrapperwhy" id="Why">
        <div class="why">
            <div class="image-sectionwhy">
                <img src="/img/Werehouse.jpg" id="imageWhy">
            </div>
            <article>
                <h3 id="judulWhy">Why Choose Us</h3>
                <p id="parafWhy">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Expedita, quod doloremque tempora dolores temporibus sed inventore tempore saepe non repellat amet quaerat, dolore, incidunt provident facilis distinctio nobis ut dicta.
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Expedita, quod doloremque tempora dolores temporibus sed inventore tempore saepe non repellat amet quaerat, dolore, incidunt provident facilis distinctio nobis ut dicta.
                </p>
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
        <div class ="services">
            <div class="cardservice">
                <div class="icon">
                  <i class="fa-solid fa-plane fa-1x"></i>
                </div>
                <h2>Plane</h2>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Corporis eum placeat nostrum, recusandae facilis repellat laudantium
                    suscipit quaerat minima unde perspiciatis assumenda aperiam rerum saepe quisquam nulla hic.
                    Cum, eos?
                </p>
                <a  href="/Services"  class="button">Read More</a>
            </div>
            <div class="cardservice">
                <div class="icon">
                  <i class="fa-solid fa-ship fa-1x"></i>
                </div>
                <h2>Ship</h2>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Corporis eum placeat nostrum, recusandae facilis repellat laudantium
                    suscipit quaerat minima unde perspiciatis assumenda aperiam rerum saepe quisquam nulla hic.
                    Cum, eos?
                </p>
                <a href="/Services1" class="button">Read More</a>
            </div>
            <div class="cardservice">
                <div class="icon">
                  <i class="fa-sharp-duotone fa-solid fa-truck-fast fa-1x"></i>
                </div>
                <h2>Truck</h2>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Corporis eum placeat nostrum, recusandae facilis repellat laudantium
                    suscipit quaerat minima unde perspiciatis assumenda aperiam rerum saepe quisquam nulla hic.
                    Cum, eos?
                </p>
                <a href="Services2" class="button">Read More</a>
            </div>
        </div>
    </div>
      <!-- iklan slide -->
    <div class="logos">
      <div class="logos-slide">
        <img src="/img/3m.svg" />
        <img src="/img/barstool-store.svg" />
        <img src="/img/budweiser.svg" />
        <img src="/img/buzzfeed.svg" />
        <img src="/img/forbes.svg" />
        <img src="/img/macys.svg" />
        <img src="/img/menshealth.svg" />
        <img src="/img/mrbeast.svg" />
      </div>
    </div>

</div>
<div class="container d-flex justify-content-center" style="margin-bottom:30px; margin-top:200px;">
<div class="card col-10">
<div class="card-body">
  <div class="row">
    <div class="col-4"  style="margin-top:10px;">
    <h3 class="textMaps">Maps</h3>
    <p>42Q2+6PH, Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau</p>
    </div>
    <div class="col-8">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.0312703091595!2d103.99927157494255!3d1.1380710622200323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98b04082b3703%3A0x182bec3b6cdd92d5!2sGES%20PT%20Global%20Expedisi%20Solusi!5e0!3m2!1sen!2sid!4v1722416419776!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</div>
</div>
</div>
</x-layout>
