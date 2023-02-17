/*parallax*/
var lastScrollPosition = 0;
var ticking = false;

function doParallax() {
  var parallax = document.querySelector(".header");
  var scrollPosition = window.pageYOffset;
  parallax.style.backgroundPosition = "center " + (-(scrollPosition * 0.4)-58.5) + "px";
  lastScrollPosition = scrollPosition;
  ticking = false;
}

window.addEventListener("scroll", function() {
  lastScrollPosition = window.pageYOffset;
  if (!ticking) {
    window.requestAnimationFrame(function() {
      doParallax();
    });
    ticking = true;
  }
});

/*navigation*/
$(document).ready(function(){
  $(".mobile-nav i").click(function(){
    $(".site-nav-menu").toggleClass("mobile-menu");
  });
});