// src/js/header.js
$(function() {
    const $document = $(document);
    const $body = $('body');
    const $submenuItems = $('.nav__item--has-submenu');
    const $burger = $('.burger');
    const $mobileMenu = $('.nav__menu');
    const $overlay = $('.overlay');
    const $langWrap = $('.lang_wrap');
    const $langList = $langWrap.find('.lang-list');
    const $langIcon = $langWrap.find('.lang_icon');

    // Submenu toggle
    $submenuItems.on('click', function(e) {
        const $target = $(e.target);
        if ($target.is('.nav__link, .nav__link-svg')) {
            e.preventDefault();
        }

        const $currentItem = $(this);
        const isOpen = $currentItem.hasClass('open');

        $submenuItems.removeClass('open');
        if (!isOpen) {
            $currentItem.addClass('open');
        }
    });

    // Close dropdowns on outside click
    $document.on('click', function(e) {
        if (!$submenuItems.is(e.target) && $submenuItems.has(e.target).length === 0) {
            $submenuItems.removeClass('open');
        }
        if (!$langWrap.is(e.target) && $langWrap.has(e.target).length === 0) {
            $langList.removeClass('open');
        }
    });

    // Burger menu
    function toggleMenu() {
        $burger.toggleClass('is-active');
        $mobileMenu.toggleClass('is-open');
        $body.toggleClass('body-no-scroll');
        $overlay.toggleClass('is-active');
    }

    $burger.on('click', toggleMenu);
    $overlay.on('click', toggleMenu);

    // Mobile nav submenu
    $('.mobile-nav__item.has-submenu > .mobile-nav__link-wrapper').on('click', function(e) {
        e.preventDefault();
        const $parent = $(this).parent();
        $parent.toggleClass('is-active');
        $(this).next('.mobile-nav__submenu').slideToggle(300);
    });

    // Language switcher (ВИПРАВЛЕНО)
    $('.lang-item').on('click', function(e) {
        e.preventDefault();

        const newLang = $(this).data('lang');
        const langCodes = ['uk', 'en'];
        let currentPath = window.location.pathname;
        const pathParts = currentPath.split('/').filter(part => part);

        // Прибираємо поточний код мови зі шляху, якщо він там є
        if (pathParts.length > 0 && langCodes.includes(pathParts[0])) {
            pathParts.shift();
        }

        const basePath = pathParts.join('/');
        let newUrl = window.location.origin;

        // Додаємо префікс тільки якщо це НЕ основна мова (українська)
        if (newLang !== 'uk') {
            newUrl += '/' + newLang;
        }

        // Додаємо решту шляху, якщо ми не на головній
        if (basePath) {
            newUrl += '/' + basePath;
        } else if (newUrl === window.location.origin) {
            // Щоб головна сторінка виглядала як ...gov.ua/ а не ...gov.ua
            newUrl += '/';
        }

        window.location.href = newUrl;
    });

    // Language dropdown toggle
    $langIcon.on('click', function (e) {
        e.stopPropagation();
        $langList.toggleClass('open');
    });
});