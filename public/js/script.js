// pop up
document.addEventListener('DOMContentLoaded', function() {
    const dialog = document.querySelector('#welcome-dialog');
    
    dialog.showModal(); // Show the dialog every time the page is loaded
    
    dialog.querySelector('.about-btn').addEventListener('click', function() {
        window.location.href = '/About'; 
    });
  
    dialog.querySelector('.close-btn').addEventListener('click', function() {
        dialog.close();
    });
  });
// Scroll navbar carousel
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const carousel = document.querySelector('.carousel');
    const navbarToggler = document.querySelector('.navbar-toggler');

    function handleScroll() {
        if (carousel && window.scrollY > (carousel.offsetHeight - navbar.offsetHeight)) {
            navbar.classList.add('scrolled');
            navbar.classList.remove('transparent');
        } else {
            navbar.classList.remove('scrolled');
            if (!navbar.classList.contains('open')) {
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

    handleScroll();
    window.addEventListener('scroll', handleScroll);
    navbarToggler.addEventListener('click', handleTogglerClick);
});
// Slide
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
// const Home = document.getElementById("Home");

// Home.addEventListener("click", function (){
//   window.location.href= "PTGes.blade.php";
// });