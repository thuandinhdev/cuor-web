$(document).ready(function() {
   
    $('.main-slider').flexslider({
      slideshowSpeed: 5000,
      directionNav: false, 
      controlNav: false,   
      animation: "fade",
      start: function(slider) {
        updateSliderHeight(slider);
        updateThumbnailActiveClass(slider);
      },
      after: function(slider) {
        updateSliderHeight(slider);
        updateThumbnailActiveClass(slider);
      }
    });
    // $('.thumbnail-link').on('click', function(e) {
    $('.course-feature').on('click', function(e) {
      e.preventDefault(); 
      var index = $(this).data('slide'); 
      $('.main-slider').flexslider(index);
    });
  
    function updateThumbnailActiveClass(slider) {
      var currentSlide = slider.currentSlide;
      // $('.thumbnail-link').removeClass('active'); 
      // $('.thumbnail-link').eq(currentSlide).addClass('active'); 
      $('.course-feature').removeClass('active'); 
      $('.course-feature').eq(currentSlide).addClass('active'); 
    }

    // Hàm cập nhật chiều cao slider
    function updateSliderHeight(slider) {
        // const viewportHeight = $(window).height();
        // slider.find('.slides').css('height', viewportHeight + 'px');
        // slider.find('li').css('height', viewportHeight + 'px');
    }

    // Lắng nghe sự kiện resize (nếu màn hình thay đổi)
    $(window).resize(function () {
        updateSliderHeight($('.main-slider'));
    });
});