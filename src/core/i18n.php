<?php
// запам'ятати вибір мови
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$available_langs = ['uk', 'en'];
$default_lang = 'uk';

if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = $_SESSION['lang'] ?? $default_lang;

$lang_file_path = __DIR__ . "/locales/{$current_lang}.php";
$translations = file_exists($lang_file_path) ? require $lang_file_path : [];

/**
 * Функція для отримання перекладу за ключем.
 * @param string $key Ключ,
 * @return string Повертає перекладений текст або сам ключ, якщо переклад не знайдено.
 */
function t(string $key): string {
    global $translations;

    $segments = explode('.', $key);
    $value = $translations;

    foreach ($segments as $segment) {
        if (!isset($value[$segment])) {
            return $key;
        }
        $value = $value[$segment];
    }

    return is_string($value) ? html_entity_decode($value) : $key;
}


/**
 * Функція для перевірки, чи активна зараз вказана мова.
 * @param string $lang Код мови для перевірки ('uk', 'en').
 * @return bool Повертає true, якщо мова активна.
 */
function is_lang_active(string $lang): bool {
    global $current_lang;
    return $current_lang === $lang;
}

