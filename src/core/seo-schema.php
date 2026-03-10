<?php
/**
 * SEO Schema.org Generator
 * src/seo/schema.php
 */

$current_uri = $_SERVER['REQUEST_URI'] ?? '/';

$seoPageTitle = $GLOBALS['page_title'] ?? ($page_title ?? config('site_title'));
$seoPageDescription = $GLOBALS['page_description'] ?? ($page_description ?? config('meta_description'));
$video_url = $GLOBALS['main_video_url'] ?? ($main_video_url ?? null);

$site_url = config('site_url', 'https://iogu.gov.ua');
$home_path = config('home_path', '/');

$is_home = (rtrim($current_uri, '/') === rtrim($site_url . $home_path, '/') || $current_uri === '/index.php' || $current_uri === '/');

$social_links = array_filter([
    config('social_facebook'),
    config('social_instagram'),
    config('social_telegram'),
    config('social_viber'),
    config('social_whatsapp'),
    config('social_youtube'),
]);

$primary_contact = config('contact_phones', [])[0] ?? ['value' => null];
$primary_phone = $primary_contact['value'];
$headCity = function_exists('t') ? t('Kyiv') : 'Kyiv';

$dictionary = [
    'branches'   => function_exists('t') ? t('header.menu.branches') : 'Branches',
    'about'      => function_exists('t') ? t('header.menu.about') : 'About',
    'news'       => function_exists('t') ? t('header.menu.press_center') : 'News',
    'contacts'   => function_exists('t') ? t('header.menu.contact') : 'Contacts',
    'activity'   => function_exists('t') ? t('header.menu.activity') : 'Activity',
];

$graph = [];

// 1. Organization Schema
$graph[] = [
    "@type" => "GovernmentOrganization",
    "@id" => $site_url . "/#organization",
    "name" => config('site_title'),
    "url" => $site_url,
    "logo" => [
        "@type" => "ImageObject",
        "url" => $site_url . '/assets/img/logo.png'
    ],
    "address" => [
        "@type" => "PostalAddress",
        "streetAddress" => function_exists('t') ? t('contacts.department_location_link') : '',
        "addressLocality" => $headCity,
        "postalCode" => "03151",
        "addressCountry" => "UA"
    ],
    "contactPoint" => [
        [
            "@type" => "ContactPoint",
            "telephone" => $primary_phone,
            "contactType" => "customer service",
            "areaServed" => "UA",
            "availableLanguage" => ["Ukrainian", "English"]
        ]
    ],
    "sameAs" => array_values($social_links)
];

// 2. WebSite Schema (тільки для головної)
if ($is_home) {
    $graph[] = [
        "@type" => "WebSite",
        "@id" => $site_url . "/#website",
        "url" => $site_url,
        "name" => $seoPageTitle,
        "publisher" => [
            "@id" => $site_url . "/#organization"
        ],
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => $site_url . "/search?q={search_term_string}",
            "query-input" => "required name=search_term_string"
        ]
    ];
} else {
    // 3. BreadcrumbList Schema (для внутрішніх сторінок)
    $path = parse_url($current_uri, PHP_URL_PATH);
    $segments = array_values(array_filter(explode('/', $path)));

    if (!empty($segments) && in_array($segments[0], ['en', 'uk'])) {
        array_shift($segments);
    }

    $has_branches = in_array('branches', $segments);
    $has_news = in_array('news', $segments);

    $needs_branches_parent = false;
    $needs_news_parent = false;

    foreach ($segments as $seg) {
        if (strpos($seg, 'center_') !== false || strpos($seg, 'filia') !== false) {
            $needs_branches_parent = true;
        }
        if (preg_match('/^news\d+$/', $seg) || !empty($is_news_page)) {
            $needs_news_parent = true;
        }
    }

    if ($needs_branches_parent && !$has_branches) array_unshift($segments, 'branches');
    if ($needs_news_parent && !$has_news) array_unshift($segments, 'news');

    $breadcrumbs_items = [
        [
            "@type" => "ListItem",
            "position" => 1,
            "name" => function_exists('t') ? t('header.title') : 'Home',
            "item" => rtrim($site_url . $home_path, '/')
        ]
    ];

    $position = 2;
    $accumulated_path = '';

    foreach ($segments as $segment) {
        $accumulated_path .= '/' . $segment;

        if (isset($dictionary[$segment])) {
            $name = $dictionary[$segment];
        } elseif (strpos($segment, 'center_') === 0) {
            $name = ucwords(str_replace('center_', '', $segment)) . ' Branches';
        } elseif (preg_match('/^news(\d+)$/', $segment, $matches)) {
            $name = 'News ' . $matches[1];
        } else {
            $name = ucwords(str_replace(['-', '_'], ' ', $segment));
        }

        $breadcrumbs_items[] = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => strip_tags($name),
            "item" => $site_url . $accumulated_path
        ];
        $position++;
    }

    if (!empty($seoPageTitle) && count($breadcrumbs_items) > 1) {
        $breadcrumbs_items[count($breadcrumbs_items) - 1]['name'] = $seoPageTitle;
    }

    $graph[] = [
        "@type" => "BreadcrumbList",
        "itemListElement" => $breadcrumbs_items
    ];
}

// 4. Video Schema
if (!empty($video_url) && !empty($seoPageTitle)) {
    $video_id = '';
    if (preg_match('/(?:youtube\.com\/(?:embed\/|v\/|watch\?v=)|youtu\.be\/)([\w-]{11})/', $video_url, $matches)) {
        $video_id = $matches[1];
    }

    $graph[] = [
        "@type" => "VideoObject",
        "name" => $seoPageTitle,
        "description" => $seoPageDescription ?: $seoPageTitle,
        "uploadDate" => $date_published ?? date('c'),
        "embedUrl" => $video_url,
        "thumbnailUrl" => $video_id ? "https://i.ytimg.com/vi/{$video_id}/maxresdefault.jpg" : $site_url . '/assets/img/default-video.jpg',
        "contentUrl" => $video_id ? "https://www.youtube.com/watch?v={$video_id}" : $video_url,
        "publisher" => [
            "@id" => $site_url . "/#organization"
        ]
    ];
}

// 5. NewsArticle Schema
if (!empty($needs_news_parent) && isset($pageContent)) {
    $graph[] = [
        "@type" => "NewsArticle",
        "headline" => mb_substr($seoPageTitle, 0, 110),
        "image" => [
            isset($main_image) ? $site_url . $main_image : $site_url . '/assets/img/default-news.jpg'
        ],
        "datePublished" => $date_published ?? date('c'),
        "dateModified" => $date_modified ?? date('c'),
        "author" => [
            "@type" => "Organization",
            "@id" => $site_url . "/#organization"
        ],
        "publisher" => [
            "@id" => $site_url . "/#organization"
        ],
        "description" => $seoPageDescription
    ];
}

return [
    "@context" => "https://schema.org",
    "@graph" => $graph
];