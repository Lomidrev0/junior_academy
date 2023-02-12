/*parallax*/
window.addEventListener("scroll", function(){
  var parallax = document.querySelector(".header");
  var scrollPosition = window.pageYOffset;
  parallax.style.backgroundPosition = "center " + (scrollPosition * 0.4) + "px";
});

