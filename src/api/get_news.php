<?php
// src/api/get_news.php

require_once __DIR__ . '/../core/Database.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();

    $lang = $_GET['lang'] ?? 'uk';
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = 6;

    $titleField = ($lang === 'en') ? 'title_en' : 'title_uk';

    $sql = "SELECT id, slug, image, date_posted, $titleField as title 
            FROM news 
            WHERE is_published = 1 
            ORDER BY date_posted DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as &$post) {
        $post['title'] = $post['title'] ?? '';
        $post['link'] = ($lang === 'en' ? '/en' : '') . '/news/' . htmlspecialchars($post['slug']);
        $post['image'] = !empty($post['image']) ? $post['image'] : '/img/no-image.png';
    }

    echo json_encode(['status' => 'success', 'data' => $posts]);

} catch (Exception $e) {
    http_response_code(500);
    error_log("Get News API Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error']);
}