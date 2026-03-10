<?php
/**
 * src/core/Config.php
 */

class Config
{
    protected static array $items = [];

    public static function load(array $items): void
    {
        self::$items = $items;
    }

    public static function get(string $key = null, $default = null)
    {
        if (is_null($key)) {
            return self::$items;
        }

        if (array_key_exists($key, self::$items)) {
            return self::$items[$key];
        }

        $array = self::$items;
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function set(string $key, $value): void
    {
        self::$items[$key] = $value;
    }
}

if (!function_exists('config')) {
    function config(string $key = null, $default = null)
    {
        if (is_null($key)) {
            return Config::get();
        }
        return Config::get($key, $default);
    }
}

$dbConfig = [
    'host' => '',
    'name' => '',
    'user' => '',
    'pass' => '',
];

$debugMode = false;

$possiblePaths = [
    dirname(__DIR__) . '/env.php',
    dirname(dirname(__DIR__)) . '/env.php'
];

foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $env = require $path;
        if (isset($env['db'])) {
            $dbConfig = array_merge($dbConfig, $env['db']);
        }
        if (isset($env['debug'])) {
            $debugMode = (bool)$env['debug'];
        }
        break;
    }
}

$configuration = [
    'db'    => $dbConfig,
    'debug' => $debugMode,

    'site_url'         => 'https://iogu.gov.ua',
    'site_title'       => function_exists('t') ? t('site_title') : 'site_title',
    'site_alt_name'    => 'Державний науково-дослідницький Інститут охорони ґрунтів України',
    'meta_description' => 'Науковий підхід: Експертні інструкції для родючості ґрунту та максимального врожаю. Здоров\'я ваших земель. Нові матеріали щотижня!',

    'contact_phones'   => [['value' => '(093)-013-80-74', 'label' => 'Мобільний']],
    'contact_emails'   => [['value' => '(044) 356-53-21', 'label' => 'Основний']],
    'contact_addresses'=> [],
    'map_zoom'         => 12,

    'social_facebook'  => 'social_facebook',
    'social_instagram' => 'https://www.instagram.com/your_instagram',
    'social_telegram'  => 'https://t.me/+380930138074',
    'social_viber'     => 'viber://chat?number=%2B380930138074',
    'social_whatsapp'  => 'https://wa.me/380930138074',
    'social_youtube'   => 'https://www.youtube.com/@channel_name',

    'gemini_api_key'       => 'gemini_api_key',
    'google_analytics_id'  => 'G-8VFCKEQQ19',
    'google_maps_key'      => 'google_maps_key',
    'recaptcha_site_key'   => '6Le3sGYsAAAAAA7OjbJoZfPC-1TdzFYK-EbMeBVF',
    'recaptcha_secret_key' => '6Le3sGYsAAAAAOgbMIVG_Tn6RNkI5LhhZ7Tcd09f',
];

Config::load($configuration);

define('MEDIA_URL', config('media.url_prefix', '/media/'));
define('IMAGE_PATH', config('media.url_prefix', '/media/'));

if (!empty(config('db.host')) && !empty(config('db.name'))) {
    try {
        $dsn = "mysql:host=" . config('db.host') . ";dbname=" . config('db.name') . ";charset=utf8mb4";
        $pdo = new PDO($dsn, config('db.user'), config('db.pass'), [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        try {
            $stmt = $pdo->query("SELECT name, value FROM settings");
            while ($row = $stmt->fetch()) {
                if (!empty($row['name'])) {
                    $value = $row['value'];
                    if (in_array($row['name'], ['contact_phones', 'contact_emails', 'contact_addresses'])) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $value = $decoded;
                        }
                    }
                    Config::set($row['name'], $value);
                }
            }
        } catch (PDOException $e) {
            // Таблиці ще немає, ігноруємо
        }
    } catch (Exception $e) {
        error_log("[CONFIG DEBUG] DB Connection Failed. Check env.php configuration.");
    }
} else {
    error_log("[CONFIG DEBUG] SKIPPING DB: Database config is empty.");
}