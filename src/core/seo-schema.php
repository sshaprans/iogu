<?php
/**
 * SEO Schema.org Generator
 * core/config.php
 * ($is_home, $site_url, $home_path, $social_links, $dictionary, $seoPageTitle)
 */

$dictionary = [
    'branches'   => t('header.menu.branches'),
    'about'      => t('header.menu.about'),
    'news'       => t('header.menu.press_center'),
    'contacts'   => t('header.menu.contact'),
    'activity'   => t('header.menu.activity'),
];

$schema_data = [];

if (!isset($site_url))
    return [];

if ($is_home) {
    // main
    $schema_data = [
        "@context" => "https://schema.org",
        "@type" => "GovernmentOrganization",
        "name" => $seoPageTitle,
        "alternateName" => $seoPageDescription,
        "url" => $site_url . $home_path,
        "logo" => $site_url . '/assets/img/logo.png',
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
                "telephone" => "$headPhone",
                "contactType" => t('contacts.department_title1'),
                "areaServed" => "UA",
                "availableLanguage" => ["Ukrainian", "English"]
            ],
            [
                "@type" => "ContactPoint",
                "telephone" => "$headMobile",
                "contactType" => t('contacts.department_title1'),
                "areaServed" => "UA",
                "availableLanguage" => "Ukrainian"
            ]
        ],
        "sameAs" => $social_links,
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => $site_url . "/search?q={search_term_string}",
            "query-input" => "required name=search_term_string"
        ]
    ];
} else {
    // Breadcrumbs

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = array_values(array_filter(explode('/', $path)));

    if (!empty($segments) && in_array($segments[0], ['en', 'uk'])) {
        array_shift($segments);
    }

    $has_branches = false;
    $needs_branches_parent = false;
    foreach ($segments as $seg) {
        if ($seg === 'branches') $has_branches = true;
        if (strpos($seg, 'center_') !== false || strpos($seg, 'filia') !== false) {
            $needs_branches_parent = true;
        }
    }
    if ($needs_branches_parent && !$has_branches) array_unshift($segments, 'branches');

    $has_news = false;
    $needs_news_parent = false;
    foreach ($segments as $seg) {
        if ($seg === 'news') $has_news = true;
        if (preg_match('/^news\d+$/', $seg)) $needs_news_parent = true;
    }
    if ($needs_news_parent && !$has_news) array_unshift($segments, 'news');

    // Формуємо масив
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
            "name" => $name,
            "item" => $site_url . $accumulated_path
        ];
        $position++;
    }

    if (!empty($seoPageTitle) && count($breadcrumbs_items) > 0) {
        $last_key = count($breadcrumbs_items) - 1;
        $breadcrumbs_items[$last_key]['name'] = $seoPageTitle;
    }

    $schema_data = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $breadcrumbs_items
    ];
}

return $schema_data;