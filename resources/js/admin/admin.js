//admin nav acivate
function transform(){
  if ($(window).width() >= 1000){
    if ( $('#sidebar').hasClass('active')) {
      $('.content').css('margin-left', '0px').css("transition", "0.3s");
    }
    else {
      $('.content').css('margin-left', '249.5px').css("transition", "0.3s");
    }
  }
  else {
    $('.content').css('margin-left', '0px').css("transition", "0.3s");
  }
}
$(document).ready(function () {
  if ($(window).width() < 1000){
    $('#sidebar').toggleClass('active');
  }
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    transform();
  });
});
addEventListener("resize", (event) => {
  transform();
});


//display messages
$(window).on('load', function(){
  $('#message').css('display','block');
});
setTimeout(function() {
  $('#message').fadeOut(2000);
}, 5000);