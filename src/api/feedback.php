<?php
// src/api/feedback.php
require_once __DIR__ . '/../core/Database.php';

header('Content-Type: application/json');

function jsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Method not allowed');
}

try {
    $db = Database::getInstance()->getConnection();

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $sourceUrl = trim($_POST['source_url'] ?? '');
    $pageTitle = trim($_POST['page_title'] ?? 'Сайт');
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($name)) {
        jsonResponse('error', 'Будь ласка, введіть ваше ім\'я.');
    }

    if (empty($phone) && empty($email)) {
        jsonResponse('error', 'Будь ласка, вкажіть телефон або Email для зв\'язку.');
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse('error', 'Невірний формат Email адреси.');
    }

    $contactParts = [];
    if ($phone) $contactParts[] = "Tel: $phone";
    if ($email) $contactParts[] = "Email: $email";
    $contactFinal = implode(', ', $contactParts);

    $secretKey = config('recaptcha_secret_key');

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

        if (!$jsonResult || !$jsonResult->success) {
            jsonResponse('error', 'Помилка перевірки reCAPTCHA. Спробуйте ще раз.');
        }
    }

    $stmt = $db->prepare("INSERT INTO messages (name, contact, message, source_url, page_title, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
    $stmt->execute([$name, $contactFinal, $message, $sourceUrl, $pageTitle]);

    jsonResponse('success', 'Дякуємо! Ваше повідомлення успішно надіслано.');

} catch (Exception $e) {
    error_log("Feedback API Error: " . $e->getMessage());
    jsonResponse('error', 'Виникла помилка на сервері. Спробуйте пізніше.');
}