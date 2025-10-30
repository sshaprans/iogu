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
                <a class="branches__new" href="./center_vn.php" >Вінниця</a>
                <a class="branches__new" href="./center_vl.php" >Волинь</a>
                <a class="branches__new" href="./center_dnipro.php" >Дніпро</a>
                <a class="branches__new" href="./center_donetsk.php" >Донецьк</a>
                <a class="branches__new" href="./center_zt.php" >Житомир</a>
                <a class="branches__new" href="./center_zk.php" >Закарпаття</a>
                <a class="branches__new" href="./center_zp.php" >Запоріжжя</a>
                <a class="branches__new" href="./center_si_if.php" >Івано-Франківськ</a>
                <a class="branches__new" href="./center_kr.php" >Кіровоградська</a>
                <a class="branches__new" href="./center_lg.php" >Луганськ</a>
                <a class="branches__new" href="./branches/lviv.html" >Львів</a>
                <a class="branches__new" href="./branches/mukolaiv.html" >Миколаїв</a>
                <a class="branches__new" href="./branches/odesa.html" >Одеса</a>
                <a class="branches__new" href="./branches/poltava.html" >Полтава</a>
                <a class="branches__new" href="./branches/rivne.html" >Рівне</a>
                <a class="branches__new" href="./branches/sumy.html" >Суми</a>
                <a class="branches__new" href="./branches/ternopil.html" >Тернопіль</a>
                <a class="branches__new" href="./branches/harkiv.html" >Харків</a>
                <a class="branches__new" href="./branches/herson.html" >Херсон</a>
                <a class="branches__new" href="./branches/hmelnutskiy.html" >Хмельницький</a>
                <a class="branches__new" href="./branches/cherkasy.html" >Черкаси</a>
                <a class="branches__new" href="./branches/chernivtsi.html" >Чернівці</a>
                <a class="branches__new" href="./branches/chernigiv.html" >Чернігів</a>
                <a class="branches__new" href="./branches/qrım.html" >АР Крим</a>
            </div>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
