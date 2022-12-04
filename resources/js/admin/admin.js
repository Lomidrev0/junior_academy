
//admin nav acivate

$(document).ready(function () {
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
  });
});

//display messages

$(window).on('load', function(){
  $('#message').css('display','block');
});
setTimeout(function() {
  $('#message').fadeOut(2000);
}, 5000);