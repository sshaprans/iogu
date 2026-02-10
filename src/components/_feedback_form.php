<?php
// src/components/_feedback_form.php

// Отримуємо ключ капчі
$siteKey = '';
try {
    if (!isset($db)) $db = Database::getInstance()->getConnection(); // Перестраховка
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_site_key'");
    $siteKey = $stmt->fetchColumn();
} catch (Exception $e) {}

// Визначаємо назву сторінки: пріоритет переданому параметру fromPage, потім page_title, потім URL
$pageName = isset($fromPage) ? $fromPage : ($pageTitle ?? 'Сайт');
?>

<div class="feedback-widget">
    <h3>Зворотній зв'язок</h3>
    <form id="feedbackForm" onsubmit="submitFeedback(event)">

        <!-- Приховане поле для назви сторінки -->
        <input type="hidden" name="page_title" value="<?= htmlspecialchars($pageName) ?>">
        <input type="hidden" name="source_url" value="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">

        <div class="form-group">
            <input type="text" name="name" placeholder="Ваше ім'я" required class="fb-input">
        </div>

        <div class="form-group">
            <!-- Змінено placeholder на потрібний формат -->
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
    .feedback-widget h3 { margin-top: 0; margin-bottom: 20px; color: #333; }
    .fb-input, .fb-textarea { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .fb-textarea { min-height: 100px; resize: vertical; }
    .fb-btn { background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: 0.3s; }
    .fb-btn:hover { background: #219150; }
    .fb-btn:disabled { background: #ccc; cursor: not-allowed; }
    .fb-result { margin-top: 15px; font-weight: bold; display: none; }
    .fb-success { color: #27ae60; }
    .fb-error { color: #e74c3c; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phoneInput');
        if(phoneInput) {
            // Встановлюємо початкове значення при фокусі
            phoneInput.addEventListener('focus', function() {
                if(!this.value) this.value = '+38 (0';
            });

            phoneInput.addEventListener('input', function (e) {
                // Видаляємо все, що не є цифрою
                let input = e.target.value.replace(/\D/g, '');

                // Якщо користувач видалив усе, залишаємо пусто (або можна повернути +38)
                if (!input) {
                    e.target.value = '';
                    return;
                }

                // Гарантуємо, що початок завжди 38
                if (input.substring(0, 2) !== '38') {
                    // Якщо користувач ввів 067..., додаємо 38
                    if (input.substring(0, 1) === '0') {
                        input = '38' + input;
                    } else {
                        // Якщо щось інше, просто ставимо 38 на початок
                        input = '38' + input;
                    }
                }

                // Обрізаємо зайві цифри (макс 12 цифр: 38 + 10 цифр номера)
                input = input.substring(0, 12);

                // Форматуємо
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

            // Забороняємо видаляти +38 (опціонально, для кращого UX)
            phoneInput.addEventListener('keydown', function(e) {
                if ((e.key === 'Backspace' || e.key === 'Delete') && this.value.length <= 5) {
                    e.preventDefault();
                    this.value = '+38 (0';
                }
            });
        }
    });

    async function submitFeedback(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('.fb-btn');
        const resultDiv = document.getElementById('fbResult');

        btn.disabled = true;
        btn.innerText = 'Надсилання...';
        resultDiv.style.display = 'none';

        try {
            const formData = new FormData(form);
            const response = await fetch('/api/feedback.php', { method: 'POST', body: formData });
            const data = await response.json();

            resultDiv.style.display = 'block';
            if (data.status === 'success') {
                resultDiv.className = 'fb-result fb-success';
                resultDiv.innerText = data.message;
                form.reset();
                if (window.grecaptcha) grecaptcha.reset();
            } else {
                resultDiv.className = 'fb-result fb-error';
                resultDiv.innerText = data.message || 'Помилка';
            }
        } catch (error) {
            resultDiv.style.display = 'block';
            resultDiv.className = 'fb-result fb-error';
            resultDiv.innerText = 'Помилка з\'єднання';
        } finally {
            btn.disabled = false;
            btn.innerText = 'Надіслати';
        }
    }
</script>