<?php
// src/setup_updates.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/core/db.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "<h2>Оновлення бази даних (CRM)...</h2>";

    // Таблиця повідомлень
    $sql = "
    CREATE TABLE IF NOT EXISTS `messages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `contact` varchar(100) NOT NULL COMMENT 'Телефон або Email',
      `message` text,
      `source_url` varchar(255) NOT NULL COMMENT 'Сторінка, звідки прийшла заявка',
      `branch_id` int(11) DEFAULT NULL COMMENT 'ID філії, якщо визначено',
      `status` enum('new','processing','done') DEFAULT 'new',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `branch_id` (`branch_id`),
      KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $db->exec($sql);
    echo "<p style='color:green'>✔ Таблицю 'messages' створено/перевірено.</p>";

    echo "<h3>Оновлення завершено!</h3>";
} catch (Exception $e) {
    die("<h3 style='color:red'>Помилка: " . $e->getMessage() . "</h3>");
}
?>