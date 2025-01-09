$(document).on("ready turbo:load", function (e) {
  console.log(e);
  initializeThemeComponents();
});
function initializeThemeComponents() {

  $('.header-nav-item').hover(
    function() {
      $('.header-nav-item').removeClass('active');
      $(this).addClass('active');
    },
    function() {
      $(this).removeClass('active');
    }
  );
  $(".main-nav-link").click(function () {
    $(this).closest(".header-nav-item").toggleClass("toggleOpen");
  });
  $(".header-burger").click(function () {
    $(".body").toggleClass("body-active");
    $(".header").toggleClass("mobileMenu");
    $(".header-nav-list").toggleClass("list-open");
    $(".header-nav-item").toggleClass("container-lg");
    $(".header-burger").toggleClass("header-burger-open");
  });

  $(".swiper-container:not(.thumbs),.swiper-home").each(function(i, el){
    let data = $(el).data();
    if(data.thumbsSlider){
        let thumbsSwiper = $(data.thumbsSlider);
        let thumbs = new Swiper(thumbsSwiper[0], thumbsSwiper.data());
        data.thumbs = {
            swiper: thumbs
        };
    }
    let swiper = new Swiper(el, data);
})

  // const swiper = new Swiper(".swiper-home", {
  //   slidesPerView: 1,
  //   spaceBetween: 0,
  //   centeredSlides: true,
  //   navigation: { "nextEl": ".blog-btn-next", "prevEl": ".blog-btn-prev" },
  //   autoplay: {
  //     delay: 2000,
  //   },
  //   speed: 1000,
  //   loop: true,
  // });
  // const swiperblog = new Swiper(".swiper-blog", {
  //   slidesPerView: 1,
  //   spaceBetween: 0,
  //   centeredSlides: true,
  //   navigation: {
  //     nextEl: ".blog-btn-next",
  //     prevEl: ".blog-btn-prev",
  //   },
  // });

  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll < 89) {
      $(".header").removeClass("down");
    } else {
      $(".header").addClass("down");
    }
  });
  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll < 90) {
      $(".header").removeClass("fixed");
      $(".page-actions").removeClass("actions-fixed");
    } else {
      $(".page-actions").addClass("actions-fixed");
      $(".header").addClass("fixed");
    }
  });
  window.onscroll = function () {
    var winScroll =
      document.body.scrollTop || document.documentElement.scrollTop;
    var height =
      document.documentElement.scrollHeight -
      document.documentElement.clientHeight;
    var scrolled = (winScroll / height) * 100;
    document.getElementById("header-process").style.width = scrolled + "%";
  };
  if (document.querySelector(".swiper-work")) {
    new IntersectionObserver((entries, observer) => {
      if (entries[0].isIntersecting) {
        observer.disconnect();
        $(document).ready(function () {
          var mySwiper = new Swiper(".swiper-work", {
            autoHeight: true,
            autoplay: {
              delay: 5000,
              disableOnInteraction: false,
            },
            speed: 500,
            direction: "vertical",
            navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev",
            },
            pagination: {
              el: ".swiper-pagination",
              type: "progressbar",
            },
            loop: false,
            effect: "slide",
            spaceBetween: 30,
            on: {
              init: function () {
                $(
                  ".swiper-pagination-custom .swiper-pagination-switch"
                ).removeClass("active");
                $(".swiper-pagination-custom .swiper-pagination-switch")
                  .eq(0)
                  .addClass("active");
              },
              slideChangeTransitionStart: function () {
                $(
                  ".swiper-pagination-custom .swiper-pagination-switch"
                ).removeClass("active");
                $(".swiper-pagination-custom .swiper-pagination-switch")
                  .eq(mySwiper.realIndex)
                  .addClass("active");
              },
            },
          });
          $(".swiper-pagination-custom .swiper-pagination-switch").click(
            function () {
              mySwiper.slideTo($(this).index());
              $(
                ".swiper-pagination-custom .swiper-pagination-switch"
              ).removeClass("active");
              $(this).addClass("active");
            }
          );
        });
      }
    }).observe(document.querySelector(".swiper-work"));
  }
}
