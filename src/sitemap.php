<?php
/**
 * Dynamic Sitemap Generator
 */

// Підключаємо БД для отримання реальних дат оновлення (опціонально)
// Якщо core/db.php знаходиться в тій же папці dist/core після білда
if (file_exists(__DIR__ . '/core/db.php')) {
    require_once __DIR__ . '/core/db.php';
}

// Встановлюємо домен вручну, щоб уникнути помилки виклику config()
// Якщо у вас є файл конфігурації, підключіть його, але цей варіант надійніший для окремого скрипта
$siteUrl = 'https://www.iogu.gov.ua';

header("Content-Type: application/xml; charset=utf-8");

// Вимикаємо кешування
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <!-- Головна сторінка -->
    <url>
        <loc><?= $siteUrl ?>/</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php
    // Допоміжна функція всередині файлу
    function printUrl($loc, $priority, $freq, $lastmod = null) {
        $lastmod = $lastmod ?? date('Y-m-d');
        echo "\t<url>\n";
        echo "\t\t<loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "\t\t<lastmod>" . $lastmod . "</lastmod>\n";
        echo "\t\t<changefreq>" . $freq . "</changefreq>\n";
        echo "\t\t<priority>" . $priority . "</priority>\n";
        echo "\t</url>\n";
    }

    // --- 1. Основні розділи (Високий пріоритет) ---
    $main_pages = [
            '/news',
            '/contacts',
            '/branches',
            '/activity',
            '/leadership',
    ];

    foreach ($main_pages as $page) {
        printUrl($siteUrl . $page, '0.9', 'weekly');
    }

    // --- 2. Новини (Архіви) ---
    // Якщо у вас є новини за поточний рік, вони важливі
    $current_year = date('Y');
    $start_year = 2023;

    for ($year = $start_year; $year <= $current_year; $year++) {
        // Пріоритет вищий для поточного року
        $prio = ($year == $current_year) ? '0.8' : '0.5';
        $freq = ($year == $current_year) ? 'daily' : 'monthly';

        $news_url = $siteUrl . "/news/news" . $year; // Перевірте, чи існують такі URL фізично
        // Якщо це просто фільтр новин, можливо краще вказувати /news
        printUrl($news_url, $prio, $freq);
    }

    // --- 3. Статичні/Другорядні сторінки ---
    $secondary_pages = [
            '/history',
            '/about',
            '/anticorruption',
            '/photo_gallery',
            '/npa',
            '/ground_partner',
            '/land_survey',
            '/monitoring_objects',
            '/edition',
            '/international_activity',
            '/government_procurement',
            '/soil_sampling',
    ];

    foreach ($secondary_pages as $page) {
        printUrl($siteUrl . $page, '0.7', 'monthly');
    }

    // --- 4. Філії ---
    $branches_list = [
            'center_dnipro', 'center_donetsk', 'center_zt', 'center_zk', 'center_zp',
            'center_si_if', 'center_kr', 'center_lg', 'center_mk', 'center_od',
            'center_pl', 'center_ri', 'center_cy', 'center_tr', 'center_kh',
            'center_hr', 'center_hm', 'center_ch', 'center_che', 'center_cher',
            'center_ar'
    ];

    foreach ($branches_list as $branch) {
        $branch_url = $siteUrl . '/center/' . $branch; // Виправлено шлях згідно .htaccess (/center/...)
        printUrl($branch_url, '0.8', 'weekly');
    }

    // --- 5. Окремі новини (Опціонально) ---
    // Можна витягнути останні 50 новин з БД
    try {
        if (class_exists('Database')) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT slug, date_posted FROM news WHERE is_published = 1 ORDER BY date_posted DESC LIMIT 50");
            $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($news as $item) {
                $link = $siteUrl . '/news/' . $item['slug'];
                // Форматуємо дату з БД
                $date = date('Y-m-d', strtotime($item['date_posted']));
                printUrl($link, '0.8', 'weekly', $date);
            }
        }
    } catch (Exception $e) {
        // Ігноруємо помилки БД, щоб не зламати весь sitemap
    }
    ?>

</urlset>