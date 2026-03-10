import './main';

$(document).ready(function() {
    // home swiper
    if ($('.swiper').length) {
        new Swiper('.swiper', {
            lazy: true,
            direction: 'horizontal',
            loop: true,
            speed: 500,
            effect: 'slider',
            slidesPerView: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 4000,
            },
        });
    }
});