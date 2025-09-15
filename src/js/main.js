import './header.js';
$(document).ready(function() {

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

