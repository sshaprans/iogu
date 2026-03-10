<?php
// src/core/page_template.php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/i18n.php';
require_once __DIR__ . '/asset-loader.php';

$schema_data = require_once __DIR__ . '/seo-schema.php';

$bundle_name = $page_bundle ?? 'home';
$current_lang = $current_lang ?? 'uk';
$ga_id = config('google_analytics_id');
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($current_lang) ?>">
<head>
    <?php if (!empty($ga_id)): ?>
        <!-- [Analytics] Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga_id) ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= htmlspecialchars($ga_id) ?>');
        </script>
    <?php endif; ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <title><?= htmlspecialchars($page_title ?? config('site_title', 'Державна установа "Інститут охорони ґрунтів України"')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description ?? config('meta_description', 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»')) ?>">

    <?php if (!empty($schema_data)): ?>
        <script type="application/ld+json">
            <?= json_encode($schema_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
        </script>
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>

    <?php
    $css_path = asset($bundle_name . '.css');
    if ($css_path):
        ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css_path) ?>">
    <?php endif; ?>
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
foreach ($scripts as $script):
    $js_path = asset($script);
    if ($js_path):
        ?>
        <script src="<?= htmlspecialchars($js_path) ?>" defer></script>
    <?php
    endif;
endforeach;
?>
</body>
</html>