<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'structure';

$page_title = 'Історія створення | Інститут охорони ґрунтів України';
//$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»';

ob_start();
?>
<main id="main">
    <div class="main-container main-container--structure">
        <div class="container">
            <h1 class="section-title section-title--structure">
                Структура
                ДЕРЖАВНОЇ УСТАНОВИ <br>
                «ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ»
            </h1>
            <section class="structure__wrapper">
                <p class="structure__text">
                    <span class="section__span"><i>28 лютого 2024 року </i></span> Міністерством аграрної політики та продовольства України затверджено організаційну структуру державної установи <span class="section__span">«Інститут охорони ґрунтів України»</span>, яка вводиться в дію з <span class="section__span"><i> 01 травня 2024 року.</i></span>.
                </p>
                <a href="https://media.iogu.gov.ua/img/structure/structure.pdf" class="link__document" target="_blank">
                    <h5 class="link__document-name">
                        Затверджена організаційна структура
                    </h5>
                    <img src="https://media.iogu.gov.ua/img/structure/structure.jpg" alt="" class="link__document-img">
                    <div class="link__document__wrapper">
                        <p class="title__hover-link">
                            Переглянути документ
                        </p>
                    </div>
                </a>
            </section>
            <a class="structure-link" href="../branches_of_du.html" >
                24 Центри ДУ «Держґрунтохорона»
            </a>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
