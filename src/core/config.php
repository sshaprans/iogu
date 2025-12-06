<?php
/**
 * src/core/config.php
 * Глобальна конфігурація та Хелпер
 */

class Config
{
    protected static $items = [];

    public static function load(array $items)
    {
        self::$items = $items;
    }

    public static function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return self::$items;
        }

        if (array_key_exists($key, self::$items)) {
            return self::$items[$key];
        }

        $array = self::$items;
        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array) && is_array($array)) {
                $array = $array[$segment];
            } elseif (array_key_exists($segment, $array)) {
                return $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function set($key, $value)
    {
        self::$items[$key] = $value;
    }
}

if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return Config::get();
        }
        return Config::get($key, $default);
    }
}

// --- ЛОГІКА ВИЗНАЧЕННЯ ОТОЧЕННЯ (DB) ---

// 1. Дефолтні налаштування (ПОРОЖНІ)
// Ми прибрали getenv() і 'root', щоб уникнути випадкового підключення з дефолтними даними.
$dbConfig = [
    'host' => '',
    'name' => '',
    'user' => '',
    'pass' => '',
];

$debugMode = false;

// 2. РОЗУМНИЙ ПОШУК env.php
// __DIR__ вказує на папку core. dirname(__DIR__) - це корінь src або public_html.

$possiblePaths = [
    // Пріоритет 1: Файл env.php лежить поруч із папкою core (у корені сайту)
    dirname(__DIR__) . '/env.php',

    // Пріоритет 2: Локальна розробка (якщо config.php глибоко в src/core)
    dirname(dirname(__DIR__)) . '/env.php'
];

$envFile = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $envFile = $path;
        break;
    }
}

// --- БЛОК ДІАГНОСТИКИ (Пише в error.log) ---
if (!$envFile) {
    // Якщо env.php не знайдено, пишемо в лог, де ми шукали
    error_log("[CONFIG DEBUG] CRITICAL: File env.php NOT FOUND. Searched in: " . implode(', ', $possiblePaths));
} else {
    // Якщо знайдено - пишемо, що все ок (можна вимкнути пізніше)
    // error_log("[CONFIG DEBUG] SUCCESS: Loaded env.php from: " . $envFile);
}
// ------------------------------------------

if ($envFile) {
    $env = require $envFile;
    if (isset($env['db'])) {
        $dbConfig = array_merge($dbConfig, $env['db']);
    }
    if (isset($env['debug'])) {
        $debugMode = $env['debug'];
    }
}

// 3. Формуємо фінальний масив конфігурації
$configuration = [
    'db' => $dbConfig,

    'site_url' => 'https://iogu.gov.ua',

    'site_title'       => 'Державна установа Інститут охорони ґрунтів України',
    'site_alt_name'    => 'Державний науково-дослідницький Інститут охорони ґрунтів України',
    'meta_description' => 'Науковий підхід: Експертні інструкції для родючості ґрунту та максимального врожаю. Здоров\'я ваших земель. Нові матеріали щотижня!',

    'contact_phones'   => [['value' => '(093)-013-80-74', 'label' => 'Мобільний']],
    'contact_emails'   => [['value' => '(044) 356-53-21', 'label' => 'Основний']],
    'contact_addresses'=> [],
    'map_zoom'         => 12,

    'social_facebook'  => 'https://www.facebook.com/iogu.gov.ua',
    'social_instagram' => 'https://www.instagram.com/your_instagram',
    'social_telegram'  => 'https://t.me/+380930138074',
    'social_viber'     => 'viber://chat?number=%2B380930138074',
    'social_whatsapp'  => 'https://wa.me/380930138074',
    'social_youtube'   => 'https://www.youtube.com/@channel_name',

    'gemini_api_key'       => 'gemini_api_key',
    'google_analytics_id'  => 'G-8VFCKEQQ19',
    'google_maps_key'      => 'google_maps_key',
    'recaptcha_site_key'   => 'recaptcha_site_key',
    'recaptcha_secret_key' => 'recaptcha_secret_key',
];

Config::load($configuration);

// 4. ДИНАМІЧНЕ ЗАВАНТАЖЕННЯ З БД (якщо є підключення)
if (!empty(config('db.host')) && !empty(config('db.name'))) {
    try {
        $dsn = "mysql:host=" . config('db.host') . ";dbname=" . config('db.name') . ";charset=utf8mb4";
        $pdo = new PDO($dsn, config('db.user'), config('db.pass'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
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
        // Логуємо помилку підключення з поміткою DEBUG
        error_log("[CONFIG DEBUG] DB Connection Failed: " . $e->getMessage() . " | User tried: " . config('db.user') . " | Host: " . config('db.host'));
    }
} else {
    // Якщо конфіг пустий
    error_log("[CONFIG DEBUG] SKIPPING DB: Database config is empty. Check env.php");
}