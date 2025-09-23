<?php
// src/core/i18n.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$available_langs = ['uk', 'en'];
$default_lang = 'uk';

if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $current_lang = $_GET['lang'];
} else {
    $current_lang = $default_lang;
}

$_SESSION['lang'] = $current_lang;

$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
if ($current_lang === $default_lang && strpos($request_uri, '/' . $default_lang) === 0) {
    $new_uri = substr($request_uri, strlen('/' . $default_lang));
    if (empty($new_uri)) {
        $new_uri = '/';
    }
    header('Location: ' . $new_uri, true, 301);
    exit();
}

/**
 * Глобальна змінна для мовного префікса URL.
 */
$lang_prefix = ($current_lang === $default_lang) ? '' : '/' . $current_lang;

$lang_file_path = __DIR__ . "/locales/{$current_lang}.php";
$translations = file_exists($lang_file_path) ? require $lang_file_path : [];

/**
 * Функція для отримання перекладу за ключем.
 */
function t(string $key): string {
    global $translations;
    $segments = explode('.', $key);
    $value = $translations;
    foreach ($segments as $segment) {
        if (!isset($value[$segment])) return $key;
        $value = $value[$segment];
    }
    return is_string($value) ? html_entity_decode($value) : $key;
}

/**
 * Функція для перевірки, чи активна зараз вказана мова.
 */
function is_lang_active(string $lang): bool {
    global $current_lang;
    return $current_lang === $lang;
}

/**
 * Створює правильний URL з урахуванням поточної мови.
 */
function base_url(string $path): string {
    global $lang_prefix;
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }
    return $lang_prefix . $path;
}
