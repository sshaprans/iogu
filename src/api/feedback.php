<?php
// src/api/feedback.php
require_once __DIR__ . '/../core/db.php';

header('Content-Type: application/json');

// Функція для відповіді JSON
function jsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Method not allowed');
}

try {
    $db = Database::getInstance()->getConnection();

    // 1. Отримуємо дані
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $sourceUrl = trim($_POST['source_url'] ?? '');
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // 2. Валідація полів
    if (empty($name) || empty($contact)) {
        jsonResponse('error', 'Будь ласка, заповніть ім\'я та контакти.');
    }

    // 3. Перевірка reCAPTCHA
    // Отримуємо секретний ключ з налаштувань
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_secret_key'");
    $secretKey = $stmt->fetchColumn();

    if ($secretKey) {
        if (empty($recaptchaResponse)) {
            jsonResponse('error', 'Будь ласка, пройдіть перевірку "Я не робот".');
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

    // 4. Збереження у БД
    // Припускаємо, що таблиця messages має поля: name, contact, message, source_url, status, created_at
    $stmt = $db->prepare("INSERT INTO messages (name, contact, message, source_url, status, created_at) VALUES (?, ?, ?, ?, 'new', NOW())");
    $stmt->execute([$name, $contact, $message, $sourceUrl]);

    jsonResponse('success', 'Дякуємо! Ваше повідомлення надіслано.');

} catch (Exception $e) {
    // Логування помилки на сервері, користувачу - загальне повідомлення
    // error_log($e->getMessage());
    jsonResponse('error', 'Помилка сервера. Спробуйте пізніше.');
}