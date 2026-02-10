<?php
// src/api/feedback.php
require_once __DIR__ . '/../core/db.php';

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
    $pageTitle = trim($_POST['page_title'] ?? ''); // Отримуємо назву сторінки
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($name)) jsonResponse('error', 'Будь ласка, введіть ваше ім\'я.');
    if (empty($phone) && empty($email)) jsonResponse('error', 'Будь ласка, вкажіть телефон або Email.');
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse('error', 'Невірний формат Email.');

    $contactString = [];
    if ($phone) $contactString[] = "Tel: $phone";
    if ($email) $contactString[] = "Email: $email";
    $contactFinal = implode(', ', $contactString);

    // Перевірка reCAPTCHA (якщо є ключі)
    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_secret_key'");
    $secretKey = $stmt->fetchColumn();

    if ($secretKey) {
        if (empty($recaptchaResponse)) jsonResponse('error', 'Будь ласка, пройдіть перевірку "Я не робот".');
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret' => $secretKey, 'response' => $recaptchaResponse];
        $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)]];
        $context  = stream_context_create($options);
        $verifyResult = file_get_contents($verifyUrl, false, $context);
        $jsonResult = json_decode($verifyResult);
        if (!$jsonResult->success) jsonResponse('error', 'Помилка капчі.');
    }

    // Збереження page_title
    $stmt = $db->prepare("INSERT INTO messages (name, contact, message, source_url, page_title, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
    $stmt->execute([$name, $contactFinal, $message, $sourceUrl, $pageTitle]);

    jsonResponse('success', 'Дякуємо! Ваше повідомлення надіслано.');

} catch (Exception $e) {
    jsonResponse('error', 'Помилка сервера. Спробуйте пізніше.');
}