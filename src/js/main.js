import './header';
$(document).ready(function() {

    // --- УНІКАЛЬНА ЛОГІКА (тільки для головної сторінки) ---

    // Ініціалізація Swiper
    if ($('.swiper').length) {
        new Swiper('.swiper', {
            lazy: true,
            direction: 'horizontal',
            loop: true,
            speed: 500,
            effect: 'slider',
            slidesPerView: 1,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 4000,
            },
        });
    }

    // Логіка для модального вікна
    const $modal = $("#modal");
    const $modalBtn = $(".btn-js");
    const $closeBtn = $(".modal-close");

    function preventScroll() {
        const scrollTop = $(window).scrollTop();
        $body.css({
            position: 'fixed',
            top: `-${scrollTop}px`,
        }).data('scroll-position', scrollTop);
    }

    function restoreScroll() {
        const scrollPosition = $body.data('scroll-position') || 0;
        $body.css({
            position: '',
            top: '',
        });
        $(window).scrollTop(scrollPosition);
    }

    $modalBtn.on('click', function(e) {
        e.preventDefault();
        $overlay.addClass("open");
        $modal.addClass("open modal-an");
        preventScroll();
    });

    $closeBtn.on('click', function(e) {
        e.preventDefault();
        $overlay.removeClass("open");
        $modal.removeClass("open");
        restoreScroll();
    });

    $overlay.on('click', function(e) {
        if ($(e.target).is($overlay)) {
            $overlay.removeClass("open");
            $modal.removeClass("open");
            restoreScroll();
        }
    });

    // Логіка для фіксованих кнопок
    $('.wrapper-contact-fixed').on('click', function() {
        $('.contact-fixed').toggleClass('active');
    });

});

