<!DOCTYPE html>
<html lang="<?= $current_lang ?? 'uk' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?? 'Державна установа "Інститут охорони ґрунтів України"' ?></title>
    <meta name="description" content="<?= $page_description ?? 'Офіційний сайт Державної установи «Інститут охорони ґрунтів України»' ?>">
</head>
<body>
<header class="header">
    <div class="header-wrap">
        <div class="header-up">
            <div class="header-up__logo-wrapper">
                <a href="/pages/index.php" class="header-up__logo">
                    <img class="header-up__logo-img" src="/img/logo.svg" width="100" height="100" alt="">
                </a>
                <a href="#" class="header-up__state-site header-link">
                    <?= t('header.state_site') ?>
                </a>
            </div>
            <div class="header-up__title-wrapper">
                <svg class="header-up__sing" width="24" height="39" viewBox="0 0 24 39" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_624_1248)">
                        <path d="M11.992 0C11.0601 1.03173 10.4921 2.38137 10.4921 3.86363C10.5471 7.11777 10.9561 10.368 10.9921 13.6153C11.0661 16.6481 10.1342 19.4956 9.00025 22.2748C8.62228 23.0423 8.21432 23.7932 7.80535 24.546L6.60545 24.31C5.52254 24.0994 4.8186 23.0725 5.03558 22.0164C5.22457 21.0929 6.0585 20.4464 6.98842 20.4464L7.42638 20.4922L6.44946 12.5348C6.13049 8.99497 4.25665 5.88418 1.49988 3.86558C1.02591 3.51842 0.522956 3.20051 0 2.92064V31.1713H6.68744C7.1874 33.814 8.68128 36.1252 10.7811 37.705C11.2801 38.0404 11.699 38.4832 12 39C12.3 38.4832 12.7189 38.0404 13.2189 37.705C15.3187 36.1213 16.8126 33.815 17.3126 31.1713H24V2.92064C23.477 3.20051 22.9741 3.51842 22.5001 3.86558C19.7404 5.8871 17.8705 8.99497 17.5505 12.5348L16.5736 20.4922L17.0116 20.4464C17.9415 20.4503 18.7754 21.092 18.9644 22.0164C19.1804 23.0725 18.4765 24.0984 17.3946 24.31L16.1947 24.546C15.7857 23.7942 15.3767 23.0433 14.9998 22.2748C13.8648 19.4976 12.9329 16.6501 13.0069 13.6153C13.0429 10.3583 13.4519 7.11094 13.5069 3.86363C13.5069 2.38137 12.9389 1.03563 12.007 0H11.992ZM1.99283 6.99197C3.28673 8.47326 4.15565 10.3173 4.41263 12.3486L5.21257 18.9115C4.18765 19.4088 3.41272 20.3216 3.11874 21.4265H1.98583V6.99392L1.99283 6.99197ZM21.9912 6.99197V21.4245H20.8583C20.5663 20.3206 19.7894 19.4078 18.7644 18.9095L19.5644 12.3466C19.8233 10.3173 20.6923 8.47131 21.9842 6.98905L21.9912 6.99197ZM11.992 20.186C12.529 21.8994 13.2679 23.5309 14.1868 25.0404C13.3129 25.2989 12.553 25.802 11.992 26.4798C11.431 25.802 10.6711 25.2998 9.79718 25.0395C10.7161 23.527 11.455 21.8994 11.992 20.186ZM1.99283 23.3787H3.12574C3.48271 24.7255 4.56062 25.7884 5.92551 26.1599L6.88643 26.3735C6.62945 27.2824 6.48846 28.2361 6.48846 29.2307H1.98883V23.3797L1.99283 23.3787ZM20.8613 23.3787H21.9942V29.2298H17.4945C17.4945 28.2409 17.3536 27.2824 17.0966 26.3725L18.0575 26.159C19.4274 25.7874 20.5043 24.7235 20.8573 23.3778L20.8613 23.3787ZM8.84226 26.8211C10.0552 26.9878 10.9901 28.0011 10.9901 29.2288H8.49029C8.49029 28.3931 8.61728 27.5866 8.84226 26.8211ZM15.1387 26.8211C15.3637 27.5866 15.4907 28.3911 15.4907 29.2288H12.9909C12.9909 27.9991 13.9258 26.9878 15.1387 26.8211ZM8.71927 31.1801H10.9921V35.2856C9.89218 34.1593 9.09224 32.7501 8.71927 31.1801ZM12.9919 31.1801H15.2647C14.8968 32.7501 14.0948 34.1612 12.9919 35.2856V31.1801Z"
                              fill="#FFD500"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_624_1248">
                            <rect width="24" height="39" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
                <h2 class="header-up__title">
                    <?= t('header.title') ?>
                </h2>
            </div>
            <div class="header-up__ui-wrapper">
                <div class="header-up__grey-view">
                    <svg class="header-up__grey-svg" width="25" height="13" viewBox="0 0 25 13" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_624_1253)">
                            <path d="M24.7746 5.625C21.9281 2.1875 17.7111 0.208333 13.2834 0H12.0183L9.90982 0.208333C9.27728 0.3125 8.53932 0.520833 7.90678 0.729167C7.27424 0.9375 6.53626 1.25 5.90372 1.45833C5.27118 1.77083 4.63864 2.08333 4.0061 2.5C3.5844 2.8125 2.6356 3.54167 2.2139 3.85417C1.58136 4.375 1.05424 5 0.527118 5.52083L0 6.25C0.316271 6.875 0.737965 7.39583 1.26508 7.91667C1.68678 7.29167 2.2139 6.77083 2.74101 6.25L2.84644 6.14583L3.90068 9.79167C4.74407 10.3125 5.58746 10.8333 6.43085 11.1458L4.63864 4.6875C5.27118 4.27083 5.90372 3.85417 6.53626 3.54167L8.85558 12.0833C9.59355 12.2917 10.3315 12.3958 11.1749 12.5L8.43389 2.70833C9.06643 2.5 9.8044 2.39583 10.4369 2.1875L13.178 12.3958L15.2864 12.1875C18.9762 11.5625 22.2444 9.6875 24.6692 6.875C25.0908 6.5625 25.0908 5.9375 24.7746 5.625ZM16.8678 6.25C16.8678 7.70833 16.0244 9.0625 14.7593 9.79167L14.3376 8.22917C14.9701 7.70833 15.2864 6.97917 15.2864 6.25C15.2864 5 14.3376 3.85417 13.0725 3.64583L12.6508 2.08333C14.9701 2.08333 16.8678 3.95833 16.8678 6.25ZM18.2383 9.27083C19.2925 7.39583 19.2925 5.10417 18.2383 3.33333C19.8196 4.0625 21.2956 5.10417 22.5607 6.35417C21.2956 7.5 19.8196 8.54167 18.2383 9.27083Z"
                                  fill="#F5F5F5"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_624_1253">
                                <rect width="25" height="12.5" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                    <span class="header-link header__btn-eye">
                        <?= t('header.eye') ?>
                    </span>
                </div>
                <a href="tel:0443565321" class="header-up__ui--tel header-link">
                    (044)-356-53-21
                </a>
                <ul class="header-up__social-list">
                    <li class="header-up__social-item">
                        <a href="#" class="header-up__social-link">
                            <svg class="header-social" width="30" height="30" viewBox="0 0 30 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.0002 1.2002C7.391 1.2002 1.2002 7.3904 1.2002 15.0002C1.2002 22.61 7.391 28.8002 15.0002 28.8002C22.61 28.8002 28.8002 22.61 28.8002 15.0002C28.8002 7.3904 22.61 1.2002 15.0002 1.2002ZM19.2002 9.6002H17.2262C16.0634 9.6002 15.6002 9.8732 15.6002 10.6838V12.6002H19.2002L18.6002 15.6002H15.6002V23.4002H12.0002V15.6002H10.2002V12.6002H12.0002V10.9358C12.0002 8.4008 13.0118 6.6002 15.9488 6.6002C17.522 6.6002 19.2002 7.2002 19.2002 7.2002V9.6002Z"
                                      fill="white"/>
                            </svg>
                        </a>
                    </li>
                    <li class="header-up__social-item">
                        <a href="#" class="header-up__social-link">
                            <svg class="header-social" width="30" height="30" viewBox="0 0 30 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M27.6822 3.93119C27.2322 3.54959 26.5254 3.49499 25.7946 3.78839H25.7934C25.0248 4.09679 4.03685 13.0992 3.18245 13.467C3.02705 13.521 1.66985 14.0274 1.80965 15.1554C1.93445 16.1724 3.02525 16.5936 3.15845 16.6422L8.49425 18.4692C8.84825 19.6476 10.1532 23.9952 10.4418 24.924C10.6218 25.503 10.9152 26.2638 11.4294 26.4204C11.8806 26.5944 12.3294 26.4354 12.6198 26.2074L15.882 23.1816L21.1482 27.2886L21.2736 27.3636C21.6312 27.522 21.9738 27.6012 22.3008 27.6012C22.5534 27.6012 22.7958 27.5538 23.0274 27.459C23.8164 27.135 24.132 26.3832 24.165 26.298L28.0986 5.85179C28.3386 4.75979 28.005 4.20419 27.6822 3.93119ZM13.2 19.2L11.4 24L9.60005 18L23.4 7.79999L13.2 19.2Z"
                                      fill="white"/>
                            </svg>
                        </a>
                    </li>
                    <li class="header-up__social-item">
                        <a href="#" class="header-up__social-link">
                            <svg class="header-social" width="30" height="30" viewBox="0 0 30 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M26.8688 7.89356C26.4001 6.22012 25.472 4.89824 24.1126 3.96309C22.397 2.77949 20.4235 2.36231 18.7196 2.10684C16.3618 1.75528 14.2267 1.70606 12.1899 1.95449C10.2821 2.18887 8.84541 2.56153 7.53525 3.16387C4.9665 4.34512 3.42432 6.25527 2.95322 8.84043C2.72353 10.0967 2.56885 11.2311 2.47978 12.3139C2.27353 14.817 2.46103 17.0318 3.05166 19.085C3.62823 21.0865 4.63369 22.5162 6.12666 23.4561C6.50635 23.6951 6.99385 23.8686 7.46494 24.035C7.73213 24.1287 7.99229 24.2201 8.20557 24.3209C8.40245 24.4123 8.40245 24.4287 8.40011 24.5928C8.3837 26.0154 8.40011 28.8045 8.40011 28.8045L8.40479 29.3998H9.47354L9.64698 29.2311C9.76182 29.1232 12.4103 26.5639 13.3548 25.5326L13.4837 25.3897C13.6454 25.2022 13.6454 25.2022 13.8118 25.1998C15.0868 25.174 16.39 25.1248 17.6837 25.0545C19.2517 24.9701 21.0681 24.8178 22.779 24.1053C24.3446 23.4514 25.4884 22.4131 26.1751 21.0209C26.8923 19.5678 27.3165 17.9951 27.4759 16.2115C27.7571 13.0756 27.5579 10.3545 26.8688 7.89356ZM21.2298 20.0881C20.836 20.7279 20.2501 21.1732 19.5587 21.4615C19.0524 21.6725 18.5368 21.6279 18.0376 21.417C13.8587 19.6475 10.5798 16.8607 8.41415 12.8529C7.9665 12.0279 7.65712 11.1279 7.30087 10.2561C7.22822 10.0779 7.2329 9.86699 7.20009 9.67012C7.23056 8.26153 8.31102 7.467 9.40322 7.22794C9.8204 7.13419 10.1907 7.28184 10.5001 7.5795C11.3579 8.39513 12.0352 9.34435 12.5462 10.4084C12.7688 10.8748 12.6681 11.2873 12.2884 11.6342C12.2087 11.7068 12.1267 11.7725 12.0399 11.8381C11.1728 12.4897 11.0462 12.9842 11.5079 13.9662C12.2954 15.6373 13.6032 16.76 15.2931 17.4561C15.7384 17.6389 16.1579 17.5475 16.4977 17.1865C16.5446 17.1397 16.5962 17.0928 16.629 17.0365C17.2946 15.9256 18.2626 16.0358 19.1556 16.6686C19.7415 17.0858 20.311 17.5264 20.8899 17.9506C21.7735 18.5998 21.7665 19.2092 21.2298 20.0881ZM15.6868 8.99981C15.4899 8.99981 15.2931 9.01621 15.0985 9.04903C14.7704 9.10293 14.4634 8.88262 14.4071 8.55449C14.3532 8.22871 14.5735 7.91934 14.9017 7.86543C15.1595 7.8209 15.4243 7.79981 15.6868 7.79981C18.286 7.79981 20.4001 9.91387 20.4001 12.5131C20.4001 12.7779 20.379 13.0428 20.3345 13.2982C20.2853 13.5912 20.0321 13.7998 19.7439 13.7998C19.711 13.7998 19.6782 13.7975 19.6431 13.7904C19.3173 13.7365 19.097 13.4272 19.1509 13.1014C19.1837 12.9092 19.2001 12.7123 19.2001 12.5131C19.2001 10.5772 17.6227 8.99981 15.6868 8.99981ZM18.6001 12.5998C18.6001 12.9303 18.3306 13.1998 18.0001 13.1998C17.6696 13.1998 17.4001 12.9303 17.4001 12.5998C17.4001 11.6084 16.5915 10.7998 15.6001 10.7998C15.2696 10.7998 15.0001 10.5303 15.0001 10.1998C15.0001 9.86934 15.2696 9.59981 15.6001 9.59981C17.2548 9.59981 18.6001 10.9451 18.6001 12.5998ZM22.0267 13.9334C21.9634 14.2123 21.7149 14.3998 21.4407 14.3998C21.3962 14.3998 21.3517 14.3951 21.3071 14.3857C20.9837 14.3107 20.7821 13.9896 20.8548 13.6662C20.9415 13.2842 20.986 12.8881 20.986 12.4896C20.986 9.57403 18.6118 7.1998 15.6962 7.1998C15.2977 7.1998 14.9017 7.24434 14.5196 7.33105C14.1962 7.40605 13.8751 7.20215 13.8024 6.87871C13.7274 6.55527 13.9313 6.23417 14.2548 6.16152C14.7235 6.05371 15.2087 5.9998 15.6962 5.9998C19.2751 5.9998 22.186 8.91074 22.186 12.4896C22.186 12.9771 22.1321 13.4623 22.0267 13.9334Z"
                                      fill="white" fill-opacity="0.85"/>
                            </svg>
                        </a>
                    </li>
                    <li class="header-up__social-item">
                        <a href="#" class="header-up__social-link">
                            <svg class="header-social" width="30" height="30" viewBox="0 0 30 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.9998 1.2002C7.3906 1.2002 1.1998 7.391 1.1998 15.0002C1.1998 17.3762 1.8136 19.7126 2.9776 21.7742L1.222 28.0382C1.1644 28.244 1.2202 28.4648 1.369 28.6178C1.4836 28.736 1.6396 28.8002 1.7998 28.8002C1.8478 28.8002 1.8964 28.7942 1.9438 28.7828L8.4814 27.1634C10.4776 28.235 12.7258 28.8002 14.9998 28.8002C22.609 28.8002 28.7998 22.6094 28.7998 15.0002C28.7998 7.391 22.609 1.2002 14.9998 1.2002ZM21.9418 19.8698C21.6466 20.687 20.2306 21.4328 19.5502 21.533C18.9394 21.6224 18.1666 21.6608 17.3182 21.3944C16.804 21.2324 16.144 21.0176 15.2986 20.657C11.7448 19.1414 9.424 15.608 9.2464 15.3746C9.0694 15.1412 7.7998 13.478 7.7998 11.7566C7.7998 10.0352 8.7148 9.1886 9.04 8.8382C9.3652 8.4878 9.7486 8.4002 9.985 8.4002C10.2214 8.4002 10.4572 8.4032 10.6642 8.4128C10.882 8.4236 11.1742 8.3306 11.4616 9.0134C11.7568 9.7142 12.4654 11.4356 12.553 11.6114C12.6418 11.7866 12.7006 11.9912 12.583 12.2246C12.4654 12.458 12.4066 12.6038 12.229 12.8084C12.0514 13.013 11.857 13.2644 11.6974 13.4216C11.5198 13.5962 11.3356 13.7852 11.542 14.1356C11.7484 14.486 12.4594 15.6314 13.513 16.559C14.866 17.7506 16.0078 18.1202 16.3618 18.2954C16.7158 18.4706 16.9228 18.4412 17.1292 18.2078C17.3356 17.9738 18.0148 17.186 18.2506 16.8362C18.4864 16.4864 18.7228 16.544 19.048 16.661C19.3732 16.7774 21.115 17.6234 21.469 17.7986C21.823 17.9738 22.0594 18.0614 22.1482 18.2072C22.237 18.3524 22.237 19.0532 21.9418 19.8698Z"
                                      fill="white" fill-opacity="0.85"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="header-down">
            <nav class="nav">
                <ul class="nav__menu">
                    <li class="nav__item nav__item--has-submenu">
                        <span class="nav__link">
                            <?= t('header.menu.about') ?>
                        </span>
                        <svg class="nav__link-svg" width="10" height="8" viewBox="0 0 10 8" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 8L9.33013 0.5H0.669873L5 8Z" fill="white"/>
                        </svg>
                        <ul class="nav__submenu">
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link">
                                    <?= t('header.menu.history') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.activity') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="/pages/leadership.php" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.leadership') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.position') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.structure') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.anthem') ?>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav__item nav__item--has-submenu">
                        <span class="nav__link" data-i18n="">
                            <?= t('header.menu.directions') ?>
                        </span>
                        <svg class="nav__link-svg" width="10" height="8" viewBox="0 0 10 8" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 8L9.33013 0.5H0.669873L5 8Z" fill="white"/>
                        </svg>
                        <ul class="nav__submenu">
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.gr_partner') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.land_survey') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.monitoring') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.edition') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.intern_activity') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.procurement') ?>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav__item nav__item--has-submenu">
                        <span class="nav__link" data-i18n="">
                            <?= t('header.menu.press_center') ?>
                        </span>
                        <svg class="nav__link-svg" width="10" height="8" viewBox="0 0 10 8" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 8L9.33013 0.5H0.669873L5 8Z" fill="white"/>
                        </svg>
                        <ul class="nav__submenu">
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link" data-i18n="">
                                    <?= t('header.menu.news') ?>
                                </a>
                            </li>
                            <li class="nav__submenu__item">
                                <a href="#" class="nav__submenu__link">
                                    <?= t('header.menu.photo_gal') ?>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav__item">
                        <a href="#" class="nav__link" data-i18n="">
                            <?= t('header.menu.center_du') ?>
                        </a>
                    </li>

                    <li class="nav__item">
                        <a href="#" class="nav__link" data-i18n="">
                            <?= t('header.menu.npa') ?>
                        </a>
                    </li>

                    <li class="nav__item">
                        <a href="/pages/contacts.php" class="nav__link" data-i18n="">
                            <?= t('header.menu.contact') ?>
                        </a>
                    </li>

                </ul>
                <div class="mobile-menu" id="mobile-menu">
                    <div class="mobile-menu__header">
                        <a href="/pages/index.php" class="mobile-menu__logo">
                            <img src="/img/logo.svg" width="50" height="50" alt="Логотип">
                            <span>Інститут охорони ґрунтів України</span>
                        </a>
                    </div>
                    <div class="mobile-menu__content">
                        <nav class="mobile-nav">
                            <ul class="mobile-nav__list">
                                <li class="mobile-nav__item has-submenu">
                                    <div class="mobile-nav__link-wrapper">
                                        <span><?= t('header.menu.about') ?></span>
                                        <svg class="mobile-nav__arrow" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="m9.4 18.4-.7-.7 5.6-5.6-5.7-5.7.7-.7 6.4 6.4-6.3 6.3z"/>
                                        </svg>
                                    </div>
                                    <ul class="mobile-nav__submenu">
                                        <li><a href="#"><?= t('header.menu.history') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.activity') ?></a></li>
                                        <li><a href="/pages/leadership.php"><?= t('header.menu.leadership') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.position') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.structure') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.anthem') ?></a></li>
                                    </ul>
                                </li>
                                <li class="mobile-nav__item has-submenu">
                                    <div class="mobile-nav__link-wrapper">
                                        <span><?= t('header.menu.directions') ?></span>
                                        <svg class="mobile-nav__arrow" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="m9.4 18.4-.7-.7 5.6-5.6-5.7-5.7.7-.7 6.4 6.4-6.3 6.3z"/>
                                        </svg>
                                    </div>
                                    <ul class="mobile-nav__submenu">
                                        <li><a href="#"><?= t('header.menu.gr_partner') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.land_survey') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.monitoring') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.edition') ?></a></li>
                                        <li><a href="#"><?= t('header.intern_activity') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.procurement') ?></a></li>
                                    </ul>
                                </li>
                                <li class="mobile-nav__item has-submenu">
                                    <div class="mobile-nav__link-wrapper">
                                        <span><?= t('header.menu.press_center') ?></span>
                                        <svg class="mobile-nav__arrow" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="m9.4 18.4-.7-.7 5.6-5.6-5.7-5.7.7-.7 6.4 6.4-6.3 6.3z"/>
                                        </svg>
                                    </div>
                                    <ul class="mobile-nav__submenu">
                                        <li><a href="#"><?= t('header.menu.news') ?></a></li>
                                        <li><a href="#"><?= t('header.menu.photo_gal') ?></a></li>
                                    </ul>
                                </li>
                                <li class="mobile-nav__item"><a href="#"
                                                                class="mobile-nav__link"><?= t('header.menu.center_du') ?></a>
                                </li>
                                <li class="mobile-nav__item"><a href="#"
                                                                class="mobile-nav__link"><?= t('header.menu.npa') ?></a>
                                </li>
                                <li class="mobile-nav__item"><a href="/pages/contacts.php"
                                                                class="mobile-nav__link"><?= t('header.menu.contact') ?></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="mobile-menu__footer">
                        <ul class="mobile-menu__social">
                            <li class="header-up__social-item"><a href="#" class="header-up__social-link">
                                    <svg class="header-social" width="30" height="30"><!-- ... --></svg>
                                </a></li>
                            <li class="header-up__social-item"><a href="#" class="header-up__social-link">
                                    <svg class="header-social" width="30" height="30"><!-- ... --></svg>
                                </a></li>
                            <li class="header-up__social-item"><a href="#" class="header-up__social-link">
                                    <svg class="header-social" width="30" height="30"><!-- ... --></svg>
                                </a></li>
                            <li class="header-up__social-item"><a href="#" class="header-up__social-link">
                                    <svg class="header-social" width="30" height="30"><!-- ... --></svg>
                                </a></li>
                        </ul>
                        <div class="mobile-menu__contacts">
                            <a href="tel:0443565321">(044) 356-53-21 <span>Приймальня</span></a>
                            <a href="tel:0443565325">(044) 356-53-25 <span>Бухгалтерія</span></a>
                            <a href="tel:0443565324">(044) 356-53-24 <span>Відділ кадрів</span></a>
                            <a href="https://maps.app.goo.gl/op2ercbCK8irgzFv7">м. Київ, провулок Сеньківський, 3</a>
                        </div>
                    </div>
                </div>
            </nav>
            <ul class="lang-list">
                <li class="lang-item header-link <?php if (is_lang_active('uk')) echo 'active'; ?>" data-lang="uk">Укр
                </li>
                <li class="lang-item header-link <?php if (is_lang_active('en')) echo 'active'; ?>" data-lang="en">Eng
                </li>
            </ul>
            <button class="burger" aria-label="Відкрити меню">
                <span class="burger__line"></span>
                <span class="burger__line"></span>
                <span class="burger__line"></span>
            </button>
        </div>

    </div>
</header>

