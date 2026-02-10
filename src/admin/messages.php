<?php
// src/admin/messages.php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/logger.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();
$user = Auth::user();

// --- 1. ОБРОБКА ДІЙ (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // А) Зміна статусу
    if ($_POST['action'] === 'update_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $stmt = $db->prepare("UPDATE messages SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        Logger::log('update', 'message', $id, "Змінено статус на $status");
        header('Location: /admin/messages.php');
        exit;
    }

    // Б) Масове видалення
    if ($_POST['action'] === 'delete_bulk') {
        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $ids = array_map('intval', $_POST['ids']);
            if (count($ids) > 0) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $db->prepare("DELETE FROM messages WHERE id IN ($placeholders)");
                $stmt->execute($ids);
                Logger::log('delete', 'message', 0, "Масове видалення: " . count($ids) . " шт.");
            }
        }
        header('Location: /admin/messages.php');
        exit;
    }

    // В) Експорт в CSV
    if ($_POST['action'] === 'export_bulk') {
        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $ids = array_map('intval', $_POST['ids']);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Отримуємо дані (додано page_title)
            $stmt = $db->prepare("SELECT created_at, name, contact, message, status, page_title, source_url FROM messages WHERE id IN ($placeholders) ORDER BY created_at DESC");
            $stmt->execute($ids);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $filename = !empty($_POST['export_filename']) ? $_POST['export_filename'] : 'messages_export';
            $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
            if (!str_ends_with($filename, '.csv')) $filename .= '.csv';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF"); // BOM

            // Заголовки (додано Сторінка)
            fputcsv($output, ['Дата', 'Ім\'я', 'Контакт', 'Повідомлення', 'Статус', 'Сторінка', 'Посилання']);

            foreach ($rows as $row) {
                $row['message'] = str_replace(["\r", "\n"], " ", $row['message']);
                // Якщо page_title пустий, пишемо "Сайт"
                $row['page_title'] = !empty($row['page_title']) ? $row['page_title'] : 'Сайт';
                fputcsv($output, $row);
            }

            fclose($output);
            exit;
        }
        header('Location: /admin/messages.php');
        exit;
    }
}

// --- 2. ОТРИМАННЯ ПОВІДОМЛЕНЬ ---
$sql = "SELECT m.*, b.name_uk as branch_name 
        FROM messages m 
        LEFT JOIN branches b ON m.branch_id = b.id";
$params = [];

if ($user['role'] === 'branch_admin' && !empty($user['branch_id'])) {
    $sql .= " WHERE m.branch_id = ?";
    $params[] = $user['branch_id'];
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
        </div>
    </div>

    <!-- ПАНЕЛЬ ІНСТРУМЕНТІВ -->
    <div class="bulk-toolbar" style="margin-bottom: 15px; padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 5px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <span style="font-weight: bold; color: #555;">Дії з обраними:</span>
        <button type="button" class="btn btn-red" onclick="submitBulkAction('delete_bulk')" style="padding: 6px 12px; font-size: 0.9em; cursor: pointer;">🗑 Видалити</button>
        <div style="width: 1px; height: 20px; background: #ddd; margin: 0 5px;"></div>
        <button type="button" class="btn btn-green" onclick="prepareExport()" style="padding: 6px 12px; font-size: 0.9em; cursor: pointer;">📥 Скачати Excel (CSV)</button>
        <button type="button" class="btn btn-gray" onclick="copySelectedToClipboard()" style="padding: 6px 12px; font-size: 0.9em; cursor: pointer;">📋 Скопіювати в буфер</button>
    </div>

    <form id="bulkForm" method="POST" action="/admin/messages.php">
        <input type="hidden" name="action" id="bulkActionInput" value="">
        <input type="hidden" name="export_filename" id="exportFilenameInput" value="">

        <div class="form-card" style="max-width: 100%;">
            <table id="msgTable">
                <thead>
                <tr>
                    <th width="40" style="text-align:center;"><input type="checkbox" id="selectAll" onclick="toggleAll(this)" style="transform: scale(1.2); cursor:pointer;"></th>
                    <th width="120">Дата</th>
                    <th width="150">Клієнт</th>
                    <th width="200">Повідомлення</th>
                    <th>Джерело / Філія</th>
                    <th width="150">Статус</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr style="<?= $msg['status'] === 'new' ? 'background-color:#fffdf0;' : '' ?>" data-date="<?= date('Y-m-d', strtotime($msg['created_at'])) ?>">
                        <td style="text-align:center;">
                            <input type="checkbox" name="ids[]" value="<?= $msg['id'] ?>" class="row-checkbox" style="transform: scale(1.2); cursor:pointer;">
                        </td>
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
                            <div class="msg-content" style="font-size:0.95em; line-height:1.4; max-height:100px; overflow-y:auto;">
                                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                            </div>
                        </td>
                        <td style="font-size:0.85em;">
                            <?php if($msg['branch_name']): ?>
                                <span class="status-badge" style="background:#e8daef; color:#8e44ad;">
                                🏢 <?= htmlspecialchars($msg['branch_name']) ?>
                            </span>
                            <?php else: ?>
                                <!-- ВІДОБРАЖЕННЯ НАЗВИ СТОРІНКИ -->
                                <span class="status-badge" style="background:#eee; color:#555;">
                                🌐 <?= htmlspecialchars($msg['page_title'] ?? 'Головний сайт') ?>
                            </span>
                            <?php endif; ?>
                            <div style="margin-top:5px; color:#999; word-break:break-all;">
                                <a href="<?= htmlspecialchars($msg['source_url']) ?>" target="_blank" style="color:#999; text-decoration:none;">
                                    🔗 Посилання
                                </a>
                            </div>
                        </td>
                        <td>
                            <select onchange="updateStatus(<?= $msg['id'] ?>, this.value)" class="form-control"
                                    style="padding:5px; font-size:0.85em; cursor:pointer;
                                            border-color: <?= $msg['status']=='new'?'#f1c40f':($msg['status']=='done'?'#2ecc71':'#3498db') ?>;
                                            color: <?= $msg['status']=='new'?'#d35400':($msg['status']=='done'?'#27ae60':'#2980b9') ?>; font-weight:bold;">
                                <option value="new" <?= $msg['status']=='new'?'selected':'' ?>>Нове 🔥</option>
                                <option value="processing" <?= $msg['status']=='processing'?'selected':'' ?>>В роботі ⏳</option>
                                <option value="done" <?= $msg['status']=='done'?'selected':'' ?>>Виконано ✅</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($messages)): ?>
                    <tr><td colspan="6" style="text-align:center; padding:30px; color:#999;">Повідомлень немає</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>

    <form id="statusForm" method="POST" style="display:none;">
        <input type="hidden" name="action" value="update_status">
        <input type="hidden" name="id" id="statusId">
        <input type="hidden" name="status" id="statusVal">
    </form>

    <script>
        function toggleAll(source) {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = source.checked);
        }
        function updateStatus(id, val) {
            document.getElementById('statusId').value = id;
            document.getElementById('statusVal').value = val;
            document.getElementById('statusForm').submit();
        }
        function submitBulkAction(action) {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) return alert('Будь ласка, оберіть хоча б одне повідомлення!');
            if (action === 'delete_bulk' && !confirm('Ви впевнені? Буде видалено ' + checked.length + ' повідомлень.')) return;
            document.getElementById('bulkActionInput').value = action;
            document.getElementById('bulkForm').submit();
        }
        function prepareExport() {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) return alert('Оберіть повідомлення для експорту!');
            let dates = [];
            checked.forEach(cb => {
                const date = cb.closest('tr').getAttribute('data-date');
                if (date) dates.push(date);
            });
            dates.sort();
            let filename = dates.length > 0 && dates[0] !== dates[dates.length-1] ? `messages_${dates[0]}_to_${dates[dates.length-1]}` : `messages_${dates[0]}`;
            const userFilename = prompt("Введіть назву для файлу:", filename);
            if (userFilename !== null) {
                document.getElementById('exportFilenameInput').value = userFilename;
                submitBulkAction('export_bulk');
            }
        }
        function copySelectedToClipboard() {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) return alert('Оберіть повідомлення!');
            let text = "Дата\tКлієнт\tКонтакт\tПовідомлення\tСторінка\n";
            checked.forEach(cb => {
                const row = cb.closest('tr');
                const date = row.cells[1].innerText.trim();
                const client = row.cells[2].querySelector('strong') ? row.cells[2].querySelector('strong').innerText.trim() : '';
                const contact = row.cells[2].querySelector('div') ? row.cells[2].querySelector('div').innerText.trim() : '';
                const msg = row.cells[3].innerText.trim().replace(/\n/g, ' ');
                const page = row.cells[4].querySelector('.status-badge') ? row.cells[4].querySelector('.status-badge').innerText.trim() : 'Сайт';
                text += `${date}\t${client}\t${contact}\t${msg}\t${page}\n`;
            });
            navigator.clipboard.writeText(text).then(() => alert('Скопійовано!')).catch(err => console.error(err));
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>