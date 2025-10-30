<?php
/**
 * Компонент "Керівництво".
 *
 * Очікує на дві змінні:
 * @var string $leadership_title - Заголовок секції (напр. "Керівництво центру").
 * @var array  $leadership_staff - Масив співробітників, де кожен елемент -
 * асоціативний масив з ключами: 'img_src', 'name', 'position', 'phone_raw', 'phone_formatted'.
 */

$leadership_title = $leadership_title ?? 'Керівництво';
$leadership_staff = $leadership_staff ?? [];
?>

<section class="container branch-head">
    <h3 class="section-title-sup section-title-sup--branches">
        <?php echo htmlspecialchars($leadership_title); ?>
    </h3>
    <div class="branches-leaderships__wrapper">

        <?php
            foreach ($leadership_staff as $member) :

            $img_src = $member['img_src'] ?? 'https://placehold.co/300x300/eee/aaa?text=Фото';
            $name = $member['name'] ?? 'Ім\'я не вказано';
            $position = $member['position'] ?? 'Посада не вказана';
            $phone_raw = $member['phone_raw'] ?? '';
            $phone_formatted = $member['phone_formatted'] ?? 'Телефон не вказано';
            $alt_text = htmlspecialchars($name);
            ?>
            <div class="branches-leaderships__box">
                <img class="branches-leaderships__img" src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo $alt_text; ?>">
                <div class="branches-leaderships__info">
                    <p class="branches-leaderships__name">
                        <?php echo htmlspecialchars($name); ?>
                    </p>
                    <p class="branches-leaderships__position">
                        <?php echo htmlspecialchars($position); ?>
                    </p>
                    <a class="branches-leaderships__link" href="tel:<?php echo htmlspecialchars($phone_raw); ?>">
                        <?php echo htmlspecialchars($phone_formatted); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>
