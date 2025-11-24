<?php
/**
 * $galleryImages = ['img1.jpg', 'img2.jpg'];
 */

if (!isset($galleryImages) || !is_array($galleryImages)) {
    $galleryImages = [];
}
?>

<div class="swiper photo_gallery-swiper">
    <div class="swiper-wrapper">
        <?php foreach ($galleryImages as $imageSrc): ?>
            <div class="swiper-slide">
                <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Gallery image" loading="lazy" />
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<div thumbsSlider="" class="swiper photo_gallery-swiper2">
    <div class="swiper-wrapper">
        <?php foreach ($galleryImages as $imageSrc): ?>
            <div class="swiper-slide">
                <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Thumbnail" loading="lazy" />
            </div>
        <?php endforeach; ?>
    </div>
</div>