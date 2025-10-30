<?php
/**
 * Компонент "Сертифікати/Документи".
 *
 * @var string $certificates_section_title - Заголовок секції (напр. "Сертифікати").
 * @var array  $certificates_list - Масив документів.
 * Кожен елемент - асоціативний масив з ключами:
 * 'link_href', 'img_src', 'img_alt', 'description'
 */

$certificates_section_title = $certificates_section_title ?? 'Документи';
$certificates_list = $certificates_list ?? [];
?>

<section class="container branch-certificates">
    <h3 class="section-title-sup section-title-sup--branches">
        <?php echo htmlspecialchars($certificates_section_title); ?>
    </h3>

    <?php
    foreach ($certificates_list as $cert) :

        $link_href = $cert['link_href'] ?? '#';
        $img_src = $cert['img_src'] ?? 'https://placehold.co/300x200/eee/aaa?text=Doc';
        $img_alt = $cert['img_alt'] ?? 'Документ';
        $description = $cert['description'] ?? 'Опис документа';
        ?>

        <a href="<?php echo htmlspecialchars($link_href); ?>" class="link__document link__document--branches" target="_blank">
            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($img_alt); ?>" class="link__document-img">
            <div class="link__document__wrapper">
                <p class="title__hover-link">
                    Переглянути
                </p>
            </div>
        </a>
        <p class="desc-for-link__document">
            <?php echo htmlspecialchars($description); ?>
        </p>

    <?php endforeach; ?>

</section>
