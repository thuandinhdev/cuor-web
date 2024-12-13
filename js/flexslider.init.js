$(document).ready(function() {
   
    $('.main-slider').flexslider({
      slideshowSpeed: 5000,
      directionNav: false, 
      controlNav: false,   
      animation: "fade",
      start: function(slider) {
        // updateSliderHeight(slider);
        updateThumbnailActiveClass(slider);
      },
      after: function(slider) {
        // updateSliderHeight(slider);
        updateThumbnailActiveClass(slider);
      }
    });
    $('.course-feature').on('click', function(e) {
      e.preventDefault(); 
      var index = $(this).data('slide'); 
      $('.course-feature').removeClass('active'); 
      $('.course-feature').eq(index).addClass('active'); 
      $('.main-slider').flexslider(index);
    });
  
    function updateThumbnailActiveClass(slider) {
      var currentSlide = slider.currentSlide;
      $('.course-feature').removeClass('active'); 
      $('.course-feature').eq(currentSlide).addClass('active'); 
    }

    // function updateSliderHeight(slider) {
    // }

    $(window).resize(function () {
        updateSliderHeight($('.main-slider'));
    });
});