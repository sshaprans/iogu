<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'center_si';

$page_title = 'Південно-східний міжрегіональний центр державної установи
ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ';

ob_start();
?>
<main id="main">
    <div class="main-container">
        <h1 class="main-title main-title--branch">
            <?= $page_title ?>
        </h1>

        <?php
        $bgLink = 'https://media.iogu.gov.ua/img/branches/history/dnipro.jpg';
        $history_slides = [
                [
                        'title' => 'Історія',

                        'text' => '
                                Згідно з Постановою Ради Міністрів СРСР у 1964 р. створення Дніпропетровської зональної агрохімічної лабораторії було доручено директору Всесоюзного науково-дослідного інституту кукурудзи академіку Задонцеву А.І. Лабораторію було створено на базі цієї науково-дослідної установи.
                            '
                ],
                [
                        'text' => '
                                 У 1969 р. лабораторію передано в управління Мінсільгоспу УРСР.
                            '
                ],
                [
                        'text' => '
                                 У 1972 р. збудовано будівлю для лабораторії в с. Дослідне Дніпропетровського району. В основу покладено проект ветеринарної лабораторії, оскільки типовий проект агрохімічних лабораторій, і були споруджені в інших областях, не задовольняв вимоги керівництва області.
                            '
                ],
                [
                        'text' => '
                                 У 1973 р. Дніпропетровська зональна агрохімічна лабораторія продовжила роботу в новому приміщенні.
                            '
                ],
                [
                        'text' => '
                                 Дуже впливовим фактором у конкурентній спроможності установи є сформована база даних потенційної родючості ґрунтів, яка має розповсюдження на всі землі сільськогосподарського призначення Дніпропетровської області.
                            '
                ],
                [
                        'text' => '
                                  У 2024 році перейменовано на Південно-східний міжрегіональний центр державної установи «Інститут охорони ґрунтів України».
                            '
                ],

        ];
        include __DIR__ . '/../components/_center_si_banner.php';

        $leadership_title = 'Керівництво центру';
        $leadership_staff = [
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/dnipro_der.jpg',
                        'name' => 'Жученко Сергій Іванович',
                        'position' => 'Директор',
                        'phone_raw' => '+380676818519',
                        'phone_formatted' => ' +38(067)-681-85-19',
                ],
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/dnipro_gol-buh.jpg',
                        'name' => 'Гаврилко Аліна Володимирівна',
                        'position' => 'Головний бухгалтер',
                        'phone_raw' => '+380952498684',
                        'phone_formatted' => '+38(095)-249-86-84',
                ],
                [
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/dnipro_gol-engen.jpg',
                        'name' => 'Сироватко Володимир Олексійович',
                        'position' => 'Головний інженер-ґрунтознавець',
                        'phone_raw' => '+380979986459',
                        'phone_formatted' => '+38(097)-998-64-59',
                ],
        ];
        include __DIR__ . '/../components/_center_si_head.php';

        $activities_title = 'Основні напрями діяльності';
        $activities_list = [
                'агрохімічні дослідження  стану родючості ґрунтів з наступною розробкою агрохімічних картограм еколого-агрохімічних паспортів полів та рекомендацій щодо поліпшення якості ґрунтів і удобрення сільськогосподарських культур;',
                'екотоксикологічні дослідження ґрунтів та продукції щодо рівня забруднення їх важкими металами, залишками пестицидів, радіонуклідами',
                'проведення ґрунтової діагностики мінерального живлення сільськогосподарських культур;',
                'проведення польових дослідів з агрохімікатами;',
                ' здійснення оперативного контролю за якістю кормів у період заготівлі та повного зоотехнічного аналізу в період їх зберігання;',
                ' визначення  якості  продукції  рослинництва, сільсь­когосподарської сировини, добрив та пестицидів;',
                ' розробка   технологічної   та проектно-кошторисної документації на проведення заходів по докорінному поліпшенню земель;',
                ' розробка,  реалізація та контроль за  виконанням державних та регіональних програм з охорони родючості ґрунтів та ін.'
        ];
        include __DIR__ . '/../components/_center_si_activities.php';

        $certificates_section_title = 'Сертифікати та Атестати';
        $certificates_list = [
                [
                        'link_href' => 'https://media.iogu.gov.ua/img/branches/dnipro2.jpg',
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/documents/dnipro.jpg',
                        'img_alt' => 'Свідоцтво про відповідність системи вимірювань № 0256 від 17.12.2024 р.',
                        'description' => '* Свідоцтво про відповідність системи вимірювань № 0256 від 17.12.2024 р.',
                ],
                [
                        'link_href' => 'https://media.iogu.gov.ua/img/branches/dnipro.jpg',
                        'img_src' => 'https://media.iogu.gov.ua/img/branches/documents/dnipro_2.jpg',
                        'img_alt' => 'Атестат про акредитацію №20438 від 05.10.2025 р.',
                        'description' => '* Атестат про акредитацію №20438 від 05.10.2025 р.',
                ],

        ];
        include __DIR__ . '/../components/_center_si_certificates.php';

        $contacts_title = 'Звертатися за контактами';
        $contacts_map_src = 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5299.0767864591435!2d35.035138!3d48.388603!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40dbfb13ae600e81%3A0x233d5b870eababf9!2z0LLRg9C70LjRhtGPINCd0LDRg9C60L7QstCwLCA2NSwg0JTQvtGB0LvRltC00L3QtSwg0JTQvdGW0L_RgNC-0L_QtdGC0YDQvtCy0YHRjNC60LAg0L7QsdC70LDRgdGC0YwsIDUyMDcx!5e0!3m2!1sru!2sua!4v1740975766448!5m2!1sru!2sua';
        $contacts_list = [
                [
                        'type' => 'phone',
                        'description' => 'Директор',
                        'phone_raw' => '+380676818519',
                        'phone_formatted' => '+38(067)-681-85-19',
                ],
                [
                        'type' => 'phone',
                        'description' => 'Головний бухгалтер',
                        'phone_raw' => '+380952498684',
                        'phone_formatted' => '+38(095)-249-86-84',
                ],
                [
                        'type' => 'phone',
                        'description' => 'Головний інженер-ґрунтознавець',
                        'phone_raw' => '+380979986459',
                        'phone_formatted' => '+38(097)-998-64-59',
                ],
                [
                        'type' => 'email',
                        'email' => 'dnipropetrovsk@iogu.gov.ua',
                ],
                [
                        'type' => 'email',
                        'email' => 'dniprogrunt@ukr.net',
                ],
                [
                        'type' => 'email',
                        'email' => 'roduchist_buh_dp@i.ua',
                ],
                [
                        'type' => 'address',
                        'text' => 'вул. Наукова, 65-А, с-ще Дослідне, Дніпровський р-н, Дніпропетровська обл., 52071',
                        'url' => 'https://maps.app.goo.gl/FnzNSBJBZAASbfhQ7',
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
