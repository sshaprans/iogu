<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'ground_partner';

$page_title = 'Грунтове партнерство | Інститут охорони ґрунтів України';
//$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <section class="gp_header">
            <img class="gp_header__img gp_header__img--not-mobile" src="http://media.iogu.gov.ua/img/ground_partner/main_img.jpg" alt="partner">
            <img class="gp_header__img gp_header__img--mobile" src="http://media.iogu.gov.ua/img/ground_partner/main_img_mobile.jpg" alt="partner">
            <div class="gp_header__content">
                <div class="gp_header__ui">
                    <a href="https://uasp.com.ua/asocziacziya/ " class="gp_header__logo" target="_blank" title="Українське ґрунтове партнерство">
                        <img class="gp_header__logo" src="http://media.iogu.gov.ua/img/ground_partner/grunt_partner.png" alt="Українське ґрунтове партнерство" >
                    </a>
                    <h2 class="gp_header__text gp_header__text--mobile">
                        Українське ґрунтове партнерство (УҐП) — національна платформа, що об’єднує урядові та наукові зусилля для сталого управління й збереження ґрунтів, створена за підтримки ФАО.
                    </h2>
                    <a href="https://uasp.com.ua/asocziacziya/" class="link-btn" target="_blank">
                        <span class="link-btn__text">Перейти на сайт</span>
                    </a>
                </div>
                <h2 class="gp_header__text gp_header__text--not-mobile">
                    Українське ґрунтове партнерство (УҐП) — національна платформа, що об’єднує урядові та наукові зусилля для сталого управління й збереження ґрунтів, створена за підтримки ФАО.
                </h2>
            </div>
        </section>
        <section class="gp_partners">
            <div class="container">
                <h3 class="sale-block__title new-main-title">Засновники й мета діяльності</h3>
                <p class="new_list-title">
                    Засновниками УҐП виступили провідні наукові інститути та державні установи, що працюють над сталим управлінням ґрунтовими ресурсами.
                </p>
                <ul class="gp_partners__list">
                    <li class="gp_partners__list__item ">
                        <a href="https://www.iogu.gov.ua/" class="gp_partners__list__link" title="ДЕРЖАВНА УСТАНОВА ”ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ”">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/logo.svg" alt="iogu">
                        </a>
                    </li>
                    <li class="gp_partners__list__item ">
                        <a href="https://agroeco.org.ua/" class="gp_partners__list__link" title="Інститут агроекології і природокористування Національної академії аграрних наук України" target="_blank">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/ground_partner/2.png" alt="naan">
                        </a>
                    </li>
                    <li class="gp_partners__list__item ">
                        <a href="https://igim.org.ua/" class="gp_partners__list__link" title="Інституту водних проблем і меліорації Національної академії аграрних наук України" target="_blank">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/ground_partner/3.png" alt="ІВПіМ">
                        </a>
                    </li>
                    <li class="gp_partners__list__item ">
                        <a href="https://zemlerobstvo.com/" class="gp_partners__list__link" title="Національний науковий центр “Інститут землеробства Національної академії аграрних наук”" target="_blank">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/ground_partner/4.png" alt="ІЗ НААН">
                        </a>
                    </li>
                    <li class="gp_partners__list__item ">
                        <a href="http://oov.medved.kiev.ua/" class="gp_partners__list__link" title="Державно підприємство «Науковий центр превентивної токсикології, харчової та хімічної безпеки імені академіка Л. І. Медведя Міністерства Охорони Здоров’я України»" target="_blank">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/ground_partner/5.png" alt="ЕКОГІНТОКС">
                        </a>
                    </li>
                    <li class="gp_partners__list__item ">
                        <a href="https://issar.com.ua/" class="gp_partners__list__link" title="Національний науковий центр «Інститут ґрунтознавства та агрохімії імені О.Н. Соколовського» Національної академії аграрних наук України" target="_blank">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/ground_partner/6.png" alt="ННЦ «ІҐА імені О.Н. Соколовського»">
                        </a>
                    </li>
                    <li class="gp_partners__list__item ">
                        <a href="https://ismav.com.ua/" class="gp_partners__list__link" title="Інститут сільськогосподарської мікробіології та агропромислового виробництва Національної академії аграрних наук України" target="_blank">
                            <img class="gp_partners__list__img" src="http://media.iogu.gov.ua/img/ground_partner/7.png" alt="IСМАВ НААН">
                        </a>
                    </li>
                </ul>
                <h4 class="new_list-title">
                    Асоціація функціонує під егідою Глобального ґрунтового партнерства та покликана:
                </h4>
                <ul class="precision-banner__content__text__list">
                    <li class="precision-banner__content__text__list__item">
                        Координувати зусилля науковців, державних служб і громадськості.
                    </li>
                    <li class="precision-banner__content__text__list__item">
                        Формувати базу моніторингу стану ґрунтів, продуктивності земель і запасів вуглецю за міжнародними стандартами.
                    </li>
                    <li class="precision-banner__content__text__list__item">
                        Донести до кожного українця правильні наукові знання про ґрунти.
                    </li>
                </ul>
            </div>
        </section>
        <section class="green-boxes">
            <div class="container">
                <div class="green-box">
                    <h4 class="green-box__title">
                        Головне завдання Асоціації — повернути ґрунту статус національного багатства України.
                    </h4>
                    <ul class="green-box__list">
                        <li class="green-box__list__item">
                            Розширення обізнаності населення щодо важливості родючих земель.
                        </li>
                        <li class="green-box__list__item">
                            Впровадження стратегії сталого використання землі.
                        </li>
                        <li class="green-box__list__item">
                            Популяризацію науково обґрунтованих методів догляду за ґрунтами.
                        </li>
                    </ul>
                </div>
                <div class="green-box">
                    <h4 class="green-box__title" >
                        Україна зобов’язалася досягти нейтрального рівня деградації ґрунтів до 2030 року, підписавши Конвенцію ООН по боротьбі з опустелюванням.
                    </h4>
                    <ul class="green-box__list">
                        <li class="green-box__list__item">
                            Охорону родючих земель.
                        </li>
                        <li class="green-box__list__item">
                            Комплексне управління природними ресурсами.
                        </li>
                        <li class="green-box__list__item">
                            Виконання національного робочого плану боротьби з опустелюванням.
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="add-info">
            <div class="container">
                <h4 class="add-info__title" >
                    Державна установа «Інститут охорони ґрунтів України» є повноправним членом:
                </h4>
                <ul class="add-info__list">
                    <li class="add-info__item">
                        <a href="https://uasp.com.ua/asocziacziya/" class="add-info__link" target="_blank" title="Українське ґрунтове Товариство">
                            Українське ґрунтове Товариство
                        </a>
                    </li>
                    <li class="add-info__item">
                        <a href="http://media.iogu.gov.ua/literature/gp/Global_Soil-Partnership.pdf" class="add-info__link" target="_blank" title="Більше інформації">
                            Глобальне ґрунтове партнерство (Global Soil Partnership)
                        </a>
                    </li>
                    <li class="add-info__item">
                        <a href="http://media.iogu.gov.ua/literature/gp/УТҐА.pdf" class="add-info__link" target="_blank" title="Більше інформації">
                            Громадська організація «Українське товариство ґрунтознавців та агрохіміків» ГО («УТҐА»)
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        <section class="add-info">
            <div class="container">
                <h4 class="add-info__title" >
                    Науково-методична література:
                </h4>
                <ul class="add-info__list">
                    <li class="add-info__item">
                        <a href="http://media.iogu.gov.ua/literatura/gp/Методичні%20рекомендації.pdf" class="add-info__link" target="_blank" title="Переглянути">
                            Методичні рекомендації щодо організації території сільськогосподарських підприємств на еколого-ландшафтній основі / Камінський В.Ф., Янсе Л.А., Коломієць Л.П., Шевченко І.П., Повидало В.М., Штакал В.М., Шквир М.І. – Вінниця: ТОВ «ТВОРИ», 2020. – 64 с.
                        </a>
                    </li>
                    <li class="add-info__item">
                        <a href="http://media.iogu.gov.ua/literature/gp/монографія%202023%20verstka_malynovska_topress.pdf" class="add-info__link" target="_blank" title="Переглянути">
                            Малиновська І.М., Ткаченко М.А. Мікробіологічні процеси у сірому лісовому ґрунті: монографія. Київ: «Аграрна наука», 2023. 120 с.
                        </a>
                    </li>
                    <li class="add-info__item">
                        <a href="http://media.iogu.gov.ua/literature/gp/Хімічна%20меліорація%20кислих%20грунтів%2006.07,2020.pdf" class="add-info__link" target="_blank" title="Переглянути">
                            Ткаченко М.А., Кондратюк І.М., Борис Н.Є. Хімічна меліорація кислих ґрунтів [Монографія]. Вінниця, ТОВ «ТВОРИ», 2019. 318 с.
                        </a>
                    </li>
                    <li class="add-info__item">
                        <a href="http://media.iogu.gov.ua/literature/gp/zvit.pdf" class="add-info__link" target="_blank" title="Переглянути">
                            Звіт про виконання Листа-Угоди між офісом Продовольчої та Сільськогосподарської організації Об'єднаних націй ("ФАО") та державної установи "Інститут охорони ґрунтів України" за темою "Посилення потенціалу зі збору та узгодження агрохімічних даних про ґрунти для подальшої автоматичної обробки: приклад лісостепової зони в Україні"
                        </a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
