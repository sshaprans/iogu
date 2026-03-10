<?php
// src/core/i18n.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Auth.php';


$available_langs = ['uk', 'en'];
$default_lang = 'uk';

if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs, true)) {
    $current_lang = $_GET['lang'];
} else {
    $current_lang = $_SESSION['lang'] ?? $default_lang;
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

$lang_prefix = ($current_lang === $default_lang) ? '' : '/' . $current_lang;

function load_translations_flattened(string $lang): array {
    $result = [];

    try {
        $db = Database::getInstance()->getConnection();
        $safe_lang = $lang === 'en' ? 'en' : 'uk';
        $col = 'text_' . $safe_lang;

        $stmt = $db->query("SELECT key_name, $col as val FROM translations");

        while ($row = $stmt->fetch()) {
            if (!empty($row['val'])) {
                $result[$row['key_name']] = $row['val'];
            }
        }
    } catch (Exception $e) {
        error_log("[i18n] DB Translation Error: " . $e->getMessage());
    }

    if (empty($result)) {
        $file_path_php = __DIR__ . "/locales/{$lang}.php";
        $file_path_json = __DIR__ . "/locales/{$lang}.json";

        if (file_exists($file_path_php)) {
            $file_data = require $file_path_php;
            $result = flatten_array_recursive($file_data);
        } elseif (file_exists($file_path_json)) {
            $file_data = json_decode(file_get_contents($file_path_json), true);
            if (is_array($file_data)) {
                $result = flatten_array_recursive($file_data);
            }
        } else {
            error_log("[i18n] Missing translation file for lang: {$lang}");
        }
    }

    return $result;
}

function flatten_array_recursive(array $array, string $prefix = ''): array {
    $result = [];
    foreach ($array as $key => $value) {
        $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;
        if (is_array($value)) {
            $result = array_merge($result, flatten_array_recursive($value, $new_key));
        } else {
            $result[$new_key] = $value;
        }
    }
    return $result;
}

$translations = load_translations_flattened($current_lang);

/**
 * Переклад за ключем (нап,р. 'header_menu_contact')
 *
 */
function t(string $key): string {
    global $translations;
    if (!empty($translations[$key])) {
        return html_entity_decode($translations[$key], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    return $key;
}

function is_lang_active(string $lang): bool {
    global $current_lang;
    return $current_lang === $lang;
}

function base_url(string $path): string {
    global $lang_prefix;
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }
    return $lang_prefix . $path;
}