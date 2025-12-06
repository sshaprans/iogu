<?php
session_start();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'uk';
    $_SESSION['lang'] = $lang;
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '/'));
    exit;
}

$current_lang = $_SESSION['lang'] ?? 'uk';

$translations = [];
$file_path = __DIR__ . "/../locales/{$current_lang}.json";
if (file_exists($file_path)) {
    $translations = json_decode(file_get_contents($file_path), true);
}

function t($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
