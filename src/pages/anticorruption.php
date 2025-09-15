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
        <h3 class="section-title" >
            Антикорупційна сторінка
        </h3>
        <div class="anticor-wrap">
            <div class="anticor-text">
                <p class="anticor-title">
                    У разі виявлення корупційної діяльності, або діяльності з ознаками корупції, обовʼязково повідомляти!
                </p>
                <p class="anticor-p">
                    Контакти:
                </p>
                <a href="tel:0443565321" class="anticor-link">
                    (044)-35-653-21
                </a>
                <a href="mailto:nfo@iogu.gov.ua" class="anticor-link">
                    nfo@iogu.gov.ua
                </a>

            </div>
            <div class="anticor-doc">
                <a href="/img/anticorruption/anticorruption.pdf" class="link__document link__document--branches" target="_blank">
                    <img src="/img/anticorruption/anticorruption.png" alt="Свідоцтво про відповідність системи вимірювань № 0266 від 27.12.2024 р." class="link__document-img">
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
<!--    <hr>-->
<!--    <section id="main-down">-->
<!--        <div class="container">-->
<!--            <div class="main-down-wrapper">-->
<!--                <div class="info-port main-down-box">-->
<!--                    <h3 class="section-title-down" data-i18n="information_portals">Інформаційні портали</h3>-->
<!--                    <ul class="main-down-list">-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://nsdi.gov.ua/login?redirect=/" data-i18n="geoportal">Портал геопросторових даних</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://e.land.gov.ua/" data-i18n="e_services">Е-послуги Держгеокадастру</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://map.land.gov.ua/?cc=3461340.1719504707,6177585.367221659&z=6.5&l=kadastr&bl=ortho10k_all" data-i18n="public_cadastral_map">Публічна кадастрова карта</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--                <div class="covv main-down-box">-->
<!--                    <h3 class="section-title-down" data-i18n="authorities_and_agencies">Відомства та ЦОВВ</h3>-->
<!--                    <ul class="main-down-list">-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://land.gov.ua/" data-i18n="geodesy_service">Державна служба України з питань геодезії, картографії та кадастру</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://darg.gov.ua/" data-i18n="agency_melioration">Державне агентство меліорації та рибного господарства України</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--                <div class="useful main-down-box">-->
<!--                    <h3 class="section-title-down" data-i18n="useful_links">Корисні посилання</h3>-->
<!--                    <ul class="main-down-list">-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://www.president.gov.ua/" data-i18n="president_of_ukraine">Президент України</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://www.kmu.gov.ua/" data-i18n="government_portal">Урядовий портал</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://www.rada.gov.ua/" data-i18n="ukraine_parliament">Верховна Рада України</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://nads.gov.ua/" data-i18n="national_agency">Національне агенство України з питань державної служби</a>-->
<!--                        </li>-->
<!--                        <li class="main-down-list-item">-->
<!--                            <a href="https://minagro.gov.ua/" data-i18n="ministry_agriculture">Міністерство аграрної політики та продовольства України</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--    </section>-->
</div>

    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
