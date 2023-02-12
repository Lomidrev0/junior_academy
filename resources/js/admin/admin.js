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

/*clock*/
$(document).ready(function () {
  function updateTime() {
    // Get the current date and time
    let now = new Date();

    // Format the time as hh:mm:ss
    let hours = now.getHours().toString().padStart(2, '0');
    let minutes = now.getMinutes().toString().padStart(2, '0');
    let seconds = now.getSeconds().toString().padStart(2, '0');
    let timeString = hours + ':' + minutes + ':' + seconds;

    // Format the date as dd/mm/yyyy
    let day = now.getDate().toString().padStart(2, '0');
    let month = (now.getMonth() + 1).toString().padStart(2, '0');
    let year = now.getFullYear();
    let dateString = day + '.' + month + '.' + year;

    // Update the time and date elements in the HTML
    document.getElementById('clock-time').textContent = timeString;
    document.getElementById('clock-date').textContent = dateString;
  }

// Call updateTime() initially to display the current time
  updateTime();

// Update the clock every second
  setInterval(updateTime, 1000);
});