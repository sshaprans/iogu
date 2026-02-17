<?php
// src/robots.php

// Встановлюємо правильний MIME-тип і кодування
header('Content-Type: text/plain; charset=UTF-8');

// Встановлюємо час останньої модифікації (поточний час)
$lastModifiedTime = time();
$lastModifiedStr = gmdate('D, d M Y H:i:s', $lastModifiedTime) . ' GMT';

// Обробка заголовка If-Modified-Since (для правильного кешування 304 Not Modified)
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModifiedTime) {
    header('HTTP/1.1 304 Not Modified');
    exit;
}

header("Last-Modified: $lastModifiedStr");
// Додаємо Cache-Control, щоб змусити клієнта перевіряти актуальність
header("Cache-Control: public, max-age=3600, must-revalidate");

?>
User-agent: *
Disallow: /admin/
Disallow: /core/
Disallow: /includes/
Disallow: /api/
Disallow: /dist/
Disallow: /src/
Disallow: /node_modules/
Disallow: *.json
Disallow: *.lock
Disallow: *.yml
Disallow: /docker-compose.yml
Disallow: /Dockerfile

Allow: /img/
Allow: /uploads/
Allow: /assets/

Sitemap: https://www.iogu.gov.ua/sitemap.xml