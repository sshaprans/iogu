<?php
// src/robots.php

// Вказуємо тип контенту
header('Content-Type: text/plain; charset=UTF-8');

// Віддаємо Last-Modified (поточна дата, бо правила можуть змінюватися)
$lastMod = gmdate('D, d M Y H:i:s') . ' GMT';
header("Last-Modified: $lastMod");

// кешування наприклад, на добу)
// header("Cache-Control: public, max-age=86400");
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

# Дозволяємо індексувати зображення та PDF
Allow: /img/
Allow: /uploads/
Allow: /assets/

# Шлях до карти сайту
Sitemap: https://www.iogu.gov.ua/sitemap.xml