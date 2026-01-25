<?php
/**
 * Dynamic Sitemap Generator
 */

;

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc><?=config('site_url')?>/</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php

    $main_pages = [
            '/contacts',
            '/leadership',
            '/branches',
            '/activity',
    ];

    foreach ($main_pages as $page) {
        printUrl(config('site_url') . $page, '0.8', 'weekly');
    }

    $current_year = date('Y');
    $start_year = 2023;

    for ($year = $start_year; $year <= $current_year; $year++) {
        $news_url =config('site_url') . "/news/news" . $year;
        printUrl($news_url, '0.8', 'daily');
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
            '/government_procurement',
            '/soil_sampling',
    ];

    foreach ($secondary_pages as $page) {
        printUrl(config('site_url') . $page, '0.8', 'monthly');
    }

    $branches_list = [
            'center_dnipro',
            'center_donetsk',
            'center_zt',
            'center_zk',
            'center_zp',
            'center_si_if',
            'center_kr',
            'center_lg',
            'center_mk',
            'center_od',
            'center_pl',
            'center_ri',
            'center_cy',
            'center_tr',
            'center_kh',
            'center_hr',
            'center_hm',
            'center_ch',
            'center_che',
            'center_cher',
            'center_ar'
    ];

    foreach ($branches_list as $branch) {
        $branch_url = config('site_url') . '/branches/' . $branch;
        printUrl($branch_url, '0.6', 'weekly');
    }


    /**
     * Допоміжна функція для виводу блоку URL
     */
    function printUrl($loc, $priority, $freq) {
        echo "\t<url>\n";
        echo "\t\t<loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "\t\t<lastmod>" . date('Y-m-d') . "</lastmod>\n";
        echo "\t\t<changefreq>" . $freq . "</changefreq>\n";
        echo "\t\t<priority>" . $priority . "</priority>\n";
        echo "\t</url>\n";
    }
    ?>

</urlset>