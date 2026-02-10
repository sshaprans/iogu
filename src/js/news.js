import './main';

$(document).ready(function() {
    // Функція для універсальної ініціалізації слайдерів
    function initializeGallerySwipers(baseClass) {
        const swiperThumbs = new Swiper(`.${baseClass}-thumbs`, {
            loop: true,
            spaceBetween: 5,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            breakpoints: {
                680: {
                    slidesPerView: 13,
                }
            },
            // Запобігаємо ініціалізації, якщо елемент не існує
            on: {
                init: function () {
                    if (this.slides.length === 0) {
                        this.destroy();
                        console.warn(`[Swiper Init] Мініатюри не знайдені для: .${baseClass}-thumbs. Ініціалізація скасована.`);
                    }
                }
            }
        });

        // 2. Ініціалізація головного слайдера
        const swiperMain = new Swiper(`.${baseClass}-main`, {
            loop: true,
            spaceBetween: 10,
            // autoplay: {
            //     delay: 3500,
            //     disableOnInteraction: false,
            //     pauseOnMouseEnter: true,
            // },
            navigation: {
                // Використовуйте загальні класи для кнопок, або зробіть їх також динамічними, якщо потрібно
                nextEl: `.${baseClass}-main .swiper-button-next`,
                prevEl: `.${baseClass}-main .swiper-button-prev`,
            },
            // Встановлюємо зв'язок з мініатюрами
            thumbs: {
                swiper: swiperThumbs,
            },
            // Запобігаємо ініціалізації, якщо елемент не існує
            on: {
                init: function () {
                    if (this.slides.length === 0) {
                        this.destroy();
                        console.warn(`[Swiper Init] Основний слайдер не знайдений для: .${baseClass}-main. Ініціалізація скасована.`);
                    }
                }
            }
        });
    }

    initializeGallerySwipers('photo_gallery-swiper3');
    initializeGallerySwipers('photo_gallery-swiper2');


    // Якщо у вас є інші набори (наприклад, swiper1, swiper2), ви викликаєте їх тут:
    // initializeGallerySwipers('photo_gallery-swiper2');
    // initializeGallerySwipers('photo_gallery-swiper-default');

});