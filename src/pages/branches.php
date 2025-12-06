<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'branches';

$page_title = 'Центри державної установи | Інститут охорони ґрунтів України';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <div class="container">
            <h1 class="main-title">
                Центри державної установи
            </h1>
            <div class="wrapper_links wrapper_links--branches">
                <a class="branches__new" href="./center_vn" >Вінниця</a>
                <a class="branches__new" href="./center_vl" >Волинь</a>
                <a class="branches__new" href="./center_dnipro" >Дніпро</a>
                <a class="branches__new" href="./center_donetsk" >Донецьк</a>
                <a class="branches__new" href="./center_zt" >Житомир</a>
                <a class="branches__new" href="./center_zk" >Закарпаття</a>
                <a class="branches__new" href="./center_zp" >Запоріжжя</a>
                <a class="branches__new" href="./center_si_if" >Івано-Франківськ</a>
                <a class="branches__new" href="./center_kr" >Кіровоградська</a>
                <a class="branches__new" href="./center_lg" >Луганськ</a>
                <a class="branches__new" href="./center_vl" >Львів</a>
                <a class="branches__new" href="./center_mk" >Миколаїв</a>
                <a class="branches__new" href="./center_od" >Одеса</a>
                <a class="branches__new" href="./center_pl" >Полтава</a>
                <a class="branches__new" href="./center_ri" >Рівне</a>
                <a class="branches__new" href="./center_cy" >Суми</a>
                <a class="branches__new" href="./center_tr" >Тернопіль</a>
                <a class="branches__new" href="./center_kh" >Харків</a>
                <a class="branches__new" href="./center_hr" >Херсон</a>
                <a class="branches__new" href="./center_hm" >Хмельницький</a>
                <a class="branches__new" href="./center_ch" >Черкаси</a>
                <a class="branches__new" href="./center_che" >Чернівці</a>
                <a class="branches__new" href="./center_cher" >Чернігів</a>
                <a class="branches__new" href="./center_ar" >АР Крим</a>
            </div>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
