<?php
/**
 * SEO Schema.org Generator
 * src/seo/schema.php
 */

$current_uri = $_SERVER['REQUEST_URI'];

$seoPageTitle = $GLOBALS['page_title'] ?? ($page_title ?? null);
$seoPageDescription = $GLOBALS['page_description'] ?? ($page_description ?? null);

// !!! ВАЖЛИВО: Змінна для відео.
// Якщо на сторінці є відео, визначте $main_video_url у контролері перед підключенням цього файлу.
$video_url = $GLOBALS['main_video_url'] ?? ($main_video_url ?? null);

// Отримання базових налаштувань сайту
$home_path = config('home_path', '/');
$site_url = config('site_url', 'http://localhost');

/** @var bool $is_home */
$is_home = (rtrim($current_uri, '/') == rtrim($site_url . $home_path, '/') || $current_uri == '/index.php');


$social_links = [
    config('social_facebook'),
    config('social_instagram'),
    config('social_telegram'),
    config('social_viber'),
    config('social_whatsapp'),
    config('social_youtube'),
];

$social_links = array_filter($social_links);


$primary_contact = config('contact_phones', [])[0] ?? ['value' => null, 'label' => ''];
$primary_phone = $primary_contact['value'];
$headCity = t('Kyiv');

$dictionary = [
    'branches'   => t('header.menu.branches'),
    'about'      => t('header.menu.about'),
    'news'       => t('header.menu.press_center'),
    'contacts'   => t('header.menu.contact'),
    'activity'   => t('header.menu.activity'),
];

$graph = [];


$organization = [
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
        "streetAddress" => t('contacts.department_location_link'),
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

$graph[] = $organization;


if ($is_home) {
    $website = [
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
    $graph[] = $website;
}


if (!$is_home) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = array_values(array_filter(explode('/', $path)));

    if (!empty($segments) && in_array($segments[0], ['en', 'uk'])) {
        array_shift($segments);
    }

    $has_branches = false;
    $needs_branches_parent = false;
    $has_news = false;
    $needs_news_parent = false;

    foreach ($segments as $seg) {
        if ($seg === 'branches') $has_branches = true;
        if (strpos($seg, 'center_') !== false || strpos($seg, 'filia') !== false) {
            $needs_branches_parent = true;
        }
        if ($seg === 'news') $has_news = true;
        if (preg_match('/^news\d+$/', $seg) || (isset($is_news_page) && $is_news_page)) {
            $needs_news_parent = true;
        }
    }

    if ($needs_branches_parent && !$has_branches) array_unshift($segments, 'branches');
    if ($needs_news_parent && !$has_news) array_unshift($segments, 'news');

    $breadcrumbs_items = [];

    $breadcrumbs_items[] = [
        "@type" => "ListItem",
        "position" => 1,
        "name" => t('header.title'),
        "item" => $site_url . $home_path
    ];

    $position = 2;
    $accumulated_path = '';

    foreach ($segments as $segment) {
        $accumulated_path .= '/' . $segment;
        $name = '';

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

    if (!empty($seoPageTitle) && count($breadcrumbs_items) > 0) {
        $last_key = count($breadcrumbs_items) - 1;
        $breadcrumbs_items[$last_key]['name'] = $seoPageTitle;
    }

    $graph[] = [
        "@type" => "BreadcrumbList",
        "itemListElement" => $breadcrumbs_items
    ];
}


if (!empty($video_url) && !empty($seoPageTitle)) {
    $video_id = '';
    if (preg_match('/(?:youtube\.com\/(?:embed\/|v\/|watch\?v=)|youtu\.be\/)([\w-]{11})/', $video_url, $matches)) {
        $video_id = $matches[1];
    }

    $embedUrl = $video_url;

    $videoSchema = [
        "@type" => "VideoObject",
        "name" => $seoPageTitle,
        "description" => $seoPageDescription ?: $seoPageTitle,
        "uploadDate" => $date_published ?? date('c'),
        "embedUrl" => $embedUrl,


        "thumbnailUrl" => $video_id ? "https://i.ytimg.com/vi/{$video_id}/maxresdefault.jpg" : $site_url . '/assets/img/default-video.jpg',
        "contentUrl" => $video_id ? "https://www.youtube.com/watch?v={$video_id}" : $embedUrl,

        "publisher" => [
            "@id" => $site_url . "/#organization"
        ]
    ];

    $graph[] = $videoSchema;
}

if ($needs_news_parent && isset($pageContent)) {
    $articleSchema = [
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
    $graph[] = $articleSchema;
}

$schema_data = [
    "@context" => "https://schema.org",
    "@graph" => $graph
];

return $schema_data;