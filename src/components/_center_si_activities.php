<?php
/**
 * Компонент "Основні напрями діяльності".
 * @var string $activities_title - Заголовок секції.
 * @var array  $activities_list  - Масив рядків (пунктів списку).
 */

$activities_title = $activities_title ?? 'Основні напрями';
$activities_list = $activities_list ?? [];
?>

<section class="text_branches branches-serves container">
    <h3 class="section-title-sup section-title-sup--branches">
        <?php echo htmlspecialchars($activities_title); ?>
    </h3>
    <ul class="branches-serves__list">
        <?php foreach ($activities_list as $item) : ?>
            <li class="branches-server__item">
                <?php echo htmlspecialchars($item); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>