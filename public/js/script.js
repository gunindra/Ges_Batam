document.addEventListener('DOMContentLoaded', function() {
    const welcomeDialog = document.getElementById('welcome-dialog');
    const goButton = document.querySelector('.btn-Go');
    const closeButton = document.getElementById('close-popup');

    if (!sessionStorage.getItem('popupDisplayed')) {
        welcomeDialog.showModal();
    }

    goButton.addEventListener('click', function(event) {
        sessionStorage.setItem('popupDisplayed', 'true');
        welcomeDialog.close();
    });

    closeButton.addEventListener('click', function() {
        sessionStorage.setItem('popupDisplayed', 'true');
        welcomeDialog.close();
    });

    window.addEventListener('popstate', function() {
        if (sessionStorage.getItem('popupDisplayed') === 'true' && welcomeDialog.open) {
            welcomeDialog.close();
        }
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || sessionStorage.getItem('popupDisplayed') === 'true') {
            if (welcomeDialog.open) {
                welcomeDialog.close();
            }
        }
    });
});
var copy = document.querySelector(".logos-slide").cloneNode(true);
document.querySelector(".logos").appendChild(copy);

// warna dari navbar about us,Why us,Services,Slide
document.addEventListener('DOMContentLoaded', function() {
  const navbar = document.querySelector('.navbar');


  if (document.body.classList.contains('about-page')) {
      navbar.classList.add('nav-page-navbar');
  } else {
      navbar.classList.remove('nav-page-navbar');
  }
});
// warna dari active
document.addEventListener('DOMContentLoaded', function() {
    const currentLocation = window.location.href;
    const menuItems = document.querySelectorAll('.navbar-nav .nav-item .nav-link');

    menuItems.forEach(item => {
        if (currentLocation.includes(item.getAttribute('href'))) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});
  document.addEventListener("scroll", function() {
    const navbar = document.querySelector('.navbar');
    const carousel = document.querySelector('#carouselExample');
    const aboutSection = document.querySelector('#About');

    const carouselHeight = carousel.offsetHeight;
    const aboutPosition = aboutSection.offsetTop;
    const scrollPosition = window.scrollY;

    if (scrollPosition < carouselHeight) {
        navbar.classList.remove('scrolled');
        navbar.classList.add('transparent');
    } else if (scrollPosition >= carouselHeight && scrollPosition < aboutPosition) {
        navbar.classList.remove('transparent');
        navbar.classList.add('scrolled');
    } else if (scrollPosition >= aboutPosition) {
        navbar.classList.remove('transparent');
        navbar.classList.add('scrolled');
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarLogo = document.querySelector('.navbar-logo');
    const carousel = document.querySelector('.carousel');
    const isHomePage = window.location.pathname === '/';

    function handleScroll() {
        if (carousel) {
            if (window.scrollY > (carousel.offsetHeight - navbar.offsetHeight)) {
                // If scrolled past carousel, make navbar colored
                navbar.classList.add('scrolled');
                navbar.classList.remove('transparent');
            } else {
                // If still on carousel, keep navbar transparent
                navbar.classList.remove('scrolled');
                navbar.classList.add('transparent');
            }
        }
    }

    function handleTogglerClick() {
        navbar.classList.toggle('open');
        if (navbar.classList.contains('open')) {
            navbar.classList.remove('transparent');
        } else {
            if (carousel && window.scrollY <= (carousel.offsetHeight - navbar.offsetHeight)) {
                navbar.classList.add('transparent');
            }
        }
    }

    // Set homepage class and logo visibility
    if (isHomePage) {
        navbar.classList.add('homepage');
    } else {
        navbar.classList.remove('homepage');
        navbar.classList.add('scrolled'); // Ensure navbar is styled correctly on other pages
        navbar.classList.remove('transparent');
    }

    handleScroll(); // Initial check
    window.addEventListener('scroll', handleScroll);
    navbarToggler.addEventListener('click', handleTogglerClick);
});
function scrollToSection(SectionId){
    const element = document.getElementById(SectionId);
    if(element){
        element.scrollIntoView({ behavior: 'smooth'});
    }
}
document.getElementById('textarea').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        this.value += '\n\n'; // Tambahkan dua baris baru untuk jarak
    }
});

