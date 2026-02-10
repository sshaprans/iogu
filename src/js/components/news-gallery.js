document.addEventListener('DOMContentLoaded', () => {
    // Елементи
    const gallery = document.getElementById('news-gallery');
    const listView = document.getElementById('news-list-view');
    const singleView = document.getElementById('single-news-container');
    const container = document.getElementById('news-container');
    const loadMoreBtn = document.getElementById('load-more-news');

    if (!gallery || !container) return;

    let isLoading = false;

    // --- 1. КНОПКА "БІЛЬШЕ" ---
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', async () => {
            if (loadMoreBtn.classList.contains('disabled') || isLoading) return;

            isLoading = true;
            const originalText = loadMoreBtn.innerText;
            loadMoreBtn.innerText = 'Завантаження...';

            const offset = parseInt(loadMoreBtn.getAttribute('data-offset')) || 6;
            const lang = gallery.getAttribute('data-lang') || 'uk';

            try {
                const res = await fetch(`/api/get_news.php?lang=${lang}&offset=${offset}`);
                const json = await res.json();

                if (json.status === 'success') {
                    const posts = json.data;
                    if (posts.length > 0) {
                        posts.forEach(post => {
                            const imgSrc = post.image ? post.image : '/img/no-image.png';
                            const html = `
                                <a href="${post.link}" class="news__gallery__block" data-id="${post.id}" style="animation: fadeIn 0.5s;">
                                    <h5 class="news__gallery__block-title">${post.title}</h5>
                                    <img src="${imgSrc}" loading="lazy" class="news__gallery__block-img" alt="${post.title}" width="420" height="350">
                                </a>
                            `;
                            container.insertAdjacentHTML('beforeend', html);
                        });
                        loadMoreBtn.setAttribute('data-offset', offset + posts.length);
                        loadMoreBtn.innerText = originalText;
                    }
                    if (posts.length < 6) {
                        loadMoreBtn.classList.add('disabled');
                        loadMoreBtn.innerText = 'Більше немає';
                        loadMoreBtn.style.opacity = '0.5';
                        loadMoreBtn.style.cursor = 'default';
                    }
                } else {
                    loadMoreBtn.innerText = originalText;
                }
            } catch (e) {
                console.error(e);
                loadMoreBtn.innerText = originalText;
            } finally {
                isLoading = false;
            }
        });
    }

    // --- 2. ВІДКРИТТЯ НОВИНИ (Клік) ---
    container.addEventListener('click', async (e) => {
        const card = e.target.closest('.news__gallery__block');
        if (card) {
            e.preventDefault();

            const newsId = card.getAttribute('data-id');
            const targetUrl = card.getAttribute('href'); // Отримуємо посилання (наприклад /news/slug)
            const lang = gallery.getAttribute('data-lang') || 'uk';

            if (!newsId) return;

            // Змінюємо URL в браузері без перезавантаження
            if (targetUrl) {
                history.pushState({ view: 'single', id: newsId }, '', targetUrl);
            }

            openSingleView(newsId, lang);
        }
    });

    // Функція завантаження та показу новини
    async function openSingleView(newsId, lang) {
        listView.style.display = 'none';
        singleView.style.display = 'block';
        singleView.innerHTML = '<div style="padding:50px; text-align:center;">Завантаження...</div>';

        gallery.scrollIntoView({ behavior: 'smooth' });

        try {
            const res = await fetch(`/api/get_single_news.php?id=${newsId}&lang=${lang}`);
            const json = await res.json();
            if (json.status === 'success') {
                renderSingle(json.data);
            } else {
                singleView.innerHTML = '<div style="padding:20px; color:red; text-align:center;">Помилка: новину не знайдено.<br><button class="btn-back-to-list news-back-btn">Назад</button></div>';
                setupBackButton();
            }
        } catch (e) {
            singleView.innerHTML = '<div style="padding:20px; text-align:center;">Помилка з\'єднання.<br><button class="btn-back-to-list news-back-btn">Назад</button></div>';
            setupBackButton();
        }
    }

    function renderSingle(post) {
        // Галерея
        let galleryHtml = '';
        if (post.header_images && Array.isArray(post.header_images) && post.header_images.length > 0) {
            post.header_images.forEach(imgSrc => {
                galleryHtml += `<img src="${imgSrc}" loading="lazy" class="news__gallery__post-img" alt="${post.title}" style="max-width: 100%; height: auto; margin-bottom: 10px; border-radius: 8px; display: block;">`;
            });
        } else if (post.image) {
            galleryHtml = `<img src="${post.image}" loading="lazy" class="news__gallery__post-img" alt="${post.title}" style="max-width: 100%; height: auto; margin-bottom: 20px; border-radius: 8px; display: block;">`;
        }

        const html = `
            <div class="news__gallery__post" id="post-${post.id}" style="animation: fadeIn 0.5s;">
                 <div style="margin-bottom: 20px;">
                    <button class="btn-back-to-list news-back-btn download-doc-btn">&larr; До списку новин</button>
                </div>
                
                <h3 class="news__gallery__post-title">${post.title}</h3>

                ${galleryHtml}
                
                <div class="news__gallery__post-date">${post.formatted_date}</div>

                <div class="news__gallery__post__content">
                    ${post.content}
                </div>

                <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
                     <button class="btn-back-to-list news-back-btn download-doc-btn">&larr; Назад</button>
                </div>
            </div>
        `;

        singleView.innerHTML = html;
        setupBackButton();
    }

    function setupBackButton() {
        const btns = singleView.querySelectorAll('.btn-back-to-list');
        btns.forEach(btn => {
            btn.onclick = () => {
                goBackToList();
            };
        });
    }

    function goBackToList() {
        // Повертаємо URL назад на сторінку списку (наприклад /news або /uk/news)
        const lang = gallery.getAttribute('data-lang') || 'uk';
        const listUrl = (lang === 'en' ? '/en/news' : '/news');

        // Змінюємо URL
        history.pushState({ view: 'list' }, '', listUrl);

        // Перемикаємо вигляд
        singleView.style.display = 'none';
        singleView.innerHTML = '';
        listView.style.display = 'block';
    }

    // --- ОБРОБКА КНОПОК БРАУЗЕРА (Назад/Вперед) ---
    window.addEventListener('popstate', (event) => {
        // Якщо ми повернулися на URL списку (закінчується на /news або /news/)
        if (location.pathname.endsWith('/news') || location.pathname.endsWith('/news/')) {
            singleView.style.display = 'none';
            singleView.innerHTML = '';
            listView.style.display = 'block';
        } else {
            // Якщо ми перейшли кнопками браузера на конкретну новину - перезавантажуємо сторінку,
            // щоб PHP відрендерив її (найпростіший і надійний варіант для SSR + Ajax гібрида)
            location.reload();
        }
    });
});