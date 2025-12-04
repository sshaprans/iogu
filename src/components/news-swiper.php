<?php
$slides = [
        [
                'href' => base_url('/news2025').'#04_12_2025',
                'img' => 'https://media.iogu.gov.ua/img/home/news-swiper/04_12_25_main.png',
                'text' => t('home.swiper.tex04_12'),
        ],
        [
                'href' => base_url('/news2025').'#24_11_2025_5',
                'img' => 'https://media.iogu.gov.ua/img/home/news-swiper/24_11_5_main.jpg',
                'text' => t('home.swiper.tex24_11_5'),
        ],
        [
                'href' => base_url('/news2025').'#24_11_2025_4',
                'img' => 'https://media.iogu.gov.ua/img/home/news-swiper/24_11_4_main.png',
                'text' => t('home.swiper.tex24_11_4'),
        ],
        [
                'href' => base_url('/news2025').'#24_11_2025_3',
                'img' => 'https://media.iogu.gov.ua/img/home/news-swiper/24_11_3_main.jpg',
                'text' => t('home.swiper.tex24_11_3'),
        ],
        [
                'href' => base_url('/news2025').'#24_11_2025_2',
                'img' => 'https://media.iogu.gov.ua/img/home/news-swiper/24_11_2_main.jpg',
                'text' => t('home.swiper.tex24_11_2'),
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
