import '../scss/main.scss';

import $ from 'jquery';

$(document).ready(function() {
    // --- Логіка для випадаючого меню ---
    $('.nav__item--has-submenu > .nav__link, .nav__item--has-submenu > .nav__link-svg').on('click', function(e) {
        e.preventDefault();
        const parentItem = $(this).parent('.nav__item');
        const submenu = parentItem.find('.nav__submenu');

        // Закриваємо інші відкриті меню
        $('.nav__item--has-submenu').not(parentItem).removeClass('open');

        // Перемикаємо клас 'open' для поточного меню
        parentItem.toggleClass('open');
    });

    // Закриваємо меню, якщо клікнули поза ним
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.nav__item--has-submenu').length) {
            $('.nav__item--has-submenu').removeClass('open');
        }
    });


    // --- ДОДАНО: Логіка перемикання мови ---
    $('.lang-item').on('click', function() {
        // Отримуємо код мови з data-атрибуту (uk або en)
        const lang = $(this).data('lang');

        if (lang) {
            // Створюємо об'єкт для роботи з параметрами URL
            const params = new URLSearchParams(window.location.search);
            // Встановлюємо новий параметр 'lang'
            params.set('lang', lang);
            // Перезавантажуємо сторінку з новим URL (напр. /pages/index.php?lang=en)
            window.location.search = params.toString();
        }
    });

    // --- Ініціалізація Swiper JS ---
    // Перевіряємо, чи існує елемент слайдера на сторінці
    if (document.querySelector('.mySwiper')) {
        const swiper = new Swiper(".mySwiper", {
            effect: "cards",
            grabCursor: true,
        });
    }


});

