<?php

// src/core/i18n.php

// Починаємо сесію, щоб запам'ятати вибір мови користувачем
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Визначаємо доступні мови та мову за замовчуванням
$available_langs = ['uk', 'en'];
$default_lang = 'uk';

// Якщо користувач натиснув на перемикач, змінюємо мову в сесії
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Визначаємо поточну мову (з сесії або беремо за замовчуванням)
$current_lang = $_SESSION['lang'] ?? $default_lang;

// Завантажуємо відповідний файл перекладу
$lang_file_path = __DIR__ . "/locales/{$current_lang}.php";
$translations = file_exists($lang_file_path) ? require $lang_file_path : [];

/**
 * Функція для отримання перекладу за ключем.
 * @param string $key Ключ, як у файлах перекладу (напр., 'header.title').
 * @return string Повертає перекладений текст або сам ключ, якщо переклад не знайдено.
 */
function t(string $key): string {
    global $translations;

    // Повертаємо переклад. Використовуємо html_entity_decode, щоб теги <br> працювали коректно.
    return html_entity_decode($translations[$key] ?? $key);
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

