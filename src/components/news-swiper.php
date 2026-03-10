<?php
// src/components/news-swiper.php
require_once __DIR__ . '/../core/Database.php';

$currentLang = $_GET['lang'] ?? 'uk';
$titleField = ($currentLang === 'en') ? 'title_en' : 'title_uk';

$slides = [];

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id, slug, image, $titleField as title FROM news WHERE is_published = 1 ORDER BY date_posted DESC LIMIT 6");
    $stmt->execute();
    $newsItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($newsItems as $item) {
        $slides[] = [
                'href' => ($currentLang === 'en' ? '/en' : '') . '/news/' . htmlspecialchars($item['slug']),
                'img' => !empty($item['image']) ? htmlspecialchars($item['image']) : '/img/no-image.png',
                'text' => $item['title'] ?? '',
        ];
    }
} catch (Exception $e) {
    error_log("News Swiper Component Error: " . $e->getMessage());
}
?>

<div class="swiper-wrapper">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $slide): ?>
                <div class="swiper-slide">
                    <a href="<?= $slide['href'] ?>" class="swiper-link" style="display: block; text-decoration: none; height: 100%;">
                        <img src="<?= $slide['img'] ?>"
                             loading="lazy"
                             class="swiper-img"
                             alt="<?= htmlspecialchars($slide['text']) ?>"
                             width="420" height="350"
                             style="width: 100%; height: auto; object-fit: cover;">
                        <h5 class="swiper-text">
                            <?= $slide['text'] ?>
                        </h5>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>