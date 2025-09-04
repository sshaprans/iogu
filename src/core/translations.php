<?php
session_start();

// Встановлення мови, якщо вона прийшла з запиту (наприклад, з кнопок)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'uk';
    $_SESSION['lang'] = $lang;
    // Перенаправляємо користувача на ту ж сторінку, але без ?lang=... в адресі
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Визначаємо поточну мову
$current_lang = $_SESSION['lang'] ?? 'uk';

$translations = [];
$file_path = __DIR__ . "/../locales/{$current_lang}.json";
if (file_exists($file_path)) {
    $translations = json_decode(file_get_contents($file_path), true);
}

// Функція-хелпер для легкого доступу до перекладів
function t($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
