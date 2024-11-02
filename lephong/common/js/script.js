const teaserCarousel = () => {
  let teaser_slide = $(".secondary-carousel");
  teaser_slide.slick({
    autoplaySpeed: 5000,
    speed: 500,
    slidesToShow: 3,
    centerPadding: "0",
    focusOnSelect: true,
    infinite: false,
    responsive: [
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          arrows: false,
        },
      },
    ],
  });
};

$(function () {
  teaserCarousel();
});
