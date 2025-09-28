<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'news';

$page_title = 'Новини | Інститут охорони ґрунтів України';
//$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <section id="news">
            <div class="container">
                <div class="news-wrapper news-wrapper--news">
                    <div class="npa-wrapper npa-wrapper--news">
                        <h3 class="main-title">
                            Зміни НПА
                        </h3>
                        <div class="npa npa--news">
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/2698-20#n132">
                                    <p class="npa-link-title"> Про державний контроль за використанням та охороною земель</p>
                                    <p class="npa-link-text">№ 2698-IX від 19.10.2022</p>
                                </a>
                            </div>
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/2849-20#n2696">
                                    <p class="npa-link-title"> Про землеустрій</p>
                                    <p class="npa-link-text">№ 2849-IX від 13.12.2022</p>
                                </a>
                            </div>
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/2079-20#n388">
                                    <p class="npa-link-title"> Про меліорацію земель</p>
                                    <p class="npa-link-text">№ 2079-IX від 17.02.2022</p>
                                </a>
                            </div>
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/3050-20#n193">
                                    <p class="npa-link-title">Про оренду землі</p>
                                    <p class="npa-link-text">№ 3050-IX від 11.04.2023</p>
                                </a>
                            </div>
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/3050-20#n189">
                                    <p class="npa-link-title">Про охорону земель</p>
                                    <p class="npa-link-text">№ 3050-IX від 11.04.2023</p>
                                </a>
                            </div>
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/2717-20#n6">
                                    <p class="npa-link-title"> Про охорону навколишнього природного середовища</p>
                                    <p class="npa-link-text">№ 2717-IX від 03.11.2022</p>
                                </a>
                            </div>
                            <div class="npa-link npa-link--news">
                                <a href="https://zakon.rada.gov.ua/laws/show/3050-20#n183">
                                    <p class="npa-link-title">ЗЕМЕЛЬНИЙ  КОДЕКС  УКРАЇНИ</p>
                                    <p class="npa-link-text">№ 3050-IX від 11.04.2023</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="magazine_wrapper">
                        <h3 class="main-title">
                            Журнал
                        </h3>
                        <div>
                            <a href="<?= base_url('/news2025') ?>" class="magazine magazine--2025">
                                <h6 class="magazine_title">2025</h6>
                            </a>

                            <a href="<?= base_url('/news2024') ?>" class="magazine magazine--2024">
                                <h6 class="magazine_title">2024</h6>
                            </a>
                            <a href="<?= base_url('/news2023') ?>" class="magazine">
                                <h6 class="magazine_title">2023</h6>
                            </a>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
