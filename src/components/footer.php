<?php
// src/components/footer.php
?>

<footer class="footer" id="footer">
    <div class="container">
        <div class="footer__main">

            <div class="footer__col footer__col--brand">
                <h2 class="footer__title"><?= t('footer_institute_name') ?? 'Інститут охорони ґрунтів України' ?></h2>
                <address class="footer__address">
                    <ul class="footer__contacts-list">
                        <li class="footer__contacts-item">
                            <span class="footer__contacts-label">Адреса:</span>
                            <a href="https://maps.app.goo.gl/op2ercbCK8irgzFv7" target="_blank" rel="noopener noreferrer" title="Відкрити в Google Maps">
                                пров. Сеньківський (Бабушкіна), 3, корп. 3, м. Київ, 03190
                            </a>
                        </li>
                        <li class="footer__contacts-item">
                            <span class="footer__contacts-label">Телефони:</span>
                            <div class="footer__phones">
                                <a href="tel:+380443565321" aria-label="Зателефонувати (044) 356-53-21">(044) 356-53-21</a>
                                <a href="tel:+380443565325" aria-label="Зателефонувати (044) 356-53-25">(044) 356-53-25</a>
                                <a href="tel:+380443565324" aria-label="Зателефонувати (044) 356-53-24">(044) 356-53-24</a>
                            </div>
                        </li>
                        <li class="footer__contacts-item">
                            <span class="footer__contacts-label">Email:</span>
                            <a href="mailto:info@iogu.gov.ua" aria-label="Написати нам на info@iogu.gov.ua">info@iogu.gov.ua</a>
                        </li>
                    </ul>
                </address>
            </div>

            <div class="footer__col footer__col--nav">
                <h3 class="footer__subtitle">Навігація</h3>
                <nav class="footer__nav" aria-label="Нижня навігація">
                    <ul class="footer__nav-list">
                        <li><a href="<?= base_url('/') ?>" class="footer__nav-link">Головна</a></li>
                        <li><a href="<?= base_url('/history') ?>" class="footer__nav-link"> <?= t('header.menu.history') ?></a></li>
                        <li><a href="<?= base_url('/leadership') ?>" class="footer__nav-link"><?= t('header.menu.leadership') ?></a></li>
                        <li><a href="<?= base_url('/structure') ?>" class="footer__nav-link"><?= t('header.menu.structure') ?></a></li>
                        <li><a href="<?= base_url('/edition') ?>" class="footer__nav-link"><?= t('header.menu.edition') ?></a></li>
                        <li><a href="<?= base_url('/news') ?>" class="footer__nav-link"><?= t('header.menu.press_center') ?></a></li>
                        <li><a href="<?= base_url('/branches') ?>" class="footer__nav-link"><?= t('header.menu.branches') ?></a></li>
                        <li><a href="<?= base_url('/contacts') ?>" class="footer__nav-link"><?= t('header.menu.contact') ?></a></li>
                    </ul>
                </nav>
            </div>

            <div class="footer__col footer__col--info">
                <h3 class="footer__subtitle">Корисна інформація</h3>
                <nav class="footer__nav" aria-label="Додаткова інформація">
                    <ul class="footer__nav-list">
                        <li><a href="<?= base_url('/npa') ?>" class="footer__nav-link"><?= t('header.menu.npa') ?></a></li>
                        <li><a href="<?= base_url('/ground_partner') ?>" class="footer__nav-link"><?= t('header.menu.gr_partner') ?></a></li>
                        <li><a href="<?= base_url('/anticorruption') ?>" class="footer__nav-link"><?= t('header.menu.anticorruption') ?></a></li>
                        <li><a href="https://www.old.iogu.gov.ua/" target="_blank" rel="noopener noreferrer" class="footer__nav-link">Стара версія сайту</a></li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>

    <div class="footer__bottom">
        <div class="container">
            <div class="footer__bottom-wrapper">
                <div class="footer__copyright">
                    <p>&copy; <?= date('Y') ?> <?= t('footer_institute_name') ?? 'Інститут охорони ґрунтів України' ?>. Всі права захищено.</p>
                    <p class="footer__license">
                        Весь контент доступний за ліцензією
                        <a href="https://creativecommons.org/licenses/by/4.0/deed.uk" target="_blank" rel="noopener noreferrer">Creative Commons Attribution 4.0 International</a>, якщо не зазначено інше.
                    </p>
                </div>

                <div class="footer__dev">
                    <span><?= t('footer.developer') ?? 'Розробка:' ?></span>
                    <a href="https://sshaprans.github.io/portfolio/" target="_blank" rel="noopener noreferrer" class="footer__dev-link" aria-label="Портфоліо розробника">
                        <svg width="24" height="24" viewBox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M36.7 8.9C33.3 3.8 27.9 0.6 21.8 0C15.6 -0.4 9.8 1.6 5.6 6C3.1 8.6 1.4 11.6 0.4 15.2C0 16.8 0 17.4 0 20C0 22.6 0 23.2 0.5 24.9C2.4 32.1 8 37.7 15.3 39.5C16 39.6 16.7 39.8 17.1 39.9C18.4 40 21.8 40 23.3 39.7C29.5 38.6 34.9 34.7 37.8 29.2C41 22.9 40.6 14.8 36.7 8.9ZM35.9 24.2C34.2 30.2 29.3 34.7 23.2 36C13.5 38 4.1 30.7 3.5 20.8C3.1 14.4 7 7.9 13 5.1C15.3 4 17.1 3.6 19.8 3.6C22.5 3.6 23.9 3.9 26.3 4.8C30.9 6.7 34.5 10.9 35.9 15.9C36.4 18 36.4 22.1 35.9 24.2Z"></path>
                        </svg>
                        SS <?= date('Y') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="overlay scroll-hidden"></div>
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="modal-close">×</span>
        <form class="form--modal" id="form--modal">
            <h3 class="form__title"><?= t('popup_consult_title') ?></h3>
            <div class="label-wrapper">
                <label class="input-text" for="name">
                    <input id="name" name="name" placeholder="<?= t('popup_placeholder_name') ?>" required="" type="text"/>
                </label>
                <label class="input-text" for="phone">
                    <input id="phone" name="phone" placeholder="<?= t('popup_placeholder_phone') ?>" required="" type="tel"/>
                </label>
            </div>
            <div class="modal-btn__wrapper">
                <button class="link-btn link-btn--pack" type="submit">
                    <span class="link-btn__text link-btn__text--pack"><?= t('popup_consult_submit') ?></span>
                </button>
                <p class="abo"><?= t('or') ?></p>
                <a class="link-btn link-btn--pack" href="tel:+380443565321">
                    <span class="link-btn__text link-btn__text--pack"><?= t('popup_consult_call') ?></span>
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<?php
if (isset($page_scripts) && is_array($page_scripts)) {
    foreach ($page_scripts as $script) {
        echo '<script src="' . asset($script) . '"></script>';
    }
}
?>

</body>
</html>