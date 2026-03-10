<?php
// src/admin/translations.php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();

$configured_langs = ['uk', 'en'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    try {
        if ($_POST['action'] === 'update') {
            $id = (int)$_POST['id'];
            $sets = []; $values = [];
            foreach ($configured_langs as $lang) {
                if (isset($_POST['text_'.$lang])) { $sets[] = "text_$lang = ?"; $values[] = $_POST['text_'.$lang]; }
            }
            $values[] = $id;
            if (empty($sets)) throw new Exception('No data');
            $stmt = $db->prepare("UPDATE translations SET " . implode(', ', $sets) . " WHERE id = ?");
            $stmt->execute($values);
            echo json_encode(['status' => 'success']);
        }
        elseif ($_POST['action'] === 'delete') {
            $stmt = $db->prepare("DELETE FROM translations WHERE id = ?");
            $stmt->execute([(int)$_POST['id']]);
            echo json_encode(['status' => 'success']);
        }
        elseif ($_POST['action'] === 'create') {
            $keyName = trim($_POST['key_name'] ?? '');
            if (empty($keyName)) throw new Exception('Ключ пустий');
            $stmt = $db->prepare("SELECT id FROM translations WHERE key_name = ?");
            $stmt->execute([$keyName]);
            if ($stmt->fetch()) throw new Exception('Ключ вже існує');

            $cols = ['key_name']; $vals = [$keyName]; $placeholders = ['?'];
            foreach ($configured_langs as $lang) {
                if (isset($_POST['text_'.$lang])) { $cols[] = 'text_'.$lang; $vals[] = $_POST['text_'.$lang]; $placeholders[] = '?'; }
            }
            $stmt = $db->prepare("INSERT INTO translations (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")");
            $stmt->execute($vals);
            echo json_encode(['status' => 'success']);
        }
    } catch (Exception $e) {
        http_response_code(500); echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM translations";
$params = [];
if ($search) {
    $term = "%$search%";
    $where = ["key_name LIKE ?"]; $params[] = $term;
    foreach ($configured_langs as $lang) { $where[] = "text_$lang LIKE ?"; $params[] = $term; }
    $sql .= " WHERE " . implode(' OR ', $where);
}
$sql .= " ORDER BY key_name ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$translations = $stmt->fetchAll();

$pageTitle = 'Переклади - Адмін-панель';
require_once __DIR__ . '/includes/header.php';
?>

    <div class="header">
        <h1>Переклади сайту</h1>
        <a href="/admin/index.php" class="btn btn-gray">Назад</a>
    </div>

    <div class="search-bar">
        <button class="btn btn-green" style="margin-right: 20px;" onclick="toggleAddPanel()">+ Додати новий ключ</button>
        <form method="GET" style="display:flex; gap:10px;">
            <input type="text" name="search" class="form-control" style="width:300px" placeholder="Пошук..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn">Знайти</button>
            <?php if($search): ?>
                <a href="/admin/translations.php" class="btn btn-red">Скинути</a>
            <?php endif; ?>
        </form>
    </div>

    <div id="addPanel" class="add-panel">
        <h3>Створити новий переклад</h3>
        <div class="form-group"><label class="form-label">Ключ</label><input type="text" id="new_key" class="form-control" placeholder="напр. home.welcome_text"></div>
        <div class="form-row">
            <?php foreach ($configured_langs as $lang): $sLang = ($lang === 'uk') ? 'en' : 'uk'; ?>
                <div class="form-group">
                    <div class="form-label-row"><label class="form-label">Текст (<?= strtoupper($lang) ?>)</label><button type="button" class="btn-translate" onclick="autoTranslateNew('<?= $sLang ?>', '<?= $lang ?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.87 15.07l-2.54-2.51.03-.03A17.52 17.52 0 0014.07 6H17V4h-7V2H8v2H1v2h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/></svg><span>з <?= strtoupper($sLang) ?></span></button></div>
                    <textarea id="new_text_<?= $lang ?>" class="form-control"></textarea>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="btn btn-green" onclick="createTranslation()">Створити</button>
        <button class="btn btn-gray" onclick="toggleAddPanel()">Скасувати</button>
    </div>

    <table>
        <thead>
        <tr>
            <th width="20%">Ключ</th>
            <?php foreach ($configured_langs as $lang): ?><th><?= strtoupper($lang) ?></th><?php endforeach; ?>
            <th width="10%">Дія</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($translations as $row): ?>
            <tr id="row-<?= $row['id'] ?>">
                <td class="key-cell"><?= htmlspecialchars($row['key_name']) ?></td>
                <?php foreach ($configured_langs as $lang): $sLang = ($lang === 'uk') ? 'en' : 'uk'; ?>
                    <td>
                        <div class="tools-area"><button type="button" class="btn-translate" onclick="autoTranslate(<?= $row['id'] ?>, '<?= $sLang ?>', '<?= $lang ?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.87 15.07l-2.54-2.51.03-.03A17.52 17.52 0 0014.07 6H17V4h-7V2H8v2H1v2h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/></svg><span>з <?= strtoupper($sLang) ?></span></button></div>
                        <textarea id="<?= $lang ?>-<?= $row['id'] ?>" class="form-control" oninput="checkChanges(<?= $row['id'] ?>)"><?= htmlspecialchars($row['text_' . $lang] ?? '') ?></textarea>
                    </td>
                <?php endforeach; ?>
                <td>
                    <button class="btn btn-green" disabled onclick="saveTranslation(<?= $row['id'] ?>)">Зберегти</button>
                    <button class="btn btn-red" style="width:100%; margin-top:5px;" onclick="deleteTranslation(<?= $row['id'] ?>)">X</button>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if(empty($translations)): ?><tr><td colspan="<?= count($configured_langs) + 2 ?>" style="text-align:center">Нічого не знайдено</td></tr><?php endif; ?>
        </tbody>
    </table>

    <script>
        const CONFIG_LANGS = <?= json_encode($configured_langs) ?>;
        function toggleAddPanel() {
            const panel = document.getElementById('addPanel');
            panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
        }
        function checkChanges(id) {
            const btn = document.querySelector(`#row-${id} .btn-green`);
            let hasChanges = CONFIG_LANGS.some(lang => {
                const el = document.getElementById(`${lang}-${id}`);
                return el.value !== el.defaultValue;
            });
            btn.disabled = !hasChanges;
        }
        async function fetchTranslation(text, fromLang, toLang) {
            const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${fromLang}|${toLang}`);
            return await res.json();
        }
        async function autoTranslate(id, fromLang, toLang) {
            const sourceBox = document.getElementById(`${fromLang}-${id}`);
            const targetBox = document.getElementById(`${toLang}-${id}`);
            const text = sourceBox.value.trim();
            if (!text) return alert('Поле джерела пусте!');
            document.body.style.cursor = 'wait';
            try {
                const data = await fetchTranslation(text, fromLang, toLang);
                if (data.responseStatus === 200) {
                    targetBox.value = data.responseData.translatedText;
                    targetBox.style.backgroundColor = '#d5f5e3';
                    setTimeout(() => targetBox.style.backgroundColor = '', 1000);
                    checkChanges(id);
                } else alert('Помилка перекладу');
            } catch (e) { alert('Помилка з\'єднання'); }
            finally { document.body.style.cursor = 'default'; }
        }
        async function autoTranslateNew(fromLang, toLang) {
            const sourceBox = document.getElementById(`new_text_${fromLang}`);
            const targetBox = document.getElementById(`new_text_${toLang}`);
            const text = sourceBox.value.trim();
            if (!text) return alert('Поле джерела пусте!');
            document.body.style.cursor = 'wait';
            try {
                const data = await fetchTranslation(text, fromLang, toLang);
                if (data.responseStatus === 200) {
                    targetBox.value = data.responseData.translatedText;
                    targetBox.style.backgroundColor = '#d5f5e3';
                    setTimeout(() => targetBox.style.backgroundColor = '', 1000);
                } else alert('Помилка перекладу');
            } catch (e) { alert('Помилка з\'єднання'); }
            finally { document.body.style.cursor = 'default'; }
        }
        async function createTranslation() {
            const keyName = document.getElementById('new_key').value.trim();
            if (!keyName) return alert('Введіть ключ!');
            const fd = new FormData();
            fd.append('action', 'create'); fd.append('key_name', keyName);
            CONFIG_LANGS.forEach(l => fd.append(`text_${l}`, document.getElementById(`new_text_${l}`).value));
            try {
                const res = await fetch('/admin/translations.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.status === 'success') location.reload();
                else alert('Помилка: ' + data.message);
            } catch (e) { alert('Помилка сервера'); }
        }
        async function deleteTranslation(id) {
            if (!confirm('Видалити?')) return;
            const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
            try {
                const res = await fetch('/admin/translations.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.status === 'success') document.getElementById('row-' + id).remove();
                else alert('Помилка');
            } catch (e) { alert('Помилка сервера'); }
        }
        async function saveTranslation(id) {
            if (!confirm('Зберегти зміни?')) return;
            const btn = document.querySelector(`#row-${id} .btn-green`);
            const originalText = btn.innerText; btn.innerText = '...'; btn.disabled = true;
            const fd = new FormData(); fd.append('action', 'update'); fd.append('id', id);
            CONFIG_LANGS.forEach(l => fd.append(`text_${l}`, document.getElementById(`${l}-${id}`).value));
            try {
                const res = await fetch('/admin/translations.php', { method: 'POST', body: fd });
                if (res.ok) {
                    btn.style.backgroundColor = '#27ae60'; btn.innerText = 'OK!';
                    CONFIG_LANGS.forEach(l => { const el = document.getElementById(`${l}-${id}`); el.defaultValue = el.value; });
                    setTimeout(() => { btn.innerText = originalText; btn.style.backgroundColor = ''; checkChanges(id); }, 1500);
                } else { alert('Помилка'); btn.innerText = originalText; btn.disabled = false; }
            } catch (e) { alert('Помилка'); btn.innerText = originalText; btn.disabled = false; }
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>