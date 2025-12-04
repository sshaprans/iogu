<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'anticorruption';

$page_title = 'Історія створення | Інститут охорони ґрунтів України';
//$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <div class="container">
            <section class="anticor">
                <h3 class="section-title">
                    Антикорупційна сторінка
                </h3>
                <div class="anticor-wrap">
                    <div class="anticor-text">
                        <p class="anticor-title">
                            У разі виявлення корупційної діяльності, або діяльності з ознаками корупції, обовʼязково
                            повідомляти!
                        </p>
                        <p class="anticor-p">
                            Контакти:
                        </p>
                        <a href="tel:0443565321" class="anticor-link">
                            (044)-35-653-21
                        </a>
                        <a href="mailto:info@iogu.gov.ua" class="anticor-link">
                            info@iogu.gov.ua
                        </a>

                    </div>
                    <div class="anticor-doc">
                        <a href="https://media.iogu.gov.ua/img/anticorruption/anticorruption.pdf" class="link__document link__document--branches"
                           target="_blank">
                            <img src="https://media.iogu.gov.ua/img/anticorruption/anticorruption.png"
                                 alt="Свідоцтво про відповідність системи вимірювань № 0266 від 27.12.2024 р."
                                 class="link__document-img">
                            <div class="link__document__wrapper">
                                <p class="title__hover-link">
                                    Переглянути Програму
                                </p>
                            </div>
                        </a>
                        <p class="desc-for-link__document">
                            * Антикорупційна програма
                        </p>
                    </div>

                </div>
            </section>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
