
//admin nav acivate

$(document).ready(function () {
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    if ($(window).width() >= 1000){
      if ( $('#sidebar').hasClass('active')) {
        $('.content').css('margin-left', '0px').css("transition", "0.3s");
      }
      else {
        $('.content').css('margin-left', '250px').css("transition", "0.3s");
      }
    }
  });
});

//dynamic height of didebar
$(document).ready(function () {
  $('#sidebar').css('height',$(document).height()- 53 +'px')
});

//display messages

$(window).on('load', function(){
  $('#message').css('display','block');
});
setTimeout(function() {
  $('#message').fadeOut(2000);
}, 5000);