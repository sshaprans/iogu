<?php
$slides = [
        [
                'href' => base_url('/news2025').'#11_11_2025',
                'img' => 'https://media.iogu.gov.ua/img/magazine/2025/11_11_25.jpg',
                'text' => t('home.swiper.tex11_11_25'),
        ],
        [
                'href' => base_url('/news2025').'#10_11_2025',
                'img' => 'https://media.iogu.gov.ua/img/magazine/2025/10_11_,main.jpg',
                'text' => t('home.swiper.tex10_11_25'),
        ],
        [
                'href' => base_url('/news2025').'#21_08_2025',
                'img' => 'https://media.iogu.gov.ua/img/home/20_08_main.png',
                'text' => t('home.swiper.tex1'),
        ],
        [
                'href' => base_url('/news2025').'#20_08_2025',
                'img' => 'https://media.iogu.gov.ua/img/home/20_08_main.png',
                'text' => t('home.swiper.tex2'),
        ],
        [
                'href' => base_url('/news2025').'#01_08_2025',
                'img' => 'https://media.iogu.gov.ua/img/home/01_08_main.png',
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
