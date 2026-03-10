<?php
// src/admin/index.php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Logger.php';

Auth::requireLogin();
$user = Auth::user();

if (isset($_GET['logout'])) {
    Auth::logout();
    header('Location: /admin/login.php');
    exit();
}

$pageTitle = 'Головна - Адмін-панель';
require_once __DIR__ . '/includes/header.php';

$logs = Logger::getLogs(50);

$dbStatus = 'Connected';
try {
    $db = Database::getInstance()->getConnection();
} catch (Exception $e) {
    $dbStatus = 'Error';
}

$diskFree = disk_free_space(__DIR__);
$diskTotal = disk_total_space(__DIR__);
$diskFreeGb = round($diskFree / 1024 / 1024 / 1024, 2);
$diskPercent = round(($diskFree / $diskTotal) * 100);
$phpVer = phpversion();
?>

    <style>
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .metric-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative; overflow: hidden; }
        .metric-title { font-size: 0.9em; color: #7f8c8d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; font-weight: bold; }
        .metric-value { font-size: 1.8em; font-weight: bold; color: #2c3e50; }
        .metric-sub { font-size: 0.85em; color: #95a5a6; margin-top: 5px; }
        .ps-score { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2em; color: white; margin-top: 10px; }
        .score-unknown { background: #bdc3c7; } .score-good { background: #2ecc71; } .score-avg { background: #f1c40f; } .score-bad { background: #e74c3c; }
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
            <div class="metric-value" style="font-size: 1.2em;">PHP <?= htmlspecialchars($phpVer) ?></div>
            <div class="metric-sub">DB: <span style="color:<?= $dbStatus === 'Connected' ? '#27ae60' : 'red' ?>">● <?= htmlspecialchars($dbStatus) ?></span></div>
            <div class="metric-sub" style="margin-top:5px;">Disk: <?= htmlspecialchars((string)$diskFreeGb) ?> GB вільно
                <div style="width:100%; height:4px; background:#eee; margin-top:2px; border-radius:2px;">
                    <div style="width:<?= htmlspecialchars((string)$diskPercent) ?>%; height:100%; background:#2ecc71; border-radius:2px;"></div>
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

    <div class="form-card" style="max-width: 100%;">
        <h3 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; display:flex; justify-content:space-between; align-items:center;">
            <span style="display:flex; align-items:center; gap:10px;">
                Журнал подій
                <span style="font-size:0.6em; background:#eee; padding:2px 8px; border-radius:10px; color:#666; font-weight:normal;">Останні 50</span>
            </span>

            <?php if (in_array($user['role'], ['admin', 'dev'], true)): ?>
                <a href="/admin/api/export_logs.php" class="btn btn-gray" style="font-size: 0.8em; padding: 6px 12px; text-decoration: none;">
                    📥 Завантажити повний архів (CSV)
                </a>
            <?php endif; ?>
        </h3>
        <table>
            <thead>
            <tr><th width="160">Дата</th><th width="200">Користувач</th><th width="120">Дія</th><th>Деталі</th></tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td style="color:#777; font-size:0.9em;"><?= date('d.m.Y H:i', strtotime($log['created_at'])) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($log['user_name'] ?? 'User') ?></strong><br>
                        <span class="status-badge" style="font-size:0.75em; background:#eee; color:#555; padding: 2px 6px;">
                            <?= htmlspecialchars($log['user_role']) ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $color = '#95a5a6'; $bg = '#ecf0f1';
                        if (strpos($log['action'], 'create') !== false) { $color = '#27ae60'; $bg = '#d5f5e3'; }
                        if (strpos($log['action'], 'delete') !== false) { $color = '#c0392b'; $bg = '#fadbd8'; }
                        if (strpos($log['action'], 'update') !== false) { $color = '#2980b9'; $bg = '#d6eaf8'; }
                        if (strpos($log['action'], 'upload') !== false) { $color = '#8e44ad'; $bg = '#f4ecf7'; }
                        ?>
                        <span class="status-badge" style="color:<?= $color ?>; background:<?= $bg ?>">
                            <?= htmlspecialchars($log['action']) ?>
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:bold; color:#555; margin-bottom: 3px;">
                            <?= htmlspecialchars(strtoupper($log['entity_type'])) ?>
                            <span style="font-weight:normal; color:#999;">(ID: <?= htmlspecialchars((string)$log['entity_id']) ?>)</span>
                        </div>
                        <div style="color:#666; font-size:0.95em;"><?= htmlspecialchars($log['details']) ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($logs)): ?>
                <tr><td colspan="4" style="text-align:center; padding:40px; color:#777;">Історія порожня.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
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
                } else {
                    alert('Ліміт запитів або помилка API');
                    document.getElementById('ps-score-circle').innerText = '?';
                }
            }).catch(e => {
                console.error(e);
                document.getElementById('ps-loading').style.display = 'none';
                alert('Error');
            });
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>