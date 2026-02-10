<?php
// src/api/get_single_news.php

if (file_exists(__DIR__ . '/../core/db.php')) {
    require_once __DIR__ . '/../core/db.php';
} elseif (file_exists(__DIR__ . '/../core/Database.php')) {
    require_once __DIR__ . '/../core/Database.php';
}

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('ID is required');
    }

    $id = (int)$_GET['id'];
    $lang = $_GET['lang'] ?? 'uk';

    // Визначаємо поля залежно від мови
    $titleField = ($lang === 'en') ? 'title_en' : 'title_uk';
    $contentField = ($lang === 'en') ? 'content_en' : 'content_uk';

    $db = Database::getInstance()->getConnection();

    // Запит однієї новини
    $stmt = $db->prepare("SELECT id, date_posted, $titleField as title, $contentField as content FROM news WHERE id = ? AND is_published = 1");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        throw new Exception('News not found');
    }

    // Форматуємо дату
    $timestamp = strtotime($post['date_posted']);
    if ($lang === 'en') {
        $post['formatted_date'] = date('F j, Y', $timestamp);
    } else {
        $months = [
            1 => 'січня', 2 => 'лютого', 3 => 'березня', 4 => 'квітня',
            5 => 'травня', 6 => 'червня', 7 => 'липня', 8 => 'серпня',
            9 => 'вересня', 10 => 'жовтня', 11 => 'листопада', 12 => 'грудня'
        ];
        $post['formatted_date'] = date('j', $timestamp) . ' ' . $months[date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }

    // Очищаємо заголовок, але КОНТЕНТ залишаємо як є (там HTML)
    $post['title'] = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');

    echo json_encode(['status' => 'success', 'data' => $post]);

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}