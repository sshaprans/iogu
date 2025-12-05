<?php
// src/admin/index.php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/logger.php';

Auth::requireLogin();
$user = Auth::user();

if (isset($_GET['logout'])) {
    Auth::logout();
    header('Location: /admin/login');
    exit();
}

$pageTitle = 'Головна - Адмін-панель';
require_once __DIR__ . '/includes/header.php';

$logs = Logger::getLogs(50);

// Метрики
$dbStatus = 'Connected';
try { $db = Database::getInstance()->getConnection(); } catch (Exception $e) { $dbStatus = 'Error'; }
$diskFree = disk_free_space(__DIR__);
$diskTotal = disk_total_space(__DIR__);
$diskFreeGb = round($diskFree / 1024 / 1024 / 1024, 2);
$diskPercent = round(($diskFree / $diskTotal) * 100);
$phpVer = phpversion();
?>

    <style>
        /* styles dashboard */
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .metric-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative; overflow: hidden; }
        .metric-title { font-size: 0.9em; color: #7f8c8d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; font-weight: bold; }
        .metric-value { font-size: 1.8em; font-weight: bold; color: #2c3e50; }
        .metric-sub { font-size: 0.85em; color: #95a5a6; margin-top: 5px; }

        .ps-score { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2em; color: white; margin-top: 10px; }
        .score-unknown { background: #bdc3c7; } .score-good { background: #2ecc71; } .score-avg { background: #f1c40f; } .score-bad { background: #e74c3c; }

        /* ai gemini */
        .ai-widget { position: fixed; bottom: 30px; right: 30px; z-index: 1000; display: flex; flex-direction: column; align-items: flex-end; }
        .ai-button { width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.3s; }
        .ai-button:hover { transform: scale(1.1); }
        .ai-button svg { width: 30px; height: 30px; fill: white; }

        .ai-chat-window {
            width: 380px; height: 500px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            margin-bottom: 15px; display: none; flex-direction: column; overflow: hidden; border: 1px solid #eee;
            animation: slideUp 0.3s ease-out;
        }
        .ai-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .ai-body { flex-grow: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; font-size: 0.95em; scroll-behavior: smooth; }
        .ai-footer { padding: 10px; border-top: 1px solid #eee; background: white; display: flex; gap: 5px; }
        .ai-input { flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 14px; }

        .ai-msg { margin-bottom: 12px; padding: 10px 14px; border-radius: 12px; max-width: 85%; line-height: 1.5; word-wrap: break-word; }
        .ai-msg-bot { background: #e0e7ff; color: #333; align-self: flex-start; border-bottom-left-radius: 2px; }
        .ai-msg-user { background: #667eea; color: white; align-self: flex-end; margin-left: auto; border-bottom-right-radius: 2px; }
        /* loader */
        .typing-dots span { animation: blink 1.4s infinite both; display: inline-block; width: 4px; height: 4px; background: #555; border-radius: 50%; margin: 0 2px; }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; } .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes blink { 0% { opacity: 0.2; } 20% { opacity: 1; } 100% { opacity: 0.2; } }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>

    <div class="header">
        <h1>Панель керування</h1>
    </div>

    <div class="dashboard-grid">
        <div class="metric-card">
            <div class="metric-title">Мій статус</div>
            <div class="metric-value" style="color: #3498db;"><?= ucfirst(htmlspecialchars($user['role'])) ?></div>
            <div class="metric-sub">Логін: <?= htmlspecialchars($user['login'] ?? '') ?></div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Сервер</div>
            <div class="metric-value" style="font-size: 1.2em;">PHP <?= $phpVer ?></div>
            <div class="metric-sub">DB: <span style="color:<?= $dbStatus=='Connected'?'#27ae60':'red' ?>">● <?= $dbStatus ?></span></div>
            <div class="metric-sub" style="margin-top:5px;">Disk: <?= $diskFreeGb ?> GB вільно
                <div style="width:100%; height:4px; background:#eee; margin-top:2px; border-radius:2px;">
                    <div style="width:<?= $diskPercent ?>%; height:100%; background:#2ecc71; border-radius:2px;"></div>
                </div>
            </div>
        </div>
        <div class="metric-card" id="pagespeed-card">
            <div class="metric-title">Google PageSpeed</div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div class="metric-sub">Мобільна версія</div>
                    <button class="btn btn-gray" onclick="runPageSpeed()" style="font-size:0.7em; padding: 4px 8px; margin-top:5px;">Запустити тест</button>
                </div>
                <div id="ps-score-circle" class="ps-score score-unknown">?</div>
            </div>
            <div id="ps-loading" style="display:none; font-size:0.8em; color:#7f8c8d; margin-top:5px;">Аналізуємо... (10-15с)</div>
        </div>
        <div class="metric-card">
            <div class="metric-title">Інструменти</div>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <a href="https://analytics.google.com/" target="_blank" style="text-decoration:none; color:#2980b9; font-size:0.9em;">📊 Google Analytics</a>
                <a href="https://search.google.com/search-console" target="_blank" style="text-decoration:none; color:#2980b9; font-size:0.9em;">🔎 Search Console</a>
            </div>
        </div>
    </div>

    <!-- Logs -->
    <div class="form-card" style="max-width: 100%;">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; display:flex; justify-content:space-between; align-items:center;">
            <span>Журнал подій</span>
            <span style="font-size:0.6em; background:#eee; padding:2px 8px; border-radius:10px; color:#666;">Останні 50</span>
        </h3>
        <table>
            <thead>
            <tr><th width="160">Дата</th><th width="200">Користувач</th><th width="120">Дія</th><th>Деталі</th></tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td style="color:#777; font-size:0.9em;"><?= date('d.m.Y H:i', strtotime($log['created_at'])) ?></td>
                    <td><strong><?= htmlspecialchars($log['user_name'] ?? 'User') ?></strong><br><span class="status-badge" style="font-size:0.75em; background:#eee; color:#555; padding: 2px 6px;"><?= $log['user_role'] ?></span></td>
                    <td>
                        <?php
                        $color = '#95a5a6'; $bg = '#ecf0f1';
                        if (strpos($log['action'], 'create') !== false) { $color = '#27ae60'; $bg = '#d5f5e3'; }
                        if (strpos($log['action'], 'delete') !== false) { $color = '#c0392b'; $bg = '#fadbd8'; }
                        if (strpos($log['action'], 'update') !== false) { $color = '#2980b9'; $bg = '#d6eaf8'; }
                        ?>
                        <span class="status-badge" style="color:<?= $color ?>; background:<?= $bg ?>"><?= $log['action'] ?></span>
                    </td>
                    <td>
                        <div style="font-weight:bold; color:#555; margin-bottom: 3px;"><?= strtoupper($log['entity_type']) ?> <span style="font-weight:normal; color:#999;">(ID: <?= $log['entity_id'] ?>)</span></div>
                        <div style="color:#666; font-size:0.95em;"><?= htmlspecialchars($log['details']) ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($logs)): ?><tr><td colspan="4" style="text-align:center; padding:40px; color:#777;">Історія порожня.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- REAL AI WIDGET -->
    <div class="ai-widget">
        <div class="ai-chat-window" id="aiChat">
            <div class="ai-header">
                <span>✨ Gemini Assistant</span>
                <span style="cursor:pointer;" onclick="toggleAI()">✕</span>
            </div>
            <div class="ai-body" id="aiMessages">
                <div class="ai-msg ai-msg-bot">
                    Вітаю! Я ваш ШІ-асистент. Я маю доступ до логів сайту. Спитайте мене, що нового!
                </div>
            </div>
            <div class="ai-footer">
                <input type="text" class="ai-input" id="aiInput" placeholder="Напишіть запитання..." onkeypress="handleAiEnter(event)">
                <button class="btn btn-green" onclick="sendAiMessage()" style="padding: 5px 10px;">➜</button>
            </div>
        </div>
        <div class="ai-button" onclick="toggleAI()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,2H5A2,2 0 0,0 3,4V18A2,2 0 0,0 5,20H9L12,23L15,20H19A2,2 0 0,0 21,18V4A2,2 0 0,0 19,2M13.88,12.88L12,17L10.12,12.88L6,11L10.12,9.12L12,5L13.88,9.12L18,11L13.88,12.88Z" /></svg>
        </div>
    </div>

    <script>
        function runPageSpeed() {
            const url = 'https://iogu.gov.ua';
            const api = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${url}&strategy=mobile`;
            document.getElementById('ps-loading').style.display = 'block';
            document.getElementById('ps-score-circle').innerText = '...';
            document.getElementById('ps-score-circle').className = 'ps-score score-unknown';
            fetch(api).then(r => r.json()).then(data => {
                document.getElementById('ps-loading').style.display = 'none';
                if (data.lighthouseResult) {
                    const score = data.lighthouseResult.categories.performance.score * 100;
                    const circle = document.getElementById('ps-score-circle');
                    circle.innerText = Math.round(score);
                    if (score >= 90) circle.className = 'ps-score score-good';
                    else if (score >= 50) circle.className = 'ps-score score-avg';
                    else circle.className = 'ps-score score-bad';
                } else { alert('Ліміт запитів або помилка API'); document.getElementById('ps-score-circle').innerText = '?'; }
            }).catch(e => { console.error(e); document.getElementById('ps-loading').style.display = 'none'; alert('Error'); });
        }

        // --- REAL AI Logic ---
        function toggleAI() {
            const chat = document.getElementById('aiChat');
            chat.style.display = (chat.style.display === 'none' || chat.style.display === '') ? 'flex' : 'none';
            if(chat.style.display === 'flex') {
                document.getElementById('aiInput').focus();
                const container = document.getElementById('aiMessages');
                container.scrollTop = container.scrollHeight;
            }
        }

        function handleAiEnter(e) { if (e.key === 'Enter') sendAiMessage(); }

        function addMsg(text, type) {
            const div = document.createElement('div');
            div.className = `ai-msg ai-msg-${type}`;
            div.innerHTML = text;
            const container = document.getElementById('aiMessages');
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
            return div;
        }

        async function sendAiMessage() {
            const input = document.getElementById('aiInput');
            const text = input.value.trim();
            if (!text) return;

            addMsg(text, 'user');
            input.value = '';
            input.disabled = true;

            const loadingDiv = addMsg('<div class="typing-dots"><span></span><span></span><span></span></div>', 'bot');

            try {
                const response = await fetch('/admin/api/gemini.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();
                loadingDiv.remove();

                if (data.error) {
                    addMsg('Помилка: ' + data.error, 'bot');
                } else {
                    addMsg(data.reply, 'bot');
                }
            } catch (error) {
                loadingDiv.remove();
                addMsg('Помилка з\'єднання з сервером.', 'bot');
                console.error(error);
            } finally {
                input.disabled = false;
                input.focus();
            }
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>