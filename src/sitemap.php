<?php
/**
 * Dynamic Sitemap Generator
 * Генерує карту сайту у форматі XML для Google Search Console.
 */


$site_url = 'https://test.iogu.gov.ua';

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc><?= $site_url ?>/</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php
    // ---  0.8) ---

    $main_pages = [
        '/contacts',
        '/leadership',
        '/branches',
        '/activity',
    ];

    foreach ($main_pages as $page) {
        echo "\t<url>\n";
        echo "\t\t<loc>" . $site_url . $page . "</loc>\n";
        echo "\t\t<lastmod>" . date('Y-m-d') . "</lastmod>\n";
        echo "\t\t<changefreq>weekly</changefreq>\n";
        echo "\t\t<priority>0.8</priority>\n";
        echo "\t</url>\n";
    }

    $secondary_pages = [
        '/news',
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
        '/government_procurement'
    ];

    foreach ($secondary_pages as $page) {
        echo "\t<url>\n";
        echo "\t\t<loc>" . $site_url . $page . "</loc>\n";
        echo "\t\t<changefreq>monthly</changefreq>\n";
        echo "\t\t<priority>0.6</priority>\n";
        echo "\t</url>\n";
    }
    ?>

</urlset>