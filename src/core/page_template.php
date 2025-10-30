<?php
// src/core/page_template.php

require_once __DIR__ . '/asset-loader.php';
require_once __DIR__ . '/i18n.php';

$bundle_name = $page_bundle ?? 'home'; // 'home' як запасний варіант
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?? 'uk' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?? 'Державна установа "Інститут охорони ґрунтів України"' ?></title>
    <meta name="description" content="<?= $page_description ?? 'Офіційний сайт.' ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <!-- Динамічно підключаємо потрібний CSS-файл для поточної сторінки -->
    <link rel="stylesheet" href="<?= asset($bundle_name . '.css') ?>">
</head>
<body>

<?php
require_once __DIR__ . '/../components/header.php';

if (isset($page_content)) {
    echo $page_content;
}

require_once __DIR__ . '/../components/footer.php';
?>

<?php
$scripts = ['header.js', $bundle_name . '.js'];
foreach ($scripts as $script): ?>
    <script src="<?= asset($script) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>

