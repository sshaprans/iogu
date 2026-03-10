<?php
$blocks = [
        [
                'title' => 'down.link_title1',
                'links' => [
                        ['href' => 'https://nsdi.gov.ua/login?redirect=/', 'label' => 'down.link1_1'],
                        ['href' => 'https://e.land.gov.ua/', 'label' => 'down.link1_2'],
                        ['href' => 'https://map.land.gov.ua/?cc=3461340.1719504707,6177585.367221659&z=6.5&l=kadastr&bl=ortho10k_all', 'label' => 'down.link1_3'],
                        ['href' => 'https://agroelita.info/', 'label' => 'down.link1_4'],
                ],
        ],
        [
                'title' => 'down.link_title2',
                'links' => [
                        ['href' => 'https://land.gov.ua/', 'label' => 'down.link2_1'],
                        ['href' => 'https://darg.gov.ua/', 'label' => 'down.link2_2'],
                ],
        ],
        [
                'title' => 'down.link_title3',
                'links' => [
                        ['href' => 'https://www.president.gov.ua/', 'label' => 'down.link3_1'],
                        ['href' => 'https://www.kmu.gov.ua/', 'label' => 'down.link3_2'],
                        ['href' => 'https://www.rada.gov.ua/', 'label' => 'down.link3_3'],
                        ['href' => 'https://nads.gov.ua/', 'label' => 'down.link3_4'],
                        ['href' => 'https://me.gov.ua/', 'label' => 'down.link3_5'],
                ],
        ],
];

$e = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?>

<section id="main-down" aria-labelledby="main-down-title">
    <div class="container">
        <?php foreach ($blocks as $i => $block): ?>
            <div class="main-down__block">
                <h5 class="main-down__block-title" id="main-down-title-<?= $i ?>">
                    <?= t($block['title']) ?>
                </h5>
                <ul class="main-down__block-list" aria-labelledby="main-down-title-<?= $i ?>">
                    <?php foreach ($block['links'] as $link): ?>
                        <li class="main-down__block-item">
                            <a href="<?= $e($link['href']) ?>" target="_blank" rel="noopener noreferrer">
                                <?= t($link['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</section>