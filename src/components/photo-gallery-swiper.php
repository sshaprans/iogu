<?php
$swiperClass = $swiperClass ?? 'photo_gallery-swiper-default';
?>

<div class="photo-gallery-container">
    <div class="swiper photo_gallery-swiper2 <?php echo htmlspecialchars($swiperClass); ?>-main">
        <div class="swiper-wrapper">
            <?php foreach ($galleryImages as $image): ?>
                <div class="swiper-slide">
                    <img src="<?php echo htmlspecialchars($image); ?>" alt="Галерея зображень" loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div class="swiper photo_gallery-swiper2 <?php echo htmlspecialchars($swiperClass); ?>-thumbs">
        <div class="swiper-wrapper">
            <?php foreach ($galleryImages as $image): ?>
                <div class="swiper-slide">
                    <img src="<?php echo htmlspecialchars($image); ?>" alt="Мініатюра">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>