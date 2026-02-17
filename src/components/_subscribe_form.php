<?php
// src/components/_subscribe_form.php

// Назва сторінки
$pageName = isset($fromPage) ? $fromPage : ($pageTitle ?? 'Сайт');

// Модифікатор форми (якщо передано)
$mod = isset($formModify) ? $formModify : '';

// Формуємо ключі для перекладу
$titleKey = 'feedback_title_contact' . $mod;
$descKey = 'feedback_subtitle_contact' . $mod;

// Отримуємо значення перекладів.
// Якщо функція t() повертає ключ, якщо перекладу немає, то все ок.
// Якщо ні - можна додати перевірку або дефолтні значення.
$titleText = function_exists('t') ? t($titleKey) : 'Підписка на новини';
$descText = function_exists('t') ? t($descKey) : 'Отримуйте актуальні новини та оновлення на пошту.';

// Якщо переклад не знайдено (наприклад, t() повернув сам ключ), можна використати дефолтні
if ($titleText === $titleKey && empty($mod)) $titleText = 'Підписка на новини';
// (Це опціонально, залежить від того, як працює ваша функція t())

?>

<div class="subscribe-widget">
    <h4 class="sub-title"><?= $titleText ?></h4>
    <p class="sub-desc"><?= $descText ?></p>

    <form id="subscribeForm<?= $mod ?>" class="sub-form" onsubmit="submitSubscribe(event, '<?= $mod ?>')">
        <input type="hidden" name="form_type" value="subscribe">
        <input type="hidden" name="page_title" value="<?= htmlspecialchars($pageName) ?>">
        <input type="hidden" name="source_url" value="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">


        <div class="sub-group">
            <input type="text" name="name" id="name" placeholder="Введіть ім'я" required class="sub-input">
            <input type="email" name="email" placeholder="Ваш Email" required class="sub-input">
            <button type="submit" class="sub-btn">OK</button>
        </div>

        <div id="subResult<?= $mod ?>" class="sub-result"></div>
    </form>
</div>

<style>
    .subscribe-widget {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        margin-top: 20px;
    }
    .sub-title {
        margin: 0 0 5px;
        font-size: 1.1rem;
        color: #2c3e50;
    }
    .sub-desc {
        margin: 0 0 15px;
        font-size: 0.9rem;
        color: #7f8c8d;
    }
    .sub-group {
        display: flex;
        gap: 5px;
    }
    .sub-input {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
    }
    .sub-input:focus { border-color: #3498db; }
    .sub-btn {
        background: #2c3e50;
        color: white;
        border: none;
        padding: 0 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.2s;
    }
    .sub-btn:hover { background: #34495e; }
    .sub-result {
        margin-top: 10px;
        font-size: 0.85rem;
        display: none;
    }
    .sub-success { color: #27ae60; }
    .sub-error { color: #e74c3c; }
</style>

<script>
    // Оновлена функція, яка приймає mod (щоб знайти правильний div результату, якщо форм кілька)
    async function submitSubscribe(e, mod = '') {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('.sub-btn');
        // Шукаємо результат саме для цієї форми
        const res = document.getElementById('subResult' + mod);

        btn.disabled = true;
        btn.innerText = '...';
        res.style.display = 'none';

        try {
            const fd = new FormData(form);
            // Додаємо дефолтне повідомлення
            fd.append('message', 'Хочу отримувати новини (Підписка)');

            const req = await fetch('/api/feedback.php', { method: 'POST', body: fd });
            const data = await req.json();

            res.style.display = 'block';
            res.className = 'sub-result ' + (data.status === 'success' ? 'sub-success' : 'sub-error');
            res.innerText = data.message;

            if(data.status === 'success') form.reset();
        } catch (err) {
            res.style.display = 'block';
            res.className = 'sub-result sub-error';
            res.innerText = 'Помилка з\'єднання';
        } finally {
            btn.disabled = false;
            btn.innerText = 'OK';
        }
    }
</script>