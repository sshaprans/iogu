<?php
/**
 * src/core/media-proxy.php
 * Проксі для безпечного завантаження медіа-файлів з віддаленого сховища.
 */

require_once __DIR__ . '/config.php';

$requestPath = $_GET['path'] ?? '';

$requestPath = str_replace(['../', '..\\'], '', $requestPath);

if (empty($requestPath)) {
    http_response_code(404);
    exit('File not specified');
}

$remoteSource = config('media.remote_source');

if (empty($remoteSource)) {
    http_response_code(500);
    exit('Media configuration missing');
}

// Формуємо повний URL
// Наприклад: https://media.iogu.gov.ua/img/logo.svg
$remoteUrl = rtrim($remoteSource, '/') . '/' . ltrim($requestPath, '/');

// Список дозволених розширень (безпека)
$allowedExtensions = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'svg'  => 'image/svg+xml',
    'webp' => 'image/webp',
    'pdf'  => 'application/pdf',
    'mp4'  => 'video/mp4'
];

$extension = strtolower(pathinfo($requestPath, PATHINFO_EXTENSION));

if (!array_key_exists($extension, $allowedExtensions)) {
    http_response_code(403);
    exit('File type not allowed');
}

// Ініціалізуємо cURL для завантаження
$ch = curl_init($remoteUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // false = відразу виводити картинку в браузер
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'IOGU-Local-Proxy/1.0');

// Вимикаємо перевірку SSL для локальної розробки, якщо виникають проблеми з сертифікатами
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Спочатку перевіримо, чи існує файл на віддаленому сервері (HEAD запит)
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(404);
    exit('File not found on remote server: ' . htmlspecialchars($requestPath));
}

// Якщо файл є, віддаємо правильні заголовки
header('Content-Type: ' . $allowedExtensions[$extension]);
// Кешування на 24 години
header('Cache-Control: public, max-age=86400');

// Завантажуємо та віддаємо контент файлу
$ch = curl_init($remoteUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);
exit;