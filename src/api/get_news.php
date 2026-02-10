<?php
// src/api/get_news.php

if (file_exists(__DIR__ . '/../core/db.php')) {
    require_once __DIR__ . '/../core/db.php';
} elseif (file_exists(__DIR__ . '/../core/Database.php')) {
    require_once __DIR__ . '/../core/Database.php';
}

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();

    $lang = $_GET['lang'] ?? 'uk';
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = 6;

    $titleField = ($lang === 'en') ? 'title_en' : 'title_uk';

    // SQL: Просто сортування за датою (спочатку нові)
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
        $post['title'] = htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8');
        $post['link'] = ($lang === 'en' ? '/en' : '') . '/news/' . htmlspecialchars($post['slug']);
        $post['image'] = !empty($post['image']) ? $post['image'] : '/img/no-image.png';
    }

    echo json_encode(['status' => 'success', 'data' => $posts]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}