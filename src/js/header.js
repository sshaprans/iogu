const $submenuItems = $('.nav__item--has-submenu');
$submenuItems.on('click', function(e) {
    if ($(e.target).is('.nav__link') || $(e.target).is('.nav__link-svg')) {
        e.preventDefault();
    }
    const currentItem = $(this);
    const isOpen = currentItem.hasClass('open');
    $submenuItems.removeClass('open');
    if (!isOpen) {
        currentItem.addClass('open');
    }
});
$(document).on('click', function(e) {
    if (!$submenuItems.is(e.target) && $submenuItems.has(e.target).length === 0) {
        $submenuItems.removeClass('open');
    }
});

const $burger = $('.burger');
const $mobileMenu = $('.nav__menu');
const $body = $('body');
const $overlay = $('.overlay');
function toggleMenu() {
    $burger.toggleClass('is-active');
    $mobileMenu.toggleClass('is-open');
    $body.toggleClass('body-no-scroll');
    $overlay.toggleClass('is-active');
}
$burger.on('click', toggleMenu);
$overlay.on('click', toggleMenu);
$('.mobile-nav__item.has-submenu > .mobile-nav__link-wrapper').on('click', function(e) {
    e.preventDefault();
    $(this).parent().toggleClass('is-active');
    $(this).next('.mobile-nav__submenu').slideToggle(300);
});

$('.lang-item').on('click', function(e) {
    e.preventDefault();

    const newLang = $(this).data('lang');
    const langCodes = ['uk', 'en'];
    let currentPath = window.location.pathname;
    const pathParts = currentPath.split('/').filter(part => part);

    if (pathParts.length > 0 && langCodes.includes(pathParts[0])) {
        pathParts.shift();
    }

    const basePath = pathParts.join('/');
    let newUrl = `${window.location.origin}/${newLang}`;

    if (basePath) {
        newUrl += `/${basePath}`;
    }

    window.location.href = newUrl;
});

const $langWrap = $('.lang_wrap');
const $langList = $langWrap.find('.lang-list');
const $langIcon = $langWrap.find('.lang_icon');

$langIcon.on('click', function (e) {
    e.stopPropagation();
    $langList.toggleClass('open');
});

$(document).on('click', function (e) {
    if (!$langWrap.is(e.target) && $langWrap.has(e.target).length === 0) {
        $langList.removeClass('open');
    }
});


// $('.vision').on('click', function() {
//     $('body').toggleClass('poor-eyesight');
// });