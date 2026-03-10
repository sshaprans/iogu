<?php
// src/admin/messages.php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Logger.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();
$user = Auth::user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $redirectUrl = '/admin/messages.php';

    if ($_POST['action'] === 'update_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $stmt = $db->prepare("UPDATE messages SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        Logger::log('update', 'message', $id, "Змінено статус на $status");
        header('Location: ' . $redirectUrl);
        exit;
    }

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
        header('Location: ' . $redirectUrl);
        exit;
    }

    if ($_POST['action'] === 'export_bulk') {
        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $ids = array_map('intval', $_POST['ids']);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $stmt = $db->prepare("SELECT created_at, name, contact, message, status, page_title, source_url FROM messages WHERE id IN ($placeholders) ORDER BY created_at DESC");
            $stmt->execute($ids);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $filename = !empty($_POST['export_filename']) ? $_POST['export_filename'] : 'messages_export';
            $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
            if (substr($filename, -4) !== '.csv') $filename .= '.csv';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");

            fputcsv($output, ['Дата', 'Ім\'я', 'Контакт', 'Повідомлення', 'Статус', 'Сторінка', 'Посилання']);

            foreach ($rows as $row) {
                $row['message'] = str_replace(["\r", "\n"], " ", $row['message']);
                $row['page_title'] = !empty($row['page_title']) ? $row['page_title'] : 'Сайт';
                fputcsv($output, $row);
            }

            fclose($output);
            exit;
        }
        header('Location: ' . $redirectUrl);
        exit;
    }
}

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

    <div class="bulk-toolbar">
        <span style="font-weight: bold; color: #555;">Дії з обраними:</span>
        <button type="button" class="btn btn-red" onclick="submitBulkAction('delete_bulk')">🗑 Видалити</button>
        <div style="width: 1px; height: 20px; background: #ddd; margin: 0 5px;"></div>
        <button type="button" class="btn btn-green" onclick="prepareExport()">📥 Скачати Excel (CSV)</button>
        <button type="button" class="btn btn-gray" onclick="copySelectedToClipboard()">📋 Скопіювати в буфер</button>
    </div>

    <!-- ВИПРАВЛЕНО: Додано .php -->
    <form id="bulkForm" method="POST" action="/admin/messages.php">
        <input type="hidden" name="action" id="bulkActionInput" value="">
        <input type="hidden" name="export_filename" id="exportFilenameInput" value="">

        <div class="form-card" style="max-width: 100%;">
            <table id="msgTable">
                <thead>
                <tr>
                    <th width="40"><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
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
                        <td><input type="checkbox" name="ids[]" value="<?= $msg['id'] ?>" class="row-checkbox"></td>
                        <td><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($msg['name']) ?></strong>
                            <div><?= htmlspecialchars($msg['contact']) ?></div>
                        </td>
                        <td><div class="msg-content"><?= nl2br(htmlspecialchars($msg['message'])) ?></div></td>
                        <td>
                            <?php if($msg['branch_name']): ?>
                                <span class="status-badge" style="background:#e8daef; color:#8e44ad;">
                                🏢 <?= htmlspecialchars($msg['branch_name']) ?>
                            </span>
                            <?php else: ?>
                                <span class="status-badge" style="background:#eee; color:#555;">
                                🌐 <?= htmlspecialchars($msg['page_title'] ?? 'Головний сайт') ?>
                            </span>
                            <?php endif; ?>
                            <div><a href="<?= htmlspecialchars($msg['source_url']) ?>" target="_blank">🔗 Посилання</a></div>
                        </td>
                        <td>
                            <select onchange="updateStatus(<?= $msg['id'] ?>, this.value)" class="form-control">
                                <option value="new" <?= $msg['status']=='new'?'selected':'' ?>>Нове 🔥</option>
                                <option value="processing" <?= $msg['status']=='processing'?'selected':'' ?>>В роботі ⏳</option>
                                <option value="done" <?= $msg['status']=='done'?'selected':'' ?>>Виконано ✅</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($messages)): ?>
                    <tr><td colspan="6">Повідомлень немає</td></tr>
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
        function toggleAll(source) { document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = source.checked); }
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
            let dates = Array.from(checked).map(cb => cb.closest('tr').dataset.date).filter(Boolean).sort();
            let filename = dates.length > 0 && dates[0] !== dates[dates.length-1] ? `messages_${dates[0]}_to_${dates[dates.length-1]}` : `messages_${dates[0] || 'export'}`;
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
                const client = row.cells[2].querySelector('strong')?.innerText.trim() || '';
                const contact = row.cells[2].querySelector('div')?.innerText.trim() || '';
                const msg = row.cells[3].innerText.trim().replace(/\n/g, ' ');
                const page = row.cells[4].querySelector('.status-badge')?.innerText.trim() || 'Сайт';
                text += `${date}\t${client}\t${contact}\t${msg}\t${page}\n`;
            });
            navigator.clipboard.writeText(text).then(() => alert('Скопійовано!')).catch(err => console.error(err));
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>