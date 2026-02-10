<?php
require_once __DIR__ . '/core/i18n.php';

$page_bundle = '404';

$page_title = 'Сторінку не знайдено | 404';
$page_description = 'Вибачте, але сторінка, яку ви шукаєте, не існує, була видалена або переміщена за іншою адресою.';

ob_start();


?>
<main id="main">
    <div class="container">
        <h2 class="section-title">404</h2>
        <h3 class="main__information__list__link">Сторінку не знайдено</h3>
        <h3 class="main__information__list__link">
            <?= $page_description?>
        </h3>
        <a href="/" class="btn download-doc-btn">Повернутися на головну</a>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/core/page_template.php';
?>
