<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'contacts';

$page_title = 'Контакти | Інститут охорони ґрунтів України';
//$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <div class="container container--contacts">
            <h1 class="main-title section-title--contacts">
                Контакти
            </h1>
            <div class="contacts">
                <p class="section-title-sup">
                    Адміністрація:
                </p>
                <p class="p-contact"><span>Юридична та фактична адреса:</span> 03190, м. Київ, пров. Сеньківський (Бабушкіна), 3, корпус 3.</p>
                <p class="p-contact">
                    <span>Тел.:</span> <a href="tel:0443565321">(044) 356-53-21</a> (приймальня)
                    <a href="tel:+380930138074" style="display: inline-block; margin: 10px 0 0 70px">(093)-013-80-74 <span>Viber, WhatsApp</span></a>
                </p>
                <p class="p-contact">
                    <span>ГАРЯЧА ЛІНІЯ:</span> <a href="tel:0443565326">(044) 356-53-21</a> (щосереди, крім святкових днів)
                </p>
                <p class="p-contact">
                    <span style="color: red">e-mail:</span> <a href="mailto:ioguinform@gmail.com " style="color: red">ioguinform@gmail.com - резервна пошта</a>
                </p>
                <p class="p-contact">
                    <span>e-mail:</span> <a href="mailto:info@iogu.gov.ua">info@iogu.gov.ua </a>
                </p>
                <p class="section-title-sup">
                    Виробниче приміщення:
                </p>
                <p class="p-contact">
                    Свідоцтво про визнання технічної компетентності <a href="https://media.iogu.gov.ua/img/chabani2024.pdf" >№ 0265 від 27.12.2024 р.</a>
                </p>
                <p class="p-contact"><span>Адреса:</span>
                    08162, Київська область, Києво-Святошинський район, смт Чабани, вул. Машинобудівників, 2б
                </p>
                <p class="p-contact">
                    <span>Тел.:</span> <a href="tel:0443576980">(044) 357-69-80</a>
                </p>
                <p class="p-contact">
                    <span style="color: red">e-mail:</span> <a href="mailto:kiev@iogu.gov.ua " style="color: red">kievodptc@ukr.net - резервна пошта</a>
                </p>
                <p class="p-contact">
                    <span>e-mail:</span> <a href="mailto:kiev@iogu.gov.ua ">kiev@iogu.gov.ua </a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
