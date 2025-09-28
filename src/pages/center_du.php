<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'center_du';

$page_title = 'Центри державної установи | Інститут охорони ґрунтів України';
//$page_description = 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»';

ob_start();
?>
<main id="main" data-center="dnipro">
    <div class="main-container">
        <h1 class="main-title main-title--branch">
            Південно-східний міжрегіональний центр державної установи
            <span class="main-title--branch__bold">
        ІНСТИТУТ ОХОРОНИ ҐРУНТІВ УКРАЇНИ
      </span>
        </h1>

        <!-- ІСТОРІЯ -->
        <section class="swiper mySwiper swiper--branch-history" style="--swiper-navigation-color:#fff;--swiper-pagination-color:#fff" aria-label="Історія центру">
            <div class="parallax-bg"
                 style="background-image:url(https://media.iogu.gov.ua/img/branches/history/dnipro.jpg)"
                 data-swiper-parallax="-23%"
                 role="img"
                 aria-label="Будівля центру, м. Дніпро">
            </div>

            <div class="swiper-wrapper">
                <div class="swiper-slide swiper-slide--branch-history">
                    <div class="branch-history__title" data-swiper-parallax="-300">Історія</div>
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            Згідно з Постановою Ради Міністрів СРСР у 1964 р. створення Дніпропетровської зональної агрохімічної лабораторії було доручено директору Всесоюзного науково-дослідного інституту кукурудзи академіку Задонцеву А.І. Лабораторію було створено на базі цієї науково-дослідної установи.
                        </p>
                    </div>
                </div>

                <div class="swiper-slide swiper-slide--branch-history">
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            У 1969 р. лабораторію передано в управління Мінсільгоспу УРСР.
                        </p>
                    </div>
                </div>

                <div class="swiper-slide swiper-slide--branch-history">
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            У 1972 р. збудовано будівлю для лабораторії в с. Дослідне Дніпропетровського району. В основу покладено проект ветеринарної лабораторії, оскільки типовий проект агрохімічних лабораторій, і були споруджені в інших областях, не задовольняв вимоги керівництва області.
                        </p>
                    </div>
                </div>

                <div class="swiper-slide swiper-slide--branch-history">
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            У 1973 р. Дніпропетровська зональна агрохімічна лабораторія продовжила роботу в новому приміщенні.
                        </p>
                    </div>
                </div>

                <div class="swiper-slide swiper-slide--branch-history">
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            Дуже впливовим фактором у конкурентній спроможності установи є сформована база даних потенційної родючості ґрунтів, яка має розповсюдження на всі землі сільськогосподарського призначення Дніпропетровської області.
                        </p>
                    </div>
                </div>

                <div class="swiper-slide swiper-slide--branch-history">
                    <div class="branch-history__content" data-swiper-parallax="-100">
                        <p class="branch-history__text">
                            У 2024 році перейменовано на Південно-східний міжрегіональний центр державної установи «Інститут охорони ґрунтів України».
                        </p>
                    </div>
                </div>
            </div>

            <div class="swiper-button-next" aria-label="Наступний слайд"></div>
            <div class="swiper-button-prev" aria-label="Попередній слайд"></div>
            <div class="swiper-pagination" aria-hidden="true"></div>
        </section>

        <!-- КЕРІВНИЦТВО -->
        <section class="container branch-head">
            <h3 class="section-title-sup section-title-sup--branches">Керівництво центру</h3>

            <div class="branches-leaderships__wrapper">
                <div class="branches-leaderships__box">
                    <img class="branches-leaderships__img"
                         src="https://media.iogu.gov.ua/img/branches/staff/dnipro_der.jpg"
                         alt="Жученко Сергій Іванович — директор"
                         loading="lazy" decoding="async">
                    <div class="branches-leaderships__info">
                        <p class="branches-leaderships__name">Жученко Сергій Іванович</p>
                        <p class="branches-leaderships__position">Директор</p>
                        <a class="branches-leaderships__link" href="tel:+380676818519">+38(067)-681-85-19</a>
                    </div>
                </div>

                <div class="branches-leaderships__box">
                    <img class="branches-leaderships__img"
                         src="https://media.iogu.gov.ua/img/branches/staff/dnipro_gol-buh.jpg"
                         alt="Гаврилко Аліна Володимирівна — головний бухгалтер"
                         loading="lazy" decoding="async">
                    <div class="branches-leaderships__info">
                        <p class="branches-leaderships__name">Гаврилко Аліна Володимирівна</p>
                        <p class="branches-leaderships__position">Головний бухгалтер</p>
                        <a class="branches-leaderships__link" href="tel:+380952498684">+38(095)-249-86-84</a>
                    </div>
                </div>

                <div class="branches-leaderships__box">
                    <img class="branches-leaderships__img"
                         src="https://media.iogu.gov.ua/img/branches/staff/dnipro_gol-engen.jpg"
                         alt="Сироватко Володимир Олексійович — головний інженер-ґрунтознавець"
                         loading="lazy" decoding="async">
                    <div class="branches-leaderships__info">
                        <p class="branches-leaderships__name">Сироватко Володимир Олексійович</p>
                        <p class="branches-leaderships__position">Головний інженер-ґрунтознавець</p>
                        <a class="branches-leaderships__link" href="tel:+380979986459">+38(097)-998-64-59</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- НАПРЯМИ ДІЯЛЬНОСТІ -->
        <section class="text_branches branches-serves container">
            <h3 class="section-title-sup section-title-sup--branches">Основні напрями діяльності</h3>

            <ul class="branches-serves__list">
                <li class="branches-serves__item">
                    агрохімічні дослідження стану родючості ґрунтів з наступною розробкою агрохімічних картограм еколого-агрохімічних паспортів полів та рекомендацій щодо поліпшення якості ґрунтів і удобрення сільськогосподарських культур;
                </li>
                <li class="branches-serves__item">
                    екотоксикологічні дослідження ґрунтів та продукції щодо рівня забруднення їх важкими металами, залишками пестицидів, радіонуклідами;
                </li>
                <li class="branches-serves__item">
                    проведення ґрунтової діагностики мінерального живлення сільськогосподарських культур;
                </li>
                <li class="branches-serves__item">
                    проведення польових дослідів з агрохімікатами;
                </li>
                <li class="branches-serves__item">
                    здійснення оперативного контролю за якістю кормів у період заготівлі та повного зоотехнічного аналізу в період їх зберігання;
                </li>
                <li class="branches-serves__item">
                    визначення якості продукції рослинництва, сільськогосподарської сировини, добрив та пестицидів;
                </li>
                <li class="branches-serves__item">
                    розробка технологічної та проектно-кошторисної документації на проведення заходів по докорінному поліпшенню земель;
                </li>
                <li class="branches-serves__item">
                    розробка, реалізація та контроль за виконанням державних та регіональних програм з охорони родючості ґрунтів та ін.
                </li>
            </ul>

            <!-- Документи -->
            <a href="https://media.iogu.gov.ua/literatura/branches/dnipro.pdf"
               class="link__document link__document--branches"
               target="_blank" rel="noopener noreferrer">
                <img src="https://media.iogu.gov.ua/img/branches/documents/dnipro.jpg"
                     alt="Свідоцтво про відповідність системи вимірювань №0256 від 17.12.2024"
                     class="link__document-img" loading="lazy" decoding="async">
                <div class="link__document__wrapper">
                    <p class="title__hover-link">Переглянути свідоцтво</p>
                </div>
            </a>
            <p class="desc-for-link__document">
                * Свідоцтво про відповідність системи вимірювань № 0256 від 17.12.2024 р.
            </p>

            <a href="https://media.iogu.gov.ua/literatura/branches/dnipro_2.jpg"
               class="link__document link__document--branches"
               target="_blank" rel="noopener noreferrer">
                <img src="https://media.iogu.gov.ua/img/branches/documents/dnipro_2.jpg"
                     alt="Атестат про акредитацію №20438 від 11.12.2024"
                     class="link__document-img" loading="lazy" decoding="async">
                <div class="link__document__wrapper">
                    <p class="title__hover-link">Переглянути свідоцтво</p>
                </div>
            </a>
            <p class="desc-for-link__document">
                * Атестат про акредитацію №20438 від 11.12.2024 р.
            </p>
        </section>

        <!-- КОНТАКТИ -->
        <article class="address_branch">
            <div class="address_branch__text">
                <h3 class="section-title-sup section-title-sup--contact">Звертатися за контактами</h3>
                <div class="address_branch__contacts">
                    <ul class="address_branch__list">
                        <li class="address_branch__item">
                            <span class="address_branch__item__desc">Директор</span>
                            <a class="address_branch__link" href="tel:+380676818519">+38(067)-681-85-19</a>
                        </li>
                        <li class="address_branch__item">
                            <span class="address_branch__item__desc">Головний бухгалтер</span>
                            <a class="address_branch__link" href="tel:+380952498684">+38(095)-249-86-84</a>
                        </li>
                        <li class="address_branch__item">
                            <span class="address_branch__item__desc">Головний інженер-ґрунтознавець</span>
                            <a class="address_branch__link" href="tel:+380979986459">+38(097)-998-64-59</a>
                        </li>
                        <li class="address_branch__item">
                            <a href="mailto:dnipropetrovsk@iogu.gov.ua" class="address_branch__link">dnipropetrovsk@iogu.gov.ua</a>
                        </li>
                        <li class="address_branch__item">
                            <a href="mailto:dniprogrunt@ukr.net" class="address_branch__link">dniprogrunt@ukr.net</a>
                        </li>
                        <li class="address_branch__item">
                            <a href="mailto:roduchist_buh_dp@i.ua" class="address_branch__link">roduchist_buh_dp@i.ua</a>
                        </li>
                        <li class="address_branch__item">
                            <a class="address_branch__link" target="_blank" rel="noopener noreferrer"
                               href="https://maps.app.goo.gl/FnzNSBJBZAASbfhQ7">
                                вул. Наукова, 65-А, с-ще Дослідне, Дніпровський р-н, Дніпропетровська обл., 52071
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <iframe class="address_branch__map"
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5299.0767864591435!2d35.035138!3d48.388603!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40dbfb13ae600e81%3A0x233d5b870eababf9!2z0LLRg9C70LjRhtGPINCd0LDRg9C60L7QstCwLCA2NSwg0JTQvtGB0LvRltC00L3QtSwg0JTQvdGW0L_RgNC-0L_QtdGC0YDQvtCy0YHRjNC60LAg0L7QsdC70LDRgdGC0YwsIDUyMDcx!5e0!3m2!1sru!2sua!4v1740975766448!5m2!1sru!2sua"
                    width="600" height="300" style="border:0" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
            </iframe>
        </article>
    </div>
</main>


<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
