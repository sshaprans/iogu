<?php
/**
 * src/core/config.php
 * Global Site Configuration & Initialization
 */

$current_uri = $_SERVER['REQUEST_URI'];

$seoPageTitle = $GLOBALS['page_title'] ?? ($page_title ?? null);
$seoPageDescription = $GLOBALS['page_description'] ?? ($page_description ?? null);

$site_url = 'https://www.iogu.gov.ua';

$home_path = function_exists('base_url') ? base_url('/') : '/';
$is_home = (rtrim($current_uri, '/') == rtrim($home_path, '/') || $current_uri == '/index.php');

// socialLinks & contacts

$fbLink = "https://www.facebook.com/iogu.gov.ua";
$youtubeLink = "https://www.youtube.com/@channel_name";
$tgLink = "https://t.me/+380930138074";
$instagramLink = "https://www.instagram.com/your_instagram";
$whatsAppLink = "https://wa.me/380930138074";
$viberLink = "viber://chat?number=%2B380930138074";

$social_links = [
    $fbLink,
    $youtubeLink,
    $tgLink,
    $instagramLink,
    $whatsAppLink,
    $viberLink
];


$headPhone = "(044) 356-53-21";
$headPhoneLink = "0443565321";
$headCity = t('Kyiv');
$headMobile = "(093)-013-80-74";
$headMobileLink = "+380930138074";

