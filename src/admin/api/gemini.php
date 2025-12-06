<?php
// src/admin/api/gemini.php
require_once __DIR__ . '/../../core/auth.php';
require_once __DIR__ . '/../../core/logger.php';
require_once __DIR__ . '/../../core/db.php';

Auth::requireLogin();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$history = $input['history'] ?? [];
$message = $input['message'] ?? '';

// 1. Отримання API ключа
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT value_uk FROM settings WHERE key_name = 'gemini_api_key'");
$stmt->execute();
$apiKey = trim($stmt->fetchColumn());

if (!$apiKey) {
    echo json_encode(['reply' => '⚠️ API Key не знайдено. Перевірте налаштування.']);
    exit;
}

$user = Auth::user();
$logs = Logger::getLogs(15); // Більше логів
$logsText = "";
foreach ($logs as $log) {
    $date = date('H:i d.m', strtotime($log['created_at']));
    $logsText .= "- [$date] {$log['user_name']} ({$log['user_role']}) -> {$log['action']}: {$log['details']}\n";
}

$systemPrompt = <<<PROMPT
ТИ — ІНТЕЛЕКТУАЛЬНИЙ АСИСТЕНТ ДЛЯ ДЕРЖУСТАНОВИ "ІНСТИТУТ ОХОРОНИ ГРУНТІВ".
ТВОЯ РОЛЬ:
Ти досвідчений адміністратор та бізнес-аналітик. Ти допомагаєш користувачу {$user['name']} ({$user['role']}).
ТВОЇ МОЖЛИВОСТІ ТА ІНСТРУКЦІЇ:
АНАЛІЗ ДАНИХ (Пріоритет):
- Якщо користувач надсилає статистику (Google Analytics, звіти), ти дієш як Бізнес-Аналітик.
- Шукай тренди, аномалії та давай конкретні поради щодо покращення показників.
- Не кажи "я не бачу даних", якщо вони є у повідомленні користувача.
ПРИВІТАННЯ (Автоматичне):
- Якщо це початок розмови, коротко привітайся.
- Нагадай, що ти можеш допомогти з перекладами, логами або аналізом статистики.
 ТЕХНІЧНА ПІДТРИМКА:
- Ось останні події в системі (Логи): $logsText
- Використовуй їх, щоб пояснити помилки, якщо користувач питає "що зламалось?".
ЛОКАЛІЗАЦІЯ:
- Якщо користувач просить текст, завжди думай про двомовність (UA/EN). Пропонуй переклади.
СТИЛЬ:
Відповідай українською. Будь лаконічним, професійним, використовуй форматування (жирний шрифт, списки).
PROMPT;

if (empty($history) && empty($message)) {
    $message = "Привітайся з користувачем {$user['name']}, коротко опиши свої можливості (Аналітика, Логи, Переклад).";
}

$contents = [];
foreach ($history as $msg) {
    $contents[] = ["role" => $msg['role'], "parts" => [["text" => $msg['text']]]];
}
if (!empty($message)) {
    $contents[] = ["role" => "user", "parts" => [["text" => $message]]];
}

$modelName = 'gemini-2.5-flash';
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

$data = [
    "contents" => $contents,
    "systemInstruction" => ["parts" => [["text" => $systemPrompt]]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['reply' => "Error: " . curl_error($ch)]);
    exit;
}
curl_close($ch);

$decoded = json_decode($response, true);
$reply = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? 'Вибачте, я зараз не можу відповісти.';

$reply = nl2br(htmlspecialchars($reply));
$reply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $reply);
$reply = preg_replace('/^\* /m', '• ', $reply);

echo json_encode(['reply' => $reply]);