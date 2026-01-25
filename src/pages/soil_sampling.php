<?php
require_once __DIR__ . '/../core/i18n.php';

$page_bundle = 'soil_sampling';

$page_title = '  Арбітражний відбір проб грунту на землях сільськогосподарського  призначення, очищених від вибухонебезпечних предметів | Інститут охорони ґрунтів України';
$page_description = 'Арбітражний відбір проб ґрунту на землях сільськогосподарського призначення, очищених від вибухонебезпечних предметів – Офіційна процедура контролю якості ґрунту після розмінування, необхідна для підтвердження його безпечності та придатності до сільськогосподарського використання.';

ob_start();
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Inter", "e-Ukraine", -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.3;
        color: #191919;
        background: #fff;
    }

    .main-container {
        max-width: 1400px;
        margin: 24px auto;
        background: #fff;
        box-shadow: 0 12px 8px 5px rgba(0, 0, 0, 0.2);
    }

    .container {
        max-width: 1360px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .section-title {
        font-size: clamp(24px, 16.842px + 1.053vw, 32px);
        color: #093f2a;
        margin-bottom: clamp(24px, -6.222px + 4.444vw, 56px);
        font-weight: 400;
        text-align: center;
        border-bottom: 2px solid #093f2a;
        padding-bottom: 20px;
    }

    .legal-links-section {
        margin: 30px 0 50px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .legal-link-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #093f2a;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }

    .legal-link-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #093f2a 0%, #3f7b63 100%);
        transition: all 0.3s ease;
    }

    .legal-link-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(9, 63, 42, 0.25);
        border-color: #3f7b63;
    }

    .legal-link-card:hover::before {
        width: 8px;
    }

    .legal-link-icon {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: #093f2a;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        color: #fff;
        font-size: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .legal-link-card:hover .legal-link-icon {
        background: #3f7b63;
        transform: rotate(10deg) scale(1.1);
    }

    .legal-link-title {
        font-size: 18px;
        font-weight: 600;
        color: #093f2a;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .legal-link-desc {
        font-size: 14px;
        color: #666;
        line-height: 1.5;
    }

    .documents-section {
        margin: 50px 0;
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        border: 2px solid #093f2a;
    }

    .documents-title {
        font-size: 24px;
        color: #093f2a;
        margin-bottom: 30px;
        font-weight: 600;
        text-align: center;
    }

    .documents-simple-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .documents-simple-list li {
        border-bottom: 1px solid #e9ecef;
    }

    .documents-simple-list li:last-child {
        border-bottom: none;
    }

    .document-link-simple {
        display: flex;
        align-items: center;
        padding: 20px;
        background: #fff;
        position: relative;
    }

    .document-link-simple::before {
        content: "•";
        margin-right: 15px;
        font-size: 20px;
        color: #093f2a;
    }

    .document-title {
        font-weight: 600;
        font-size: 18px;
    }

    .document-desc {
        font-size: 14px;
        color: #666;
        margin-left: 35px;
        font-style: italic;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin: 50px 0;
    }

    .info-box {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        border-left: 4px solid #093f2a;
    }

    .info-box h3 {
        color: #093f2a;
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .info-box p {
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 10px;
        color: #191919;
    }

    .info-box p:last-child {
        margin-bottom: 0;
    }

    .download-documents-list {
        margin-top: 20px;
    }

    .download-document-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        gap: 20px;
    }

    .download-document-item:last-of-type {
        border-bottom: none;
    }

    .download-doc-btn {
        display: inline-block;
        text-decoration: none;
        background: #093f2a;
        color: #fff;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .download-doc-btn:hover {
        background: #3f7b63;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(9, 63, 42, 0.3);
    }

    .download-all-wrapper {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #093f2a;
        text-align: center;
    }

    .download-all-btn {
        display: inline-block;
        text-decoration: none;
        background: linear-gradient(135deg, #093f2a 0%, #3f7b63 100%);
        color: #fff;
        padding: 16px 40px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(9, 63, 42, 0.3);
    }

    .download-all-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(9, 63, 42, 0.4);
    }

    @media (max-width: 897px) {
        .main-container {
            margin: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .documents-section {
            padding: 20px;
        }

        .download-document-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .download-doc-btn {
            width: 100%;
        }

        .legal-links-section {
            grid-template-columns: 1fr;
        }

        .legal-link-card {
            min-width: 100%;
        }
    }
</style>

<main id="main">
    <div class="main-container">
        <div class="container">
            <h1 class="section-title">
                Арбітражний відбір проб грунту на землях сільськогосподарського призначення, очищених від
                вибухонебезпечних предметів
            </h1>

            <div class="legal-links-section">
                <!-- 1. Постанова КМУ -->
                <a href="https://zakon.rada.gov.ua/laws/show/844-2025-%D0%BF#Text" target="_blank"
                   rel="noopener noreferrer" class="legal-link-card">
                    <div class="legal-link-icon">📋</div>
                    <div class="legal-link-title">Постанова КМУ № 844 від 14.07.2025</div>
                    <div class="legal-link-desc">Про затвердження Порядку відбору проб ґрунтів на землях
                        сільськогосподарського призначення, очищених від вибухонебезпечних предметів</div>
                </a>

                <!-- 2. Наказ 957 -->
                <a href="https://media.iogu.gov.ua/literatura/download/nakaz_957.pdf" target="_blank"
                   rel="noopener noreferrer" class="legal-link-card" download>
                    <div class="legal-link-icon">📄</div>
                    <div class="legal-link-title">Наказ № 957 від 30.10.2025 року</div>
                    <div class="legal-link-desc">Про затвердження Методичних рекомендацій щодо відбору проб ґрунту</div>
                </a>

                <!-- 3. Наказ 834 -->
                <a href="https://media.iogu.gov.ua/literatura/download/nakaz_834.pdf" target="_blank"
                   rel="noopener noreferrer" class="legal-link-card" download>
                    <div class="legal-link-icon">📄</div>
                    <div class="legal-link-title">Наказ № 834 від 21.10.2025</div>
                    <div class="legal-link-desc">Про уповноваження Державної установи «Інститут охорони ґрунтів України»
                        на відбір, зберігання та арбітражний аналіз проб ґрунту</div>
                </a>

                <!-- 4. Нормативи ГДК -->
                <a href="https://zakon.rada.gov.ua/laws/show/1325-2021-%D0%BF#Text" target="_blank"
                   rel="noopener noreferrer" class="legal-link-card">
                    <div class="legal-link-icon">⚗️</div>
                    <div class="legal-link-title">Нормативи ГДК небезпечних речовин у ґрунтах</div>
                    <div class="legal-link-desc">Постанова КМУ від 15.12.2021 № 1325</div>

                </a>
            </div>

            <div class="documents-section">
                <h2 class="documents-title">Обов'язкові вимоги:</h2>
                <ul class="documents-simple-list">
                    <li>
                        <div class="document-link-simple" style="cursor: default;">
                            <div>
                                <div class="document-title">Копія паспорта заявника (для фізичних осіб)</div>
                                <div class="document-desc">Документ, що посвідчує особу</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="document-link-simple" style="cursor: default;">
                            <div>
                                <div class="document-title">Копія установчих документів організації (для юридичних осіб)
                                </div>
                                <div class="document-desc">Статут та інші установчі документи</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="document-link-simple" style="cursor: default;">
                            <div>
                                <div class="document-title">Документи на право власності або право користування
                                    земельною ділянкою/ділянками</div>
                                <div class="document-desc">Правовстановлюючі документи на землю</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <h3>Термін розгляду</h3>
                    <p>Відповідно до абзацу другого пункту 9 Порядку відбору проб ґрунтів на землях
                        сільськогосподарського призначення, очищених від вибухонебезпечних предметів, затвердженого
                        постановою Кабінету Міністрів України від 14 липня 2025 р. № 844.</p>
                    <p>Виконавці робіт повідомляють ДУ "Держґрунтохорона" про намір щодо проведення робіт з відбору
                        первинної проби ґрунту на земельних ділянках <strong>не пізніше ніж за сім робочих днів</strong>
                        до дня проведення таких робіт.</p>
                </div>

                <div class="info-box">
                    <h3>Контактна інформація</h3>
                    <p><strong>Телефон:</strong> (044) 356-53-21</p>
                    <p><strong>Email:</strong> info@iogu.gov.ua</p>
                    <p><strong>Адреса:</strong> 03190, м. Київ, пров. Сеньківський, 3, корп. 3</p>
                    <p><strong>Час роботи:</strong> Пн-Пт: 9:00-17:00</p>
                </div>
            </div>

            <div class="documents-section" style="margin-top: 50px;">
                <h2 class="documents-title">Документи для завантаження:</h2>
                <div class="download-documents-list">
                    <!-- 1. Повідомлення -->
                    <div class="download-document-item">
                        <div class="download-doc-info">
                            <div class="document-title">Повідомлення</div>
                            <div class="document-desc">Офіційна форма повідомлення на проведення робіт</div>
                        </div>
                        <a href="https://media.iogu.gov.ua/literatura/download/povidomlennya.docx"
                           class="download-doc-btn" target="_blank" download>
                            Завантажити
                        </a>
                    </div>

                    <!-- 2. Договір -->
                    <div class="download-document-item">
                        <div class="download-doc-info">
                            <div class="document-title">Договір </div>
                            <div class="document-desc">на виконання робіт з відбору арбітражної проби ґрунту</div>
                        </div>
                        <a href="https://media.iogu.gov.ua/literatura/download/dogovir.docx" class="download-doc-btn"
                           target="_blank" download>
                            Завантажити
                        </a>
                    </div>

                    <!-- 3. Акт -->
                    <div class="download-document-item">
                        <div class="download-doc-info">
                            <div class="document-title">Акт</div>
                            <div class="document-desc">на виконання робіт з відбору арбітражної проби ґрунту</div>
                        </div>
                        <a href="https://media.iogu.gov.ua/literatura/download/akt vidboru.docx"
                           class="download-doc-btn" target="_blank" download>
                            Завантажити
                        </a>
                    </div>

                    <!-- 4. Протокол -->
                    <div class="download-document-item">
                        <div class="download-doc-info">
                            <div class="document-title">Протокол</div>
                            <div class="document-desc">Погодження ціни на виконання робіт з відбору арбітражної проби ґрунту</div>
                        </div>
                        <a href="https://media.iogu.gov.ua/literatura/download/protocol.docx" class="download-doc-btn"
                           target="_blank" download>
                            Завантажити
                        </a>
                    </div>

                    <div class="download-all-wrapper">
                        <a href="https://media.iogu.gov.ua/literatura/download/all_documents.zip"
                           class="download-all-btn" target="_blank" download>
                            Завантажити всі документи
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php
$page_content = ob_get_clean();
require_once __DIR__ . '/../core/page_template.php';
?>
ƒ