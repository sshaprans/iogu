<?php
/**
 * Dynamic Sitemap Generator
 */

// Підключаємо БД
if (file_exists(__DIR__ . '/core/Database.php')) {
    require_once __DIR__ . '/core/Database.php';
}

$siteUrl = 'https://www.iogu.gov.ua';

// --- SEO: Last-Modified для карти сайту ---
$lastModDate = null;
try {
    if (class_exists('Database')) {
        $db = Database::getInstance()->getConnection();
        // Беремо дату найсвіжішої новини
        $stmt = $db->query("SELECT MAX(date_posted) FROM news WHERE is_published = 1");
        $lastModDate = $stmt->fetchColumn();
    }
} catch (Exception $e) {}

// Якщо дати немає, беремо поточний час
$timestamp = $lastModDate ? strtotime($lastModDate) : time();
$lastModHeader = gmdate('D, d M Y H:i:s', $timestamp) . ' GMT';

// Заголовки
header("Content-Type: application/xml; charset=utf-8");
header("Last-Modified: $lastModHeader");
// Прибираємо сувору заборону кешування, бо ми дали Last-Modified
// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

// Вимикаємо кешування
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc><?= $siteUrl ?>/</loc>
        <lastmod><?= date('Y-m-d', $timestamp) ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php
    function printUrl($loc, $priority, $freq, $lastmod = null) {
        $lastmod = $lastmod ?? date('Y-m-d');
        echo "\t<url>\n";
        echo "\t\t<loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "\t\t<lastmod>" . $lastmod . "</lastmod>\n";
        echo "\t\t<changefreq>" . $freq . "</changefreq>\n";
        echo "\t\t<priority>" . $priority . "</priority>\n";
        echo "\t</url>\n";
    }

    $main_pages = ['/news', '/contacts', '/branches', '/activity', '/leadership'];
    foreach ($main_pages as $page) printUrl($siteUrl . $page, '0.9', 'weekly');

    $current_year = date('Y');
    for ($year = 2023; $year <= $current_year; $year++) {
        printUrl($siteUrl . "/news/news" . $year, ($year == $current_year ? '0.8' : '0.5'), 'monthly');
    }

    $secondary_pages = ['/history', '/about', '/anticorruption', '/photo_gallery', '/npa', '/ground_partner', '/land_survey', '/monitoring_objects', '/edition', '/international_activity', '/government_procurement', '/soil_sampling'];
    foreach ($secondary_pages as $page) printUrl($siteUrl . $page, '0.7', 'monthly');

    $branches_list = ['center_dnipro', 'center_donetsk', 'center_zt', 'center_zk', 'center_zp', 'center_si_if', 'center_kr', 'center_lg', 'center_mk', 'center_od', 'center_pl', 'center_ri', 'center_cy', 'center_tr', 'center_kh', 'center_hr', 'center_hm', 'center_ch', 'center_che', 'center_cher', 'center_ar'];
    foreach ($branches_list as $branch) printUrl($siteUrl . '/center/' . $branch, '0.8', 'weekly');

    // Новини (останні 50)
    try {
        if (isset($db)) {
            $stmt = $db->query("SELECT slug, date_posted FROM news WHERE is_published = 1 ORDER BY date_posted DESC LIMIT 50");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                printUrl($siteUrl . '/news/' . $row['slug'], '0.8', 'weekly', date('Y-m-d', strtotime($row['date_posted'])));
            }
        }
    } catch (Exception $e) {}
    ?>

</urlset>