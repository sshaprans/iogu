<?php
require_once __DIR__ . '/../core/i18n.php';
require_once __DIR__ . '/../core/asset-loader.php';
require_once __DIR__ . '/../components/header.php';

?>

<main id="main">
    <div class="main-container">
        <div class="container container--contacts">
            <h1 class="main-title section-title--contacts">
                <?= t('contacts.title') ?>
            </h1>
            <div class="contacts">
                <p class="section-title-sup">
                    <?= t('contacts.admin_title') ?>
                </p>
                <p class="p-contact"><span><?= t('contacts.address_label') ?></span> <?= t('contacts.address_value') ?></p>
                <p class="p-contact">
                    <span><?= t('contacts.phone_label') ?></span> <a href="tel:0443565321">(044) 356-53-21</a> (<?= t('contacts.reception') ?>)
                    <a href="tel:+380930138074" style="display: inline-block; margin: 10px 0 0 70px">(093)-013-80-74 <span>Viber, WhatsApp</span></a>
                </p>
                <p class="p-contact">
                    <span><?= t('contacts.hotline_label') ?></span> <a href="tel:0443565326">(044) 356-53-21</a> (<?= t('contacts.hotline_days') ?>)
                </p>
                <p class="p-contact">
                    <span class="is-backup-email">e-mail:</span> <a href="mailto:ioguinform@gmail.com" class="is-backup-email"><?= t('contacts.backup_email_main') ?></a>
                </p>
                <p class="p-contact">
                    <span>e-mail:</span> <a href="mailto:info@iogu.gov.ua">info@iogu.gov.ua </a>
                </p>
                <p class="section-title-sup">
                    <?= t('contacts.prod_title') ?>
                </p>
                <p class="p-contact">
                    <?= t('contacts.certificate') ?> <a href="/assets/files/chabani2024.pdf" target="_blank"><?= t('contacts.certificate_link') ?></a>
                </p>
                <p class="p-contact"><span><?= t('contacts.address_label') ?></span>
                    <?= t('contacts.address_value_prod') ?>
                </p>
                <p class="p-contact">
                    <span><?= t('contacts.phone_label') ?></span> <a href="tel:0443576980">(044) 357-69-80</a>
                </p>
                <p class="p-contact">
                    <span class="is-backup-email">e-mail:</span> <a href="mailto:kiev@iogu.gov.ua" class="is-backup-email"><?= t('contacts.backup_email_prod') ?></a>
                </p>
                <p class="p-contact">
                    <span>e-mail:</span> <a href="mailto:kiev@iogu.gov.ua">kiev@iogu.gov.ua </a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../components/footer.php'; ?>

