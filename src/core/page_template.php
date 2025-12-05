<?php
// src/core/page_template.php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/i18n.php';
require_once __DIR__ . '/asset-loader.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/seo-schema.php';
$bundle_name = $page_bundle ?? 'home'; // 'home' як запасний варіант
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?? 'uk' ?>">
<head>
    <!-- [Analytics] Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8VFCKEQQ19"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-8VFCKEQQ19');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <title><?= $page_title ?? 'Державна установа "Інститут охорони ґрунтів України"' ?></title>
    <meta name="description" content="<?= $page_description ?? 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»' ?>">

    <?php if (isset($schema_data)): ?>
        <script type="application/ld+json">
    <?= json_encode($schema_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
    </script>
    <?php endif; ?>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
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

