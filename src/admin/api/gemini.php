<?php
// src/admin/api/gemini.php
require_once __DIR__ . '/../../core/auth.php';
require_once __DIR__ . '/../../core/logger.php';
require_once __DIR__ . '/../../core/db.php';

Auth::requireLogin();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (!$message) {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

//  API ключ
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT value_uk FROM settings WHERE key_name = 'gemini_api_key'");
$stmt->execute();
$apiKey = trim($stmt->fetchColumn());

if (!$apiKey) {
    echo json_encode(['reply' => '⚠️ API Key не знайдено в базі (settings -> gemini_api_key).']);
    exit;
}

// контекст
$user = Auth::user();
$logs = Logger::getLogs(10);
$logsText = "";
foreach ($logs as $log) {
    $date = date('H:i d.m', strtotime($log['created_at']));
    $logsText .= "- [$date] {$log['user_name']} ({$log['user_role']}) зробив '{$log['action']}' у '{$log['entity_type']}': {$log['details']}\n";
}

$systemPrompt = "Ти - корисний асистент для адмін-панелі держ установи Інститут Охорони Грунтів. Ти можеш допомогти розібратись з адмін панеллю. 
Користувач: {$user['name']} ({$user['role']}).
Останні події в системі:
$logsText
Відповідай українською. При заповненні тексту з перекладом, пропонуй кращій варіант.";

// 3. Запит до API
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";

$data = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $systemPrompt . "\n\nЗапит: " . $message]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode(['reply' => "❌ Помилка cURL: " . $curlError]);
    exit;
}

$decoded = json_decode($response, true);

if ($httpCode !== 200) {
    $errorMsg = $decoded['error']['message'] ?? 'Невідома помилка API';
    $errorCode = $decoded['error']['code'] ?? $httpCode;
    $errorStatus = $decoded['error']['status'] ?? 'UNKNOWN';
    echo json_encode(['reply' => "❌ Google API Error ($errorCode): $errorMsg"]);
    exit;
}

$aiText = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? 'ШІ мовчить.';
$aiText = nl2br(htmlspecialchars($aiText));
$aiText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $aiText);

echo json_encode(['reply' => $aiText]);