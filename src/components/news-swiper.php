<?php
$slides = [
        [
                'href' => './link/press_center/magazines/magazine_2025.html#21_08_2025',
                'img' => '/img/home/21_08_main.JPG',
                'text' => t('home.swiper.tex1'),
        ],
        [
                'href' => './link/press_center/magazines/magazine_2025.html#20_08_2025',
                'img' => '/img/home/20_08_main.png',
                'text' => t('home.swiper.tex2'),
        ],
        [
                'href' => './link/press_center/magazines/magazine_2025.html#01_08_2025',
                'img' => '/img/home/01_08_main.png',
                'text' => t('home.swiper.tex3'),
        ],
];
?>

<div class="swiper-wrapper">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $slide): ?>
                <div class="swiper-slide">
                    <a href="<?= $slide['href'] ?>" class="swiper-link">
                        <img class="swiper-img" src="<?= $slide['img'] ?>" alt="" loading="lazy">
                        <div class="swiper-text">
                            <p><?= $slide['text'] ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="swiper-btn swiper-button-prev"></div>
    <div class="swiper-btn swiper-button-next"></div>
</div>
