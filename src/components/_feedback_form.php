<?php
// src/components/_feedback_form.php

// Отримуємо ключ капчі з налаштувань
$siteKey = '';
try {
    if (!isset($db)) $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_site_key'");
    $siteKey = $stmt->fetchColumn();
} catch (Exception $e) {}

// Визначаємо назву сторінки
$pageName = isset($fromPage) ? $fromPage : ($pageTitle ?? 'Сайт');
?>

<div class="feedback-widget">
    <h3>Зворотній зв'язок</h3>
    <form id="feedbackForm" onsubmit="submitFeedback(event)">

        <!-- Приховані поля -->
        <input type="hidden" name="page_title" value="<?= htmlspecialchars($pageName) ?>">
        <input type="hidden" name="source_url" value="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">

        <div class="form-group">
            <input type="text" name="name" placeholder="Ваше ім'я *" required class="fb-input">
        </div>

        <div class="form-group">
            <!-- Маска телефону -->
            <input type="tel" name="phone" id="phoneInput" placeholder="+38 (0__) ___-__-__" class="fb-input">
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" class="fb-input">
        </div>

        <div class="form-group">
            <textarea name="message" placeholder="Ваше повідомлення..." class="fb-textarea"></textarea>
        </div>

        <?php if ($siteKey): ?>
            <div class="form-group" style="margin-bottom:15px;">
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($siteKey) ?>"></div>
            </div>
        <?php endif; ?>

        <button type="submit" class="fb-btn">Надіслати</button>
        <div id="fbResult" class="fb-result"></div>
    </form>
</div>

<style>
    .feedback-widget { background: #f9f9f9; padding: 25px; border-radius: 8px; border: 1px solid #eee; margin-top: 30px; }
    .feedback-widget h3 { margin-top: 0; margin-bottom: 20px; color: #333; font-size: 1.5rem; }
    .form-group { margin-bottom: 15px; }
    .fb-input, .fb-textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 16px; outline: none; transition: border-color 0.3s; }
    .fb-input:focus, .fb-textarea:focus { border-color: #3498db; }
    .fb-textarea { min-height: 100px; resize: vertical; }
    .fb-btn { background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: 0.3s; width: 100%; font-weight: bold; }
    .fb-btn:hover { background: #219150; }
    .fb-btn:disabled { background: #95a5a6; cursor: not-allowed; }
    .fb-result { margin-top: 15px; font-weight: bold; padding: 10px; border-radius: 4px; display: none; text-align: center; }
    .fb-success { background-color: #d5f5e3; color: #27ae60; border: 1px solid #c3e6cb; }
    .fb-error { background-color: #fadbd8; color: #e74c3c; border: 1px solid #f5c6cb; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phoneInput');

        if (phoneInput) {
            // При фокусі додаємо префікс, якщо пусте
            phoneInput.addEventListener('focus', function() {
                if (!this.value) this.value = '+38 (0';
            });

            // При втраті фокусу, якщо там тільки префікс - очищаємо (щоб не відправляти пустий номер)
            phoneInput.addEventListener('blur', function() {
                if (this.value === '+38 (0') this.value = '';
            });

            phoneInput.addEventListener('input', function (e) {
                let input = e.target.value.replace(/\D/g, ''); // Залишаємо тільки цифри
                let formatted = '';

                // Якщо користувач стирає, даємо йому стерти, але не код країни
                if (!input) {
                    e.target.value = '';
                    return;
                }

                // Гарантуємо, що починається з 380
                if (input.substring(0, 2) === '38') {
                    // Все ок
                } else if (input.substring(0, 1) === '0') {
                    input = '38' + input; // Перетворюємо 067 в 38067
                } else {
                    input = '38' + input; // Додаємо префікс
                }

                // Обрізаємо до макс довжини (38 + 10 цифр = 12)
                input = input.substring(0, 12);

                // Форматування: +38 (0XX) XXX-XX-XX
                if (input.length > 0) {
                    formatted = '+38';
                }
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

            // Заборона видалення префіксу Backspace-ом
            phoneInput.addEventListener('keydown', function(e) {
                // Дозволяємо стирати тільки якщо курсор після дужки або далі
                // Довжина "+38 (0" це 7 символів.
                if ((e.key === 'Backspace' || e.key === 'Delete') && this.value.length <= 7) {
                    e.preventDefault();
                    // Можна очистити повністю, якщо хочуть стерти префікс
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

        // Перевірка телефону перед відправкою (має бути повним)
        const phoneVal = form.querySelector('input[name="phone"]').value;
        // Повний формат "+38 (0XX) XXX-XX-XX" - це 19 символів
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
            // Додаємо g-recaptcha-response вручну, якщо FormData його не підхопила (іноді буває)
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
            btn.innerText = 'Надіслати';
        }
    }
</script>