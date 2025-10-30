<?php
/**
 * Компонент "Контакти філіалу".
 *
 * @var string $contacts_title   - Заголовок секції (напр. "Звертатися за контактами").
 * @var array  $contacts_list    - Масив контактів. Кожен елемент - асоціативний масив,
 * який *обов'язково* має ключ 'type'.
 * - type 'phone': 'description', 'phone_raw', 'phone_formatted'
 * - type 'email': 'email'
 * - type 'address': 'text', 'url'
 * @var string $contacts_map_src - URL для вбудованої карти (iframe src).
 */

$contacts_title = $contacts_title ?? 'Контакти';
$contacts_list = $contacts_list ?? [];
$contacts_map_src = $contacts_map_src ?? '';
?>

<article class="address_branch">
    <div class="address_branch__text">
        <h3 class="section-title-sup section-title-sup--contact">
            <?php echo htmlspecialchars($contacts_title); ?>
        </h3>
        <div class="address_branch__contacts">
            <ul class="address_branch__list">

                <?php foreach ($contacts_list as $item) :

                    if (!isset($item['type'])) {
                        continue;
                    }
                    ?>
                    <li class="address_branch__item">
                        <?php
                        switch ($item['type']) {

                            case 'phone':
                                ?>
                                <span class="address_branch__item__desc">
                                    <?php echo htmlspecialchars($item['description'] ?? 'Телефон'); ?>
                                </span>
                                <a class="address_branch__link" href="tel:<?php echo htmlspecialchars($item['phone_raw'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($item['phone_formatted'] ?? ''); ?>
                                </a>
                                <?php break;

                            case 'email':
                                ?>
                                <a href="mailto:<?php echo htmlspecialchars($item['email'] ?? ''); ?>" class="address_branch__link">
                                    <?php echo htmlspecialchars($item['email'] ?? 'Email'); ?>
                                </a>
                                <?php break;

                            case 'address':
                                ?>
                                <a class="address_branch__link" target="_blank" href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>">
                                    <?php echo htmlspecialchars($item['text'] ?? 'Адреса'); ?>
                                </a>
                                <?php break;
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php if (!empty($contacts_map_src)) : ?>
        <iframe class="address_branch__map"
                src="<?php echo htmlspecialchars($contacts_map_src); ?>"
                width="600" height="300" style="border:0;"
                allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    <?php endif; ?>
</article>
