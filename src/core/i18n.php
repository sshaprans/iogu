<?php
// src/core/i18n.php

require_once __DIR__ . '/db.php';

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
    if (empty($new_uri)) $new_uri = '/';
    header('Location: ' . $new_uri, true, 301);
    exit();
}

$lang_prefix = ($current_lang === $default_lang) ? '' : '/' . $current_lang;

function load_translations_flattened($lang) {
    $result = [];
    $db_error = null;

    try {
        $db = Database::getInstance()->getConnection();
        $col = 'text_' . $lang;
        $stmt = $db->query("SELECT key_name, $col as val FROM translations");

        while ($row = $stmt->fetch()) {
            if (!empty($row['val'])) {
                $result[$row['key_name']] = $row['val'];
            }
        }
    } catch (Exception $e) {
        $db_error = $e->getMessage();
        echo "<!-- DB Translation Error: " . htmlspecialchars($db_error) . " -->";
    }

    if (empty($result)) {
        $file_path = __DIR__ . "/locales/{$lang}.php";
        if (file_exists($file_path)) {
            $file_data = require $file_path;
            $result = flatten_array_recursive($file_data);
            echo "<!-- Loaded translations from FILE fallback due to empty DB or Error -->";
        }
    }

    return $result;
}

function flatten_array_recursive($array, $prefix = '') {
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
 * переклад за ключем (напр. 'header.menu.contact')
 */
function t(string $key): string {
    global $translations;
    if (isset($translations[$key]) && !empty($translations[$key])) {
        return html_entity_decode($translations[$key]);
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