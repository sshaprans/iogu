<?php
/**
 * @var string $bgLink        - URL фонового зображення.
 * @var array  $history_slides - масив слайдів, де кожен слайд є асоціативним масивом
 */

$bgLink = $bgLink ?? 'https://placehold.co/1920x1080/333/999?text=Default+BG';
$history_slides = $history_slides ?? [];
?>

<section class="swiper mySwiper swiper--branch-history" style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff">

    <div class="parallax-bg" style="
            background-image: url(<?php echo htmlspecialchars($bgLink); ?>);
            " data-swiper-parallax="-23%"></div>

    <div class="swiper-wrapper">

        <?php foreach ($history_slides as $slide) :?>
            <div class="swiper-slide swiper-slide--branch-history">

                <?php if (isset($slide['title']) && !empty($slide['title'])) :?>
                    <h2 class="branch-history__title" data-swiper-parallax="-300">
                        <?php echo htmlspecialchars($slide['title']); ?>
                    </h2>
                <?php endif; ?>

                <?php if (isset($slide['text']) && !empty($slide['text'])) :?>
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            <?php echo htmlspecialchars($slide['text']); ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</section>
