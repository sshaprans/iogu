<?php
// src/admin/messages.php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/logger.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();
$user = Auth::user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE messages SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    Logger::log('update', 'message', $id, "Змінено статус на $status");
    header('Location: /admin/messages.php');
    exit;
}

// --- ОТРИМАННЯ ПОВІДОМЛЕНЬ (Фільтрація прав) ---
$sql = "SELECT m.*, b.name_uk as branch_name 
        FROM messages m 
        LEFT JOIN branches b ON m.branch_id = b.id";
$params = [];

// адмін філії
if ($user['role'] === 'branch_admin' && !empty($user['branch_id'])) {
    $sql .= " WHERE m.branch_id = ?";
    $params[] = $user['branch_id'];
} else {
    //  можна додати фільтр "Загальні" -> branch_id IS NULL)
}

$sql .= " ORDER BY m.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

$pageTitle = 'Повідомлення (CRM)';
require_once __DIR__ . '/includes/header.php';
?>

    <div class="header">
        <h1>Вхідні повідомлення</h1>
        <div style="font-size:0.9em; color:#777;">
            Всього: <?= count($messages) ?>
            <?php if($user['role'] === 'branch_admin'): ?> (по вашій філії) <?php endif; ?>
        </div>
    </div>

    <div class="form-card" style="max-width: 100%;">
        <table>
            <thead>
            <tr>
                <th width="120">Дата</th>
                <th width="150">Клієнт</th>
                <th width="200">Повідомлення</th>
                <th>Джерело / Філія</th>
                <th width="150">Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($messages as $msg): ?>
                <tr style="<?= $msg['status'] === 'new' ? 'background-color:#fffdf0;' : '' ?>">
                    <td style="font-size:0.85em; color:#555;">
                        <?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?>
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($msg['name']) ?></strong>
                        <div style="font-size:0.9em; margin-top:5px; color:#2980b9;">
                            <?= htmlspecialchars($msg['contact']) ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:0.95em; line-height:1.4; max-height:100px; overflow-y:auto;">
                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                        </div>
                    </td>
                    <td style="font-size:0.85em;">
                        <?php if($msg['branch_name']): ?>
                            <span class="status-badge" style="background:#e8daef; color:#8e44ad;">
                            🏢 <?= htmlspecialchars($msg['branch_name']) ?>
                        </span>
                        <?php else: ?>
                            <span class="status-badge" style="background:#eee; color:#555;">
                            🌐 Головний сайт
                        </span>
                        <?php endif; ?>
                        <div style="margin-top:5px; color:#999; word-break:break-all;">
                            <a href="<?= htmlspecialchars($msg['source_url']) ?>" target="_blank" style="color:#999; text-decoration:none;">
                                🔗 Посилання
                            </a>
                        </div>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                            <select name="status" onchange="this.form.submit()" class="form-control"
                                    style="padding:5px; font-size:0.85em;
                                        border-color: <?= $msg['status']=='new'?'#f1c40f':($msg['status']=='done'?'#2ecc71':'#3498db') ?>;
                                        color: <?= $msg['status']=='new'?'#d35400':($msg['status']=='done'?'#27ae60':'#2980b9') ?>; font-weight:bold;">
                                <option value="new" <?= $msg['status']=='new'?'selected':'' ?>>Нове 🔥</option>
                                <option value="processing" <?= $msg['status']=='processing'?'selected':'' ?>>В роботі ⏳</option>
                                <option value="done" <?= $msg['status']=='done'?'selected':'' ?>>Виконано ✅</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if(empty($messages)): ?>
                <tr><td colspan="5" style="text-align:center; padding:30px; color:#999;">Повідомлень немає</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>