<?php
// src/core/page_template.php

// === ВИПРАВЛЕННЯ ===
// Підключаємо ОБИДВА файли з основною логікою ПЕРЕД компонентами
require_once __DIR__ . '/i18n.php';         // Визначає функцію t() та логіку мов
require_once __DIR__ . '/asset-loader.php'; // Визначає функцію asset()

// Підключаємо шапку сайту
require_once __DIR__ . '/../components/header.php';

// Виводимо унікальний контент сторінки, який був "зловлений" буфером
// і переданий у змінній $page_content
if (isset($page_content)) {
    echo $page_content;
}

// Підключаємо футер сайту
require_once __DIR__ . '/../components/footer.php';

