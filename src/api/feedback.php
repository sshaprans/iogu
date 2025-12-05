<?php
// src/api/feedback.php
require_once __DIR__ . '/../core/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error' => 'Method Not Allowed']); exit;
}

$name = trim($_POST['name'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$message = trim($_POST['message'] ?? '');
$sourceUrl = trim($_POST['source_url'] ?? '');
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (!$name || !$contact) {
    echo json_encode(['status' => 'error', 'message' => 'Заповніть обов\'язкові поля']); exit;
}

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->query("SELECT value_uk FROM settings WHERE key_name = 'recaptcha_secret_key'");
    $secretKey = $stmt->fetchColumn();

    if ($secretKey) {
        if (empty($recaptchaResponse)) {
            echo json_encode(['status' => 'error', 'message' => 'Будь ласка, підтвердіть, що ви не робот']); exit;
        }

        $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}";
        $verify = json_decode(file_get_contents($verifyUrl));

        if (!$verify->success) {
            echo json_encode(['status' => 'error', 'message' => 'Помилка reCAPTCHA. Спробуйте ще раз.']); exit;
        }
    }

    $branchId = null;
    if ($sourceUrl) {
        $branches = $db->query("SELECT id, slug FROM branches")->fetchAll();
        foreach ($branches as $branch) {
            if (strpos($sourceUrl, $branch['slug']) !== false) {
                $branchId = $branch['id']; break;
            }
        }
    }

    $stmt = $db->prepare("INSERT INTO messages (name, contact, message, source_url, branch_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $contact, $message, $sourceUrl, $branchId]);

    echo json_encode(['status' => 'success', 'message' => 'Повідомлення надіслано!']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error']);
}
?>