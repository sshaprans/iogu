<?php
//  Database вже підключено у файлі, якщо ні - треба require_once __DIR__ . '/../core/db.php';
$siteKey = '';
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_site_key'");
    $siteKey = $stmt->fetchColumn();
} catch (Exception $e) {}
?>

<div class="feedback-widget">
    <h3>Зворотній зв'язок</h3>
    <form id="feedbackForm" onsubmit="submitFeedback(event)">
        <div class="form-group">
            <input type="text" name="name" placeholder="Ваше ім'я" required class="fb-input">
        </div>
        <div class="form-group">
            <input type="text" name="contact" placeholder="Телефон або Email" required class="fb-input">
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

        <input type="hidden" name="source_url" value="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">

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
            const response = await fetch('/api/feedback.php', {
                method: 'POST',
                body: formData
            });
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
            console.error(error);
        } finally {
            btn.disabled = false;
            btn.innerText = 'Надіслати';
        }
    }
</script>