<?php
// src/setup_db.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Налаштування та оновлення бази даних...</h2>";

// 1. Підключення до БД
$possible_db_paths = [
    __DIR__ . '/core/db.php',
    __DIR__ . '/src/core/db.php',
    __DIR__ . '/../src/core/db.php'
];

$db_path = null;
foreach ($possible_db_paths as $path) {
    if (file_exists($path)) {
        $db_path = $path;
        break;
    }
}

if (!$db_path) {
    die("<h3 style='color:red'>Помилка: файл core/db.php не знайдено.</h3>");
}
require_once $db_path;

try {
    $db = Database::getInstance()->getConnection();

    // 2. Створення таблиць (пропускаємо, якщо існують)
    $db->exec("CREATE TABLE IF NOT EXISTS `branches` (id int(11) NOT NULL AUTO_INCREMENT, slug varchar(100) NOT NULL, name_uk varchar(255) NOT NULL, name_en varchar(255) DEFAULT NULL, address_uk text, address_en text, phone varchar(50) DEFAULT NULL, email varchar(100) DEFAULT NULL, PRIMARY KEY (id), UNIQUE KEY slug (slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->exec("CREATE TABLE IF NOT EXISTS `users` (id int(11) NOT NULL AUTO_INCREMENT, login varchar(50) NOT NULL, password_hash varchar(255) NOT NULL, role enum('dev','admin','branch_admin') NOT NULL DEFAULT 'admin', branch_id int(11) DEFAULT NULL, name varchar(100) DEFAULT NULL, avatar varchar(255) DEFAULT NULL, PRIMARY KEY (id), UNIQUE KEY login (login), KEY branch_id (branch_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `avatar` VARCHAR(255) DEFAULT NULL AFTER `name`"); } catch (Exception $e) {}
    $db->exec("CREATE TABLE IF NOT EXISTS `news` (id int(11) NOT NULL AUTO_INCREMENT, slug varchar(255) NOT NULL, image varchar(255) DEFAULT NULL, date_posted date NOT NULL, title_uk varchar(255) NOT NULL, title_en varchar(255) DEFAULT NULL, content_uk longtext NOT NULL, content_en longtext, is_published tinyint(1) DEFAULT 1, PRIMARY KEY (id), UNIQUE KEY slug (slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->exec("CREATE TABLE IF NOT EXISTS `translations` (id int(11) NOT NULL AUTO_INCREMENT, key_name varchar(255) NOT NULL, text_uk text, text_en text, created_at timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id), UNIQUE KEY key_name (key_name)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->exec("CREATE TABLE IF NOT EXISTS `settings` (id int(11) NOT NULL AUTO_INCREMENT, key_name varchar(50) NOT NULL, value_uk text, value_en text, description varchar(255) DEFAULT NULL, PRIMARY KEY (id), UNIQUE KEY key_name (key_name)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $db->exec("CREATE TABLE IF NOT EXISTS `activity_logs` (id int(11) NOT NULL AUTO_INCREMENT, user_id int(11) NOT NULL, user_name varchar(100) DEFAULT NULL, user_role varchar(20) DEFAULT NULL, action varchar(50) NOT NULL, entity_type varchar(50) NOT NULL, entity_id int(11) DEFAULT NULL, details text, created_at timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    echo "<p>✔ Структура таблиць перевірена.</p>";

    // 3. Дані та API Ключ

    $correctKey = 'AIzaSyA-CvThfHKSJRH4jBIxviaiy5mghENwJuc';

    $settings = [
        ['header_phone', '(044)-356-53-21', 'Телефон у шапці'],
        ['social_facebook', 'https://www.facebook.com/iogu.gov.ua', 'Facebook посилання'],
        ['gemini_api_key', $correctKey, 'API Key для ШІ помічника']
    ];

    foreach ($settings as $set) {
        if ($set[0] === 'gemini_api_key') {
            // Примусово оновлюємо ключ
            $stmt = $db->prepare("INSERT INTO settings (key_name, value_uk, description) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE value_uk = ?");
            $stmt->execute([$set[0], $set[1], $set[2], $set[1]]);
        } else {
            $stmt = $db->prepare("INSERT IGNORE INTO settings (key_name, value_uk, description) VALUES (?, ?, ?)");
            $stmt->execute($set);
        }
    }
    echo "<p style='color:green; font-weight:bold;'>✔ API Key успішно оновлено на: " . substr($correctKey, 0, 5) . "..." . substr($correctKey, -3) . "</p>";

} catch (Exception $e) {
    die("<h3 style='color:red'>Помилка: " . $e->getMessage() . "</h3>");
}

echo "<h3>🎉 Готово!</h3>";
echo '<p><a href="/admin/login">Вхід в адмінку</a></p>';
?>