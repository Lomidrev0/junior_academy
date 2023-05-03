/*parallax*/
import {i18n, toast} from "../app";

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
      try {
        doParallax();
      }
      catch (error){}
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

/*store watch dog*/
export function addWatchDog(event) {
  event.preventDefault();
  const myForm = document.getElementById('watch-dog-form');

  if (!myForm.checkValidity()) {
    myForm.reportValidity(); // show validation errors
    return;
  }
  const formData = new FormData(myForm);
  axios
    .post('/add_watch_dog', formData)
    .then(response => {
      response.data ? toast.success(i18n('The email address has been saved'),i18n('After re-starting the registration, we will contact you at the entered email address')) :
      toast.warning(i18n('We already register the entered address in the system'),null);
      myForm.reset();
    })
    .catch(error => {
      // Handle the error response
    });
}
window.addWatchDog = addWatchDog;