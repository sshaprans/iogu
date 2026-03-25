<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'center_si';

$page_title = 'Західний міжрегіональний центр державної установи ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ';

ob_start();
?>
    <main id="main">
        <div class="main-container">
            <h1 class="main-title main-title--branch">
                <?= $page_title ?>
            </h1>

            <?php
            $bgLink = 'https://media.iogu.gov.ua/img/branches/history/hmelik.jpg';
            $history_slides = [
                    [
                            'title' => 'Історія',
                            'text' => 'Започаткована в 1964 році в Україні агрохімічна служба (зональні агрохімічні лабораторії) виконувала функції з агрохімічного обстеження ґрунтів.'
                    ],
                    [
                            'text' => 'У Хмельницькій області було створено агрохімічну лабораторію, яку в 1984 році реорганізували в обласну державну проєктно-розвідувальну станцію хімізації сільського господарства. У 1999 році її перетворили на Хмельницький обласний державний проєктно-технологічний центр охорони родючості ґрунтів і якості продукції «Облдержродючість».'
                    ],
                    [
                            'text' => 'З 2013 року установа отримала назву Хмельницька філія державної установи «Інститут охорони ґрунтів України». У 2024 році її перейменували на Західний міжрегіональний центр ДУ «Держґрунтохорона».'
                    ],
                    [
                            'text' => 'Наукова установа має висококваліфікованих працівників, які забезпечують якість і точність досліджень. Протягом понад 60 років вони надають послуги виробникам сільськогосподарської продукції.'
                    ],
                    [
                            'text' => 'Західний міжрегіональний центр ДУ «Держґрунтохорона» співпрацює з Хмельницькою ОДА, Департаментом агропромислового розвитку Хмельницької ОДА, управліннями агропромислового розвитку райдержадміністрацій Хмельницької області.'
                    ],
                    [
                            'text' => 'Також установа активно взаємодіє із закладами вищої освіти, зокрема Подільським державним університетом, Кам’янець-Подільським національним університетом імені Івана Огієнка та іншими навчальними закладами, а також з Головним управлінням земельних ресурсів.'
                    ],
            ];
            include __DIR__ . '/../components/_center_si_banner.php';

            $leadership_title = 'Керівництво центру';
            $leadership_staff = [
                    [
                            'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/hmelnik_der.jpg',
                            'name' => 'Собко Володимир Іванович',
                            'position' => 'Директор',
                            'phone_raw' => '+380673721996',
                            'phone_formatted' => '+38(067)-372-19-96',
                    ],
                    [
                            'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/hmelnik_gol-buh.jpg',
                            'name' => 'Фроїмчук Ірина Сергіївна',
                            'position' => 'т.в.о. Головного бухгалтера',
                            'phone_raw' => '+380688234228',
                            'phone_formatted' => '+38(068)-823-42-28',
                    ],
                    [
                            'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/hmelnik_nach-vid.jpg',
                            'name' => 'Кожевнікова Валентина Леонідівна',
                            'position' => 'Начальник відділу',
                            'phone_raw' => '+380969675979',
                            'phone_formatted' => '+38(096)-967-59-79',
                    ],
                    [
                            'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/avatar_w.png',
                            'name' => 'Маліновська Юлія Валеріївна',
                            'position' => 'Відділ кадрів',
                            'phone_raw' => '+380975831945',
                            'phone_formatted' => '+38(097)-583-19-45',
                    ],
            ];
            include __DIR__ . '/../components/_center_si_head.php';

            $activities_title = 'Основні напрями діяльності';
            $activities_list = [
                    'паспортизація ґрунтів з видачею агрохімічних паспортів поля, також видаються матеріали моніторингу ґрунтів з еколого-агрохімічними показниками якості, визначеною бальною оцінкою полів, картограми вмісту елементів живлення, довідки про стан родючості ґрунтів, відповідні рекомендації;',
                    'листкова діагностика;',
                    'продуктивна волога;',
                    'дослідження якості продукції рослинництва;',
                    'визначення якості органічних добрив;',
                    'радіологічні дослідження об’єктів довкілля;',
                    'органо-мінеральних добрив та торфу;',
                    'повний зоотехнічний аналіз кормів;',
                    'контроль якості мінеральних добрив та хімічних меліорантів.'
            ];
            include __DIR__ . '/../components/_center_si_activities.php';

            $certificates_section_title = 'Сертифікати та Атестати';
            $certificates_list = [
                    [
                            'link_href' => 'https://media.iogu.gov.ua/literature/branches/hmelik.pdf',
                            'img_src' => 'https://media.iogu.gov.ua/img/branches/documents/hmelik.jpg',
                            'img_alt' => 'Свідоцтво про відповідність системи вимірювань № 0257 від 20.12.2024 р.',
                            'description' => '* Свідоцтво про відповідність системи вимірювань № 0257 від 20.12.2024 р.',
                    ],
            ];
            include __DIR__ . '/../components/_center_si_certificates.php';

            $contacts_title = 'Звертатися за контактами';
            $contacts_map_src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2633.94248897162!2d26.577271300000003!3d48.6874642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4733c7f2d43ed7cb%3A0x1ab8f733d5e216bd!2z0YPQuy4g0K_RgNC-0YHQu9Cw0LLQsCDQnNGD0LTRgNC-0LPQviwgMTE0LCDQmtCw0LzQtdC90LXRhi3Qn9C-0LTQvtC70YzRgdC60LjQuSwg0KXQvNC10LvRjNC90LjRhtC60LDRjyDQvtCx0LvQsNGB0YLRjCwgMzIzMDE!5e0!3m2!1sru!2sua!4v1740981892716!5m2!1sru!2sua';
            $contacts_list = [
                    [
                            'type' => 'phone',
                            'description' => 'Директор',
                            'phone_raw' => '+380384974071',
                            'phone_formatted' => '+38(03849)-7-40-71',
                    ],
                    [
                            'type' => 'phone',
                            'description' => 'т.в.о. Головного бухгалтера',
                            'phone_raw' => '+380688234228',
                            'phone_formatted' => '+38(068)-823-42-28',
                    ],
                    [
                            'type' => 'phone',
                            'description' => 'Начальник відділу',
                            'phone_raw' => '+380969675979',
                            'phone_formatted' => '+38(096)-967-59-79',
                    ],
                    [
                            'type' => 'phone',
                            'description' => 'Відділ кадрів',
                            'phone_raw' => '+380975831945',
                            'phone_formatted' => '+38(097)-583-19-45',
                    ],
                    [
                            'type' => 'email',
                            'email' => 'khmelnitsky@iogu.gov.ua',
                    ],
                    [
                            'type' => 'email',
                            'email' => 'obl-rod@ukr.net',
                    ],
                    [
                            'type' => 'address',
                            'text' => 'вул. Ярослава Мудрого, 114, м. Кам’янець-Подільський, 32300',
                            'url' => 'https://maps.app.goo.gl/WbTtCeW4AQecJhf38',
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