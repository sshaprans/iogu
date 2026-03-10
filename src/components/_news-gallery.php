<?php
// src/components/_news-gallery.php
require_once __DIR__ . '/../core/Database.php';

$currentLang = $_GET['lang'] ?? 'uk';
$titleField = ($currentLang === 'en') ? 'title_en' : 'title_uk';
$contentField = ($currentLang === 'en') ? 'content_en' : 'content_uk';

$e = fn($s) => htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');

$requestedSlug = $_GET['slug'] ?? null;
$singlePost = null;
$galleryPosts = [];

try {
    $db = Database::getInstance()->getConnection();

    if ($requestedSlug) {
        $stmt = $db->prepare("SELECT id, image, header_gallery, date_posted, $titleField as title, $contentField as content FROM news WHERE slug = ? AND is_published = 1");
        $stmt->execute([$requestedSlug]);
        $singlePost = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$singlePost) {
        $stmt = $db->prepare("SELECT id, slug, image, date_posted, $titleField as title FROM news WHERE is_published = 1 ORDER BY date_posted DESC LIMIT 6");
        $stmt->execute();
        $galleryPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $ex) {
    error_log("News Gallery Component Error: " . $ex->getMessage());
}
?>

<div id="news-gallery" class="news-gallery" data-lang="<?= $e($currentLang) ?>">

    <h3 class="section-title" >
        <?= t('news_gallery_title') ?>
    </h3>

    <div id="news-list-view" style="<?= $singlePost ? 'display:none;' : '' ?>">
        <div class="news__gallery-wrap" id="news-container">
            <?php if (!empty($galleryPosts)): ?>
                <?php foreach ($galleryPosts as $post): ?>
                    <?php
                    $link = ($currentLang === 'en' ? '/en' : '') . '/news/' . $e($post['slug']);
                    $imgSrc = !empty($post['image']) ? $e($post['image']) : '/img/no-image.png';
                    ?>
                    <a href="<?= $link ?>" class="news__gallery__block" data-id="<?= $post['id'] ?>">
                        <h5 class="news__gallery__block-title"><?= $post['title'] ?? '' ?></h5>
                        <img src="<?= $imgSrc ?>" loading="lazy" class="news__gallery__block-img" alt="<?= $e($post['title']) ?>" width="420" height="350">
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="opacity: 0.6; padding: 20px;">Новин поки немає.</p>
            <?php endif; ?>
        </div>

        <span class="news__gallery-more download-doc-btn <?= count($galleryPosts) < 6 ? 'disabled' : '' ?>"
              id="load-more-news" data-offset="6" style="cursor: pointer;">-> більше</span>
    </div>

    <div id="single-news-container" style="<?= !$singlePost ? 'display: none;' : '' ?>">
        <?php if ($singlePost): ?>
            <div class="news__gallery__post" id="post-<?= $e($singlePost['id']) ?>">
                <div style="margin-bottom: 20px;">
                    <a href="/<?= $currentLang === 'en' ? 'en/' : '' ?>news" class="btn-back-to-list news-back-btn download-doc-btn">&larr; До списку новин</a>
                </div>

                <h3 class="news__gallery__post-title"><?= $singlePost['title'] ?? '' ?></h3>

                <?php
                $headerImages = !empty($singlePost['header_gallery']) ? json_decode($singlePost['header_gallery'], true) : [];
                if (!empty($headerImages)):
                    foreach ($headerImages as $img): ?>
                        <img src="<?= $e($img) ?>" class="news__gallery__post-img" style="max-width: 100%; height: auto; margin-bottom: 10px; border-radius: 8px;">
                    <?php endforeach;
                elseif (!empty($singlePost['image'])): ?>
                    <img src="<?= $e($singlePost['image']) ?>" class="news__gallery__post-img" style="max-width: 100%; height: auto; margin-bottom: 20px; border-radius: 8px;">
                <?php endif;
                ?>

                <div class="news__gallery__post-date"><?= date('d.m.Y', strtotime($singlePost['date_posted'])) ?></div>
                <div class="news__gallery__post__content"><?= $singlePost['content'] ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>