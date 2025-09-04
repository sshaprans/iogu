<?php

require_once __DIR__ . '/../core/i18n.php';

$page_title = 'Головна | Інститут охорони ґрунтів України';
$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України». Новини, діяльність, контакти.';
$page_scripts = [];

ob_start();
?>

<main id="main">
    <div class="container">
        <!-- Тепер ви можете безпечно використовувати функцію t() тут -->
        <h1><?= t('home.title') // Приклад використання ?></h1>
        <p><?= t('home.subtitle') // Приклад використання ?></p>
<!--        <picture class="protection__director-picture">-->
<!--            <source srcset="/img/about/for-test1.webp 1x,-->
<!--                            /img/about/for-test.webp 2x"-->
<!--                    type="image/webp">-->
<!--            <source srcset="/img/about/for-test1.png 1x,-->
<!--                            /img/about/for-test.png 2x"-->
<!--                    type="image/png">-->
<!--            <img  src="/img/about/for-test1.png" alt="" width="600" height="403"-->
<!--                 loading="lazy" decoding="async">-->
<!--        </picture>-->
        <?php require __DIR__ . '/../components/hero-slider.php'; ?>
    </div>
</main>

<?php
// --- Кінець унікального контенту ---
$page_content = ob_get_clean();

// Підключаємо головний шаблон
require_once __DIR__ . '/../core/page_template.php';
?>
