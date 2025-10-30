<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'center_si';

$page_title = 'Івано-Франківський регіональний центр державної установи ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <h1 class="main-title main-title--branch">
            <?= $page_title ?>
        </h1>

        <?php
        $bgLink = 'https://uk.wikipedia.org/wiki/%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA#/media/%D0%A4%D0%B0%D0%B9%D0%BB:Ratush-01.jpg';
        $history_slides = [
                [
                        'title' => 'Історія',

                        'text' => 'Історія Івано-Франківського регіонального центру розпочалася у 1964 році зі створення 
                зональної агрохімічної лабораторії як самостійної виробничо-наукової установи при Науковому інституті 
                землеробства і тваринництва західних регіонів України'
                ],
                [
                        'text' => 'За 60 років діяльності структура пройшла ряд реорганізацій. З травня 2024 року перейменовано 
                на Івано-Франківський регіональний центр державної установи «Інститут охорони ґрунтів України».'
                ],
                [
                        'text' => 'Івано-Франківський регіональний центр ДУ «Держґрунтохорона» є державною науково-виробничою 
                організацією, яка здійснює політику у сфері збереження, відтворення та охорони родючості ґрунтів, 
                ведення їх державного моніторингу.'
                ],
                [
                        'text' => 'Це єдина державна установа в області, яка проводить комплекс наукових досліджень ґрунтів з 
                видачею агрохімічного паспорта поля, земельної ділянки за показниками родючості та екологічної безпеки 
                ґрунтового покриву.'
                ],

        ];
        include __DIR__ . '/../components/_center_si_banner.php';

        $leadership_title = 'Керівництво центру';
        $leadership_staff = [
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/iv_frank_der.jpg',
                        'name' => 'Пятковська Ірина Олегівна',
                        'position' => 'в.о. Директора',
                        'phone_raw' => '+380665224062',
                        'phone_formatted' => '+38(066)-522-40-62'
                ],
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/iv_frank_engen.jpg',
                        'name' => 'Матвійчук Ольга Володимирівна',
                        'position' => 'Головний інженер-ґрунтознавець',
                        'phone_raw' => '+380994432470',
                        'phone_formatted' => '+38(099)-443-24-70'
                ],
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/iv_frank_laba.jpg',
                        'name' => 'Налужний Руслан Ігорович',
                        'position' => 'Зав. лаб. моніторингу земель',
                        'phone_raw' => '+380979533145',
                        'phone_formatted' => '+38(097)-953-31-45'
                ],
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/iv_frank_acco.jpg',
                        'name' => 'Сончак Алла Анатоліївна',
                        'position' => 'Зав. лаб. аналітичного забезпечення',
                        'phone_raw' => '+380678526329',
                        'phone_formatted' => '+38(067)-852-63-29'
                ]
        ];
        include __DIR__ . '/../components/_center_si_head.php';

        $activities_title = 'Основні напрями діяльності';
        $activities_list = [
                'моніторинг ґрунтів на землях сільськогосподарського призначення;',
                'виготовлення та видача агрохімічних паспортів полів, земельних ділянок;',
                'розроблення проєктно-кошторисної документації на вапнування кислих ґрунтів;',
                'проведення розрахунків потреби добрив під планові врожаї сільськогосподарських культур;',
                'визначення відповідності агрохімікатів вимогам державних стандартів та технічних умов;',
                'виконання агрохімічних аналізів місцевих органічних добрив, торфопродукції, ґрунтосумішей та хімічних меліорантів;',
                'визначення якості сільськогосподарської продукції та сировини;',
                'надання науково-консультативних послуг землекористувачам всіх форм власності.'
        ];
        include __DIR__ . '/../components/_center_si_activities.php';

        $contacts_title = 'Звертатися за контактами';
        $contacts_map_src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2621.52120900556!2d24.7169697!3d48.9245137!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4730c16d9f9a7dbb%3A0xf9961309e059ffe!2z0YPQuy4g0JzQuNGF0LDQuNC70LAg0JzRg9C70YvQutCwLCAyLCDQmNCy0LDQvdC-LdCk0YDQsNC90LrQvtCy0YHQuiwg0JjQstCw0L3Qvi3QpNGA0LDQvdC60L7QstGB0LrQsNGPINC-0LHQu9Cw0YHRgtGMLCA3NjAwMA!5e0!3m2!1sru!2sua!4v1740984039985!5m2!1sru!2sua';
        $contacts_list = [
                [
                        'type' => 'phone',
                        'description' => 'в.о. Директора',
                        'phone_raw' => '+380665224062',
                        'phone_formatted' => '+38(066)-522-40-62'
                ],
                [
                        'type' => 'phone',
                        'description' => 'Головний інженер-ґрунтознавець',
                        'phone_raw' => '+380994432470',
                        'phone_formatted' => '+38(099)-443-24-70'
                ],
                [
                        'type' => 'phone',
                        'description' => 'Зав. лаб. моніторингу земель',
                        'phone_raw' => '+380979533145',
                        'phone_formatted' => '+38(097)-953-31-45'
                ],
                [
                        'type' => 'phone',
                        'description' => 'Зав. лаб. аналітичного забезпечення',
                        'phone_raw' => '+380678526329',
                        'phone_formatted' => '+38(067)-852-63-29'
                ],
                [
                        'type' => 'email',
                        'email' => 'ifroduchist@ukr.net'
                ],
                [
                        'type' => 'address',
                        'text' => 'вул. Гаркуші, 2, м. Івано-Франківськ, 76018',
                        'url' => 'https://maps.app.goo.gl/Tc6v31157dNd5gGMA'
                ]
        ];
        include __DIR__ . '/../components/_center_si_contacts.php';

        ?>

    </div>
</main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
