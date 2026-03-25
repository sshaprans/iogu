<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'center_si';

$page_title = 'ЧЕРНІГІВСЬКИЙ регіональний центр державної установи ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ';

ob_start();
?>
    <main id="main">
        <div class="main-container">
            <div class="container">
                <h1 class="main-title main-title--branch">
                    Чернігівський регіональний центр державної установи
                    <span class="main-title--branch__bold">
                    ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ
                </span>
                </h1>

                <?php
                $bgLink = 'https://media.iogu.gov.ua/img/branches/history/chernihiv.jpg';
                $history_slides = [
                        [
                                'title' => 'Історія',
                                'text' => 'Свою діяльність служба охорони ґрунтів Чернігівщини започаткувала 30 липня 1964 р. згідно з рішенням виконкому Чернігівської обласної Ради депутатів трудящих про створення зональної агрохімічної лабораторії при Чернігівській обласній державній сільськогосподарській дослідній станції.'
                        ],
                        [
                                'text' => 'За більше ніж півстоліття, в результаті неодноразової зміни та розширення функцій, зональна агрохімічна лабораторія трансформувалася в Чернігівський регіональний центр державної установи «Інститут охорони ґрунтів України» – єдина в області державна наукова установа з широким спектром агрохімічних досліджень.'
                        ],
                        [
                                'text' => 'Нині Чернігівський регіональний центр ДУ «Держґрунтохорона» реалізує свою діяльність у тісній співпраці з <a href="https://apk.cg.gov.ua/" class="swiper-slide__link" target="_blank">Департаментом агропромислового розвитку облдержадміністрації</a>, районними та місцевими органами виконавчої влади й самоврядування, органами земельних ресурсів та екології, охорони навколишнього середовища, науковими установами та ін.'
                        ],
                ];
                include __DIR__ . '/../components/_center_si_banner.php';
                ?>

                <section class="branch-head">
                    <h3 class="section-title-sup section-title-sup--branches">Керівництво центру</h3>
                    <div class="branches-leaderships__wrapper">
                        <?php
                        $leadership_staff = [
                                [
                                        'img_src' => 'https://media.iogu.gov.ua/img/branches/staff/chernigiv_der.jpg',
                                        'name' => 'Шабанова Ірина Ігорівна',
                                        'position' => 'в.о. Директора',
                                        'phone_raw' => '+380508362877',
                                        'phone_formatted' => '+38(050)-836-28-77',
                                ],
                        ];
                        foreach ($leadership_staff as $staff): ?>
                            <div class="branches-leaderships__box">
                                <img class="branches-leaderships__img" src="<?= htmlspecialchars($staff['img_src']) ?>" alt="director">
                                <div class="branches-leaderships__info">
                                    <p class="branches-leaderships__name">
                                        <?= htmlspecialchars($staff['name']) ?>
                                    </p>
                                    <p class="branches-leaderships__position">
                                        <?= htmlspecialchars($staff['position']) ?>
                                    </p>
                                    <div class="branches-leaderships__links">
                                        <?php if (!empty($staff['phone_raw'])): ?>
                                            <a class="branches-leaderships__link" href="tel:<?= htmlspecialchars($staff['phone_raw']) ?>">
                                                <?= htmlspecialchars($staff['phone_formatted']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <?php

                $contacts_title = 'Звертатися за контактами';
                $contacts_map_src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.4202011888992!2d31.359177700000004!3d51.523852299999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46d5482e14458ac1%3A0xca1bd1fe29def354!2z0LLRg9C7LiDQhtCy0LDQvdCwINCS0LjQs9C-0LLRgdGM0LrQvtCz0L4sIDQxLCDQp9C10YDQvdGW0LPRltCyLCDQp9C10YDQvdGW0LPRltCy0YHRjNC60LAg0L7QsdC70LDRgdGC0YwsIDE0MDAw!5e0!3m2!1suk!2sua!4v1740934859587!5m2!1suk!2sua';
                $contacts_list = [
                        [
                                'type' => 'phone',
                                'description' => 'в.о. Директора',
                                'phone_raw' => '+380508362877',
                                'phone_formatted' => '+38(050)-836-28-77',
                        ],
                        [
                                'type' => 'email',
                                'email' => 'chernigiv_grunt@ukr.net',
                        ],
                        [
                                'type' => 'address',
                                'text' => 'вул. Івана Виговського (Малиновського), 41, м. Чернігів, 14020',
                                'url' => 'https://maps.app.goo.gl/9nVpMuxpUwnfGsnh7',
                        ]
                ];
                ?>
                <div class="address_branch ">
                    <div class="address_branch__text">
                        <h3 class="section-title-sup section-title-sup--branches"><?= htmlspecialchars($contacts_title) ?></h3>
                        <div class="address_branch__contacts">
                            <ul class="address_branch__list">
                                <?php foreach ($contacts_list as $contact): ?>
                                    <li class="address_branch__item">
                                        <?php if ($contact['type'] === 'phone'): ?>
                                            <?php if (!empty($contact['description'])): ?>
                                                <span class="address_branch__item__desc"><?= htmlspecialchars($contact['description']) ?></span>
                                            <?php endif; ?>
                                            <a class="address_branch__link" href="tel:<?= htmlspecialchars($contact['phone_raw']) ?>">
                                                <?= htmlspecialchars($contact['phone_formatted']) ?>
                                            </a>
                                        <?php elseif ($contact['type'] === 'email'): ?>
                                            <a class="address_branch__link" href="mailto:<?= htmlspecialchars($contact['email']) ?>">
                                                <?= htmlspecialchars($contact['email']) ?>
                                            </a>
                                        <?php elseif ($contact['type'] === 'address'): ?>
                                            <a class="address_branch__link" target="_blank" href="<?= htmlspecialchars($contact['url']) ?>">
                                                <?= htmlspecialchars($contact['text']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php if (!empty($contacts_map_src)): ?>
                        <iframe class="address_branch__map" src="<?= htmlspecialchars($contacts_map_src) ?>" width="600" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>