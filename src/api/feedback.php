<?php
// src/api/feedback.php

// Підключаємо БД
if (file_exists(__DIR__ . '/../core/db.php')) {
    require_once __DIR__ . '/../core/db.php';
} elseif (file_exists(__DIR__ . '/../core/Database.php')) {
    require_once __DIR__ . '/../core/Database.php';
}

header('Content-Type: application/json');

function jsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

// Дозволяємо тільки POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Method not allowed');
}

try {
    $db = Database::getInstance()->getConnection();

    // 1. Отримуємо та очищаємо дані
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $sourceUrl = trim($_POST['source_url'] ?? '');
    $pageTitle = trim($_POST['page_title'] ?? 'Сайт');
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // 2. Валідація
    if (empty($name)) {
        jsonResponse('error', 'Будь ласка, введіть ваше ім\'я.');
    }

    // Перевірка: має бути хоча б один контакт
    if (empty($phone) && empty($email)) {
        jsonResponse('error', 'Будь ласка, вкажіть телефон або Email для зв\'язку.');
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse('error', 'Невірний формат Email адреси.');
    }

    // Формуємо єдиний рядок контакту для таблиці messages
    $contactParts = [];
    if ($phone) $contactParts[] = "Tel: $phone";
    if ($email) $contactParts[] = "Email: $email";
    $contactFinal = implode(', ', $contactParts);


    // 3. Перевірка reCAPTCHA (якщо налаштована)
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_secret_key'");
    $secretKey = $stmt->fetchColumn();

    if ($secretKey) {
        if (empty($recaptchaResponse)) {
            jsonResponse('error', 'Будь ласка, підтвердіть, що ви не робот.');
        }

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret' => $secretKey, 'response' => $recaptchaResponse];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        $verifyResult = file_get_contents($verifyUrl, false, $context);
        $jsonResult = json_decode($verifyResult);

        if (!$jsonResult->success) {
            jsonResponse('error', 'Помилка перевірки reCAPTCHA. Спробуйте ще раз.');
        }
    }

    // 4. Збереження у БД (таблиця messages)
    // Переконайтеся, що в таблиці messages є колонка page_title. Якщо ні - виконайте SQL з попередніх кроків.
    $stmt = $db->prepare("INSERT INTO messages (name, contact, message, source_url, page_title, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
    $stmt->execute([$name, $contactFinal, $message, $sourceUrl, $pageTitle]);

    jsonResponse('success', 'Дякуємо! Ваше повідомлення успішно надіслано.');

} catch (Exception $e) {
    // Для дебагу можна розкоментувати:
    // jsonResponse('error', 'Помилка: ' . $e->getMessage());
    jsonResponse('error', 'Виникла помилка на сервері. Спробуйте пізніше.');
}