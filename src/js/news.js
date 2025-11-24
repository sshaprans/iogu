import './main';
$(document).ready(function() {
    var swiper = new Swiper(".photo_gallery-swiper", {
        loop: true,
        spaceBetween: 5,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
            680: {
                slidesPerView: 13,
            }
        }
    });

    var swiper2 = new Swiper(".photo_gallery-swiper2", {
        loop: true,
        spaceBetween: 10,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
});

