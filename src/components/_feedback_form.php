<?php
// src/components/_feedback_form.php

// Отримуємо ключ капчі, якщо він є
$siteKey = '';
try {
    if (!isset($db)) $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_site_key'");
    $siteKey = $stmt->fetchColumn();
} catch (Exception $e) {}

// Визначаємо назву сторінки для адмінки
$pageName = isset($fromPage) ? $fromPage : ($pageTitle ?? 'Сайт');
?>

<div class="feedback-widget">
    <h3 class="feedback-title"><?= t('feedback_title_contact')?></h3>
    <p class="feedback-subtitle"><?= t('feedback_subtitle_contact')?></p>

    <form id="feedbackForm" class="feedback-form" onsubmit="submitFeedback(event)">

        <!-- Приховані поля для аналітики -->
        <input type="hidden" name="page_title" value="<?= htmlspecialchars($pageName) ?>">
        <input type="hidden" name="source_url" value="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">

        <div class="form-group">
            <label for="name" class="form-label">Ваше ім'я *</label>
            <input type="text" name="name" id="name" placeholder="Введіть ім'я" required class="fb-input">
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label for="phoneInput" class="form-label">Телефон</label>
                <input type="tel" name="phone" id="phoneInput" placeholder="+38 (0__) ___-__-__" class="fb-input">
            </div>
            <div class="form-group half">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="mail@example.com" class="fb-input">
            </div>
        </div>

        <div class="form-group">
            <label for="message" class="form-label">Повідомлення</label>
            <textarea name="message" id="message" placeholder="Ваше запитання..." class="fb-textarea"></textarea>
        </div>

        <?php if ($siteKey): ?>
            <div class="form-group recaptcha-wrapper">
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($siteKey) ?>"></div>
            </div>
        <?php endif; ?>

        <button type="submit" class="fb-btn">Надіслати повідомлення</button>

        <div id="fbResult" class="fb-result"></div>
    </form>
</div>

<style>
    .feedback-widget {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #eef2f5;
        max-width: 600px;
        margin: 30px auto;
    }
    .feedback-title {
        margin-top: 0;
        margin-bottom: 10px;
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
    }
    .feedback-subtitle {
        text-align: center;
        color: #7f8c8d;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-row {
        display: flex;
        gap: 15px;
    }
    .form-row .half {
        width: 50%;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #34495e;
    }
    .fb-input, .fb-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #dfe6e9;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 16px;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
        background: #fdfdfd;
    }
    .fb-input:focus, .fb-textarea:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        background: #fff;
    }
    .fb-textarea {
        min-height: 120px;
        resize: vertical;
    }
    .fb-btn {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        color: white;
        padding: 14px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        width: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .fb-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
    }
    .fb-btn:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    .fb-result {
        margin-top: 15px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: none;
        text-align: center;
        font-size: 0.95rem;
    }
    .fb-success {
        background-color: #d5f5e3;
        color: #218838;
        border: 1px solid #c3e6cb;
    }
    .fb-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .recaptcha-wrapper {
        display: flex;
        justify-content: center;
    }
    @media (max-width: 600px) {
        .form-row { flex-direction: column; gap: 0; }
        .form-row .half { width: 100%; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phoneInput');

        if (phoneInput) {
            // Встановлюємо початкове значення при фокусі
            phoneInput.addEventListener('focus', function() {
                if(!this.value) this.value = '+38 (0';
            });

            // Очищаємо, якщо нічого не ввели
            phoneInput.addEventListener('blur', function() {
                if (this.value === '+38 (0') this.value = '';
            });

            phoneInput.addEventListener('input', function (e) {
                // Видаляємо все, що не є цифрою
                let input = e.target.value.replace(/\D/g, '');

                // Якщо користувач видалив усе
                if (!input) {
                    e.target.value = '';
                    return;
                }

                // Гарантуємо, що початок завжди 38
                if (input.substring(0, 2) !== '38') {
                    if (input.substring(0, 1) === '0') {
                        input = '38' + input; // 067 -> 38067
                    } else {
                        input = '38' + input; // 6 -> 386...
                    }
                }

                // Обрізаємо зайві цифри (макс 12)
                input = input.substring(0, 12);

                // Форматування: +38 (0XX) XXX-XX-XX
                let formatted = '+38';
                if (input.length > 2) {
                    formatted += ' (' + input.substring(2, 5);
                }
                if (input.length >= 5) {
                    formatted += ') ' + input.substring(5, 8);
                }
                if (input.length >= 8) {
                    formatted += '-' + input.substring(8, 10);
                }
                if (input.length >= 10) {
                    formatted += '-' + input.substring(10, 12);
                }

                e.target.value = formatted;
            });

            // Забороняємо видаляти +38 (0
            phoneInput.addEventListener('keydown', function(e) {
                if ((e.key === 'Backspace' || e.key === 'Delete') && this.value.length <= 7) {
                    e.preventDefault();
                    if (this.value.length <= 7) this.value = '';
                }
            });
        }
    });

    async function submitFeedback(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('.fb-btn');
        const resultDiv = document.getElementById('fbResult');

        // Валідація телефону
        const phoneVal = form.querySelector('input[name="phone"]').value;
        const emailVal = form.querySelector('input[name="email"]').value;

        if (!phoneVal && !emailVal) {
            alert("Будь ласка, вкажіть хоча б один контакт (телефон або пошту).");
            return;
        }

        if (phoneVal && phoneVal.length < 19) {
            alert("Будь ласка, введіть повний номер телефону.");
            return;
        }

        btn.disabled = true;
        btn.innerText = 'Надсилання...';
        resultDiv.style.display = 'none';
        resultDiv.className = 'fb-result';

        try {
            const formData = new FormData(form);
            if (window.grecaptcha) {
                formData.set('g-recaptcha-response', grecaptcha.getResponse());
            }

            const response = await fetch('/api/feedback.php', { method: 'POST', body: formData });
            const data = await response.json();

            resultDiv.style.display = 'block';
            if (data.status === 'success') {
                resultDiv.classList.add('fb-success');
                resultDiv.innerText = data.message;
                form.reset();
                if (window.grecaptcha) grecaptcha.reset();
            } else {
                resultDiv.classList.add('fb-error');
                resultDiv.innerText = data.message || 'Сталася помилка';
            }
        } catch (error) {
            resultDiv.style.display = 'block';
            resultDiv.classList.add('fb-error');
            resultDiv.innerText = 'Помилка з\'єднання з сервером';
            console.error(error);
        } finally {
            btn.disabled = false;
            btn.innerText = 'Надіслати повідомлення';
        }
    }
</script>