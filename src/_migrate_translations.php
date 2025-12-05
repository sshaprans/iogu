<?php
// src/_migrate_translations.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Start importing translations...</h3>";

// --- 1. Пошук файлу db.php ---
// Спрощуємо пошук, припускаючи стандартну структуру Webpack (dist/core/db.php)
$db_path = __DIR__ . '/core/db.php';

if (!file_exists($db_path)) {
    // Запасний варіант для локального запуску із src
    $db_path = __DIR__ . '/../src/core/db.php';
}

if (!file_exists($db_path)) {
    die("<h3 style='color:red'>Error: db.php not found at $db_path</h3>");
}
require_once $db_path;


// --- 2. Шляхи до файлів перекладів (Спрощено) ---
$uk_path = __DIR__ . '/core/locales/uk.php';
$en_path = __DIR__ . '/core/locales/en.php';

// Перевірка існування UK (обов'язково)
if (!file_exists($uk_path)) {
    // Спробуємо знайти в src, якщо запускаємо не з dist
    $uk_path = __DIR__ . '/../src/core/locales/uk.php';
    if (!file_exists($uk_path)) {
        die("<h3 style='color:red'>Error: uk.php not found!</h3><p>Checked: " . __DIR__ . "/core/locales/uk.php</p>");
    }
}

// Перевірка існування EN (якщо є)
if (!file_exists($en_path)) {
    $en_path = __DIR__ . '/../src/core/locales/en.php';
}


// --- 3. ЗАВАНТАЖЕННЯ ДАНИХ ---

// Завантажуємо UK
echo "Loading UK translations from: <strong>$uk_path</strong><br>";
$translations_uk = require $uk_path;
if (!is_array($translations_uk)) {
    die("<h3 style='color:red'>Error: uk.php must return an array!</h3>");
}

// Завантажуємо EN
$translations_en = [];
if (file_exists($en_path)) {
    echo "Loading EN translations from: <strong>$en_path</strong><br>";
    $translations_en = require $en_path;
    if (!is_array($translations_en)) {
        echo "<h4 style='color:orange'>Warning: en.php found but did not return an array. Skipping EN.</h4>";
        $translations_en = [];
    }
} else {
    echo "<h4 style='color:gray'>Notice: en.php not found. Importing only UK.</h4>";
}


// --- 4. Підготовка до імпорту ---
try {
    $db = Database::getInstance()->getConnection();

    // Створення таблиці
    $createTableSql = "
    CREATE TABLE IF NOT EXISTS `translations` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `key_name` varchar(255) NOT NULL COMMENT 'Напр. header.menu.about',
      `text_uk` text,
      `text_en` text,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `key_name` (`key_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $db->exec($createTableSql);
    echo "<p style='color:blue'>checked/created table 'translations'...</p>";

} catch (Exception $e) {
    die("<h3 style='color:red'>DB Connection/Creation Error: " . $e->getMessage() . "</h3>");
}

function flattenArray($array, $prefix = '') {
    $result = [];
    foreach ($array as $key => $value) {
        $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;
        if (is_array($value)) {
            $result = array_merge($result, flattenArray($value, $new_key));
        } else {
            $result[$new_key] = $value;
        }
    }
    return $result;
}

$flat_uk = flattenArray($translations_uk);
$flat_en = flattenArray($translations_en);

// Збираємо всі унікальні ключі
$all_keys = array_unique(array_merge(array_keys($flat_uk), array_keys($flat_en)));
$count = 0;

foreach ($all_keys as $key) {
    $val_uk = isset($flat_uk[$key]) ? $flat_uk[$key] : '';
    $val_en = isset($flat_en[$key]) ? $flat_en[$key] : '';

    // ВИПРАВЛЕНО SQL: використовуємо різні імена параметрів для UPDATE,
    // щоб уникнути помилки "Invalid parameter number"
    $sql = "INSERT INTO translations (key_name, text_uk, text_en) 
            VALUES (:key, :uk, :en)
            ON DUPLICATE KEY UPDATE text_uk = :uk_upd, text_en = :en_upd";

    $stmt = $db->prepare($sql);

    // Передаємо значення двічі: для INSERT і для UPDATE
    $stmt->execute([
        ':key' => $key,
        ':uk' => $val_uk,
        ':en' => $val_en,
        ':uk_upd' => $val_uk,
        ':en_upd' => $val_en
    ]);
    $count++;
}

echo "<h3 style='color:green'>Success! Imported/Updated $count translation keys.</h3>";
echo "<p>UA and EN translations are now in the database.</p>";
echo "<a href='/'>Перейти на головну</a>";
?>