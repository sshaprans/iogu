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
const $mobileMenu = $('.mobile-menu');
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

// Замініть цим кодом ваш поточний обробник кліків
$('.lang-item').on('click', function(e) {
    e.preventDefault(); // Зупиняємо стандартний перехід за посиланням

    // 1. Отримуємо мову, на яку клікнули (наприклад, 'en')
    const newLang = $(this).data('lang');

    // 2. Визначаємо список можливих мов на сайті
    const langCodes = ['uk', 'en']; // Додайте сюди всі ваші мови

    // 3. Отримуємо поточний шлях сторінки (наприклад, '/leadership' або '/uk/leadership')
    let currentPath = window.location.pathname;

    // 4. Перевіряємо, чи починається шлях з коду мови, і видаляємо його
    const pathParts = currentPath.split('/').filter(part => part); // Розбиваємо шлях на частини
    if (pathParts.length > 0 && langCodes.includes(pathParts[0])) {
        pathParts.shift(); // Видаляємо перший елемент, якщо це код мови
    }

    // 5. Збираємо чистий шлях назад
    const basePath = pathParts.join('/');

    // 6. Формуємо новий URL
    // Для головної сторінки basePath буде пустим
    let newUrl = `${window.location.origin}/${newLang}`;
    if (basePath) {
        newUrl += `/${basePath}`;
    }

    // 7. Перенаправляємо на нову адресу
    window.location.href = newUrl;
});

$('.vision').on('click', function() {
    $('body').toggleClass('poor-eyesight');
});