import './main';

/**
 * Активує плагін слайдів, використовуючи jQuery.
 * @param {number} [activeSlide=0] - Індекс слайду, який буде активним спочатку.
 */
function slidesPlugin(activeSlide = 0) {
    const $slides = $('.slide');

    $slides.eq(activeSlide).addClass('active');

    $slides.on('click', function() {
        $(this).addClass('active').siblings().removeClass('active');
    });
}

$(document).ready(function() {
    slidesPlugin(1);
});

