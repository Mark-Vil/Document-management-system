$('.toggle-abstract').on('click', function(event) {
  event.preventDefault();
  var $abstract = $(this).siblings('.abstract');
  $abstract.toggleClass('expanded');
  
  if ($abstract.hasClass('expanded')) {
    $(this).text('Read less...');
  } else {
    $(this).text('Read more...');
  }
});