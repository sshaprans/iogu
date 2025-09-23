<?php
// src/core/i18n.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$available_langs = ['uk', 'en'];
$default_lang = 'uk';

if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = $_SESSION['lang'] ?? $default_lang;

/**
 * Глобальна змінна для мовного префікса URL.
 * Для мови за замовчуванням ('uk') вона буде ПУСТОЮ ('').
 * Для англійської ('en') вона буде '/en'.
 * @var string
 */
$lang_prefix = ($current_lang === $default_lang) ? '' : '/' . $current_lang;

$lang_file_path = __DIR__ . "/locales/{$current_lang}.php";
$translations = file_exists($lang_file_path) ? require $lang_file_path : [];

/**
 * Функція для отримання перекладу за ключем.
 * @param string $key Ключ.
 * @return string Повертає перекладений текст або сам ключ.
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
 * @param string $lang Код мови для перевірки.
 * @return bool
 */
function is_lang_active(string $lang): bool {
    global $current_lang;
    return $current_lang === $lang;
}

/**
 * Створює правильний URL з урахуванням поточної мови.
 * Це головна функція для побудови всіх внутрішніх посилань.
 * @param string $path Шлях, який потрібно додати (наприклад, '/about' або 'contacts').
 * @return string Повертає шлях з мовним префіксом (напр., '/en/about').
 */
function base_url(string $path): string {
    global $lang_prefix;

    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }

    return $lang_prefix . $path;
}
