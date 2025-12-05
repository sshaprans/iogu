<?php
// src/admin/news.php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/logger.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();

// config
$uploadDir = __DIR__ . '/../img/news/';
$uploadContentDir = __DIR__ . '/../img/news/content/';
$mediaDomain = 'https://media.iogu.gov.ua';
$uploadUrlPath = '/img/news/';
$uploadContentUrlPath = '/img/news/content/';

if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
if (!is_dir($uploadContentDir)) @mkdir($uploadContentDir, 0777, true);

function validateImageUpload($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) throw new Exception('Помилка завантаження');
    if ($file['size'] > 5 * 1024 * 1024) throw new Exception('Максимум 5MB');
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (!in_array($finfo->file($file['tmp_name']), $allowedMimes)) throw new Exception('Невірний формат зображення');
    return strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
}

function generateSlug($text) {
    $table = ['А'=>'A', 'а'=>'a', 'Б'=>'B', 'б'=>'b', 'В'=>'V', 'в'=>'v', 'Г'=>'H', 'г'=>'h', 'Ґ'=>'G', 'ґ'=>'g', 'Д'=>'D', 'д'=>'d', 'Е'=>'E', 'е'=>'e', 'Є'=>'Ye', 'є'=>'ye', 'Ж'=>'Zh', 'ж'=>'zh', 'З'=>'Z', 'з'=>'z', 'И'=>'Y', 'и'=>'y', 'І'=>'I', 'і'=>'i', 'Ї'=>'Yi', 'ї'=>'yi', 'Й'=>'Y', 'й'=>'y', 'К'=>'K', 'к'=>'k', 'Л'=>'L', 'л'=>'l', 'М'=>'M', 'м'=>'m', 'Н'=>'N', 'н'=>'n', 'О'=>'O', 'о'=>'o', 'П'=>'P', 'п'=>'p', 'Р'=>'R', 'р'=>'r', 'С'=>'S', 'с'=>'s', 'Т'=>'T', 'т'=>'t', 'У'=>'U', 'у'=>'u', 'Ф'=>'F', 'ф'=>'f', 'Х'=>'Kh', 'х'=>'kh', 'Ц'=>'Ts', 'ц'=>'ts', 'Ч'=>'Ch', 'ч'=>'ch', 'Ш'=>'Sh', 'ш'=>'sh', 'Щ'=>'Shch', 'щ'=>'shch', 'Ь'=>'', 'ь'=>'', 'Ю'=>'Yu', 'ю'=>'yu', 'Я'=>'Ya', 'я'=>'ya', ' '=>'-', ','=>'', '.'=>'', '/'=>'-', '"'=>'', '\''=>''];
    $text = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', strtr((string)$text, $table)));
    return trim($text, '-');
}

// POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // TinyMce
    if ($action === 'upload_tiny_image') {
        header('Content-Type: application/json');
        try {
            $ext = validateImageUpload($_FILES['file'] ?? null);
            $filename = 'content_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadContentDir . $filename)) {
                echo json_encode(['location' => $mediaDomain . $uploadContentUrlPath . $filename]);
            } else throw new Exception('Server error');
        } catch (Exception $e) {
            http_response_code(500); echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $old = $db->query("SELECT title_uk FROM news WHERE id=$id")->fetch();
        $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
        Logger::log('delete', 'news', $id, "Видалено новину: " . ($old['title_uk'] ?? ''));
        header('Location: /admin/news.php'); exit;
    }

    if ($action === 'save') {
        try {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $titleUk = trim($_POST['title_uk']);
            $slug = trim($_POST['slug']);
            if (empty($slug)) $slug = generateSlug($titleUk);

            $imagePath = $_POST['current_image'] ?? null;
            if (!empty($_POST['manual_image_url'])) $imagePath = trim($_POST['manual_image_url']);

            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                try {
                    $ext = validateImageUpload($_FILES['image']);
                    $filename = 'news_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                        $imagePath = $uploadUrlPath . $filename;
                    }
                } catch (Exception $e) {}
            }

            $params = [
                    $slug, $imagePath, $_POST['date_posted'],
                    $titleUk, trim($_POST['title_en']),
                    $_POST['content_uk'], $_POST['content_en'],
                    isset($_POST['is_published']) ? 1 : 0
            ];

            $logDetails = "Новина: " . mb_substr($titleUk, 0, 30) . "...";

            if ($id) {
                // for log
                $oldData = $db->query("SELECT * FROM news WHERE id=$id")->fetch();
                $changes = [];

                if ($oldData['title_uk'] !== $titleUk) $changes[] = "Заголовок";
                if ($oldData['slug'] !== $slug) $changes[] = "URL";
                if ($oldData['image'] !== $imagePath) $changes[] = "Картинка";
                if ($oldData['content_uk'] !== $_POST['content_uk']) $changes[] = "Текст";
                if ($oldData['is_published'] != (isset($_POST['is_published']) ? 1 : 0)) $changes[] = "Статус публікації";

                if (!empty($changes)) {
                    $logDetails .= " | Змінено: " . implode(', ', $changes);
                } else {
                    $logDetails .= " | Оновлено без змін";
                }

                $sql = "UPDATE news SET slug=?, image=?, date_posted=?, title_uk=?, title_en=?, content_uk=?, content_en=?, is_published=? WHERE id=?";
                $params[] = $id;
                $dbAction = 'update';
            } else {
                $sql = "INSERT INTO news (slug, image, date_posted, title_uk, title_en, content_uk, content_en, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $dbAction = 'create';
                $logDetails .= " | Створено нову";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            $logId = $id ? $id : $db->lastInsertId();
            Logger::log($dbAction, 'news', $logId, $logDetails);

            header('Location: /admin/news.php'); exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}


$view = $_GET['view'] ?? 'list';
$editData = null;
if ($view === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_id'] ?? (int)$_GET['id']]);
    $editData = $stmt->fetch();
    if (!$editData) $view = 'list';
}

$newsList = ($view === 'list') ? $db->query("SELECT * FROM news ORDER BY date_posted DESC")->fetchAll() : [];

$pageTitle = 'Новини - Адмін-панель';
$useTinyMCE = true;
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($view === 'list'): ?>
    <div class="header">
        <h1>Управління новинами</h1>
        <a href="/admin/news.php?view=add" class="btn btn-green">+ Додати новину</a>
    </div>

    <table>
        <thead>
        <tr>
            <th width="80">Фото</th>
            <th>Дата</th>
            <th>Заголовок (UK)</th>
            <th>Статус</th>
            <th width="120">Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($newsList as $news): ?>
            <tr>
                <td>
                    <?php if($news['image']): ?>
                        <img src="<?= htmlspecialchars($news['image']) ?>" class="news-thumb" alt="">
                    <?php else: ?>
                        <div class="news-thumb"></div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($news['date_posted']) ?></td>
                <td><strong><?= htmlspecialchars($news['title_uk']) ?></strong></td>
                <td>
                    <?= $news['is_published']
                            ? '<span class="status-badge status-on">Публічно</span>'
                            : '<span class="status-badge status-off">Приховано</span>' ?>
                </td>
                <td>
                    <a href="/admin/news.php?view=edit&id=<?= $news['id'] ?>" class="btn btn-gray" style="padding: 5px 10px;">✎</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Видалити?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $news['id'] ?>">
                        <button type="submit" class="btn btn-red" style="padding: 5px 10px;">X</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <div class="header">
        <h1><?= $editData ? 'Редагування новини' : 'Нова новина' ?></h1>
        <a href="/admin/news.php" class="btn btn-gray">Назад</a>
    </div>

    <form class="form-card" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save">
        <?php if($editData): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($editData['image']) ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Дата публікації</label>
                <input type="date" name="date_posted" class="form-control" required value="<?= $editData['date_posted'] ?? date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">URL (Slug)</label>
                <input type="text" name="slug" class="form-control" placeholder="auto-generated" value="<?= htmlspecialchars($editData['slug'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Зображення</label>
            <?php if(!empty($editData['image'])): ?>
                <img src="<?= htmlspecialchars($editData['image']) ?>" style="height: 80px; margin-bottom: 5px; border-radius: 4px;">
            <?php endif; ?>
            <div style="margin-bottom: 5px;">
                <input type="text" name="manual_image_url" class="form-control" placeholder="Або вставте посилання..." value="">
            </div>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label style="display:flex; align-items:center; cursor:pointer;">
                <input type="checkbox" name="is_published" <?= (!isset($editData) || $editData['is_published']) ? 'checked' : '' ?> style="margin-right: 10px;">
                <span style="font-weight:bold;">Опублікувати</span>
            </label>
        </div>

        <div class="tabs">
            <button type="button" class="tab-btn active" onclick="openTab('uk')">Українська</button>
            <button type="button" class="tab-btn" onclick="openTab('en')">English</button>
        </div>

        <div id="tab-uk" class="tab-content active">
            <div class="form-group">
                <div class="form-label-row">
                    <label class="form-label">Заголовок (UK)</label>
                    <button type="button" class="btn-translate-manual" onclick="forceTranslate('title_en', 'title_uk', 'en', 'uk')">↻ з EN</button>
                </div>
                <input type="text" name="title_uk" id="title_uk" class="form-control" required value="<?= htmlspecialchars($editData['title_uk'] ?? '') ?>">
            </div>
            <div class="form-group">
                <div class="form-label-row">
                    <label class="form-label">Текст (UK)</label>
                    <button type="button" class="btn-translate-manual" onclick="forceTranslate('content_en', 'content_uk', 'en', 'uk')">↻ з EN</button>
                </div>
                <textarea name="content_uk" id="content_uk" class="form-control editor"><?= htmlspecialchars($editData['content_uk'] ?? '') ?></textarea>
            </div>
        </div>

        <div id="tab-en" class="tab-content">
            <div class="form-group">
                <div class="form-label-row">
                    <label class="form-label">Title (EN)</label>
                    <button type="button" class="btn-translate-manual" onclick="forceTranslate('title_uk', 'title_en', 'uk', 'en')">↻ з UK</button>
                </div>
                <input type="text" name="title_en" id="title_en" class="form-control" value="<?= htmlspecialchars($editData['title_en'] ?? '') ?>">
            </div>
            <div class="form-group">
                <div class="form-label-row">
                    <label class="form-label">Content (EN)</label>
                    <button type="button" class="btn-translate-manual" onclick="forceTranslate('content_uk', 'content_en', 'uk', 'en')">↻ з UK</button>
                </div>
                <textarea name="content_en" id="content_en" class="form-control editor"><?= htmlspecialchars($editData['content_en'] ?? '') ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-green" style="width: 100%; margin-top: 20px; padding: 15px;">Зберегти</button>
    </form>
<?php endif; ?>

    <script>
        function openTab(lang) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + lang).classList.add('active');
            const btns = document.querySelectorAll('.tab-btn');
            if (lang === 'uk') btns[0].classList.add('active');
            else btns[1].classList.add('active');
        }

        tinymce.init({
            selector: '.editor', height: 400, menubar: true,
            plugins: 'advlist autolink lists link image charmap preview searchreplace code fullscreen table wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            image_title: true, automatic_uploads: true, file_picker_types: 'image',
            images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('action', 'upload_tiny_image');
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                fetch('/admin/news.php', { method: 'POST', body: formData })
                    .then(r => r.ok ? r.json() : Promise.reject('Error'))
                    .then(json => resolve(json.location))
                    .catch(e => reject(e));
            }),
            setup: function(ed) {
                ed.on('blur', function(e) {
                    if (ed.id === 'content_uk') triggerAutoTranslate('content_uk', 'content_en', 'uk', 'en');
                    if (ed.id === 'content_en') triggerAutoTranslate('content_en', 'content_uk', 'en', 'uk');
                });
            }
        });

        // auto translate
        document.addEventListener('DOMContentLoaded', () => {
            setupAutoTranslate('title_uk', 'title_en', 'uk', 'en');
            setupAutoTranslate('title_en', 'title_uk', 'en', 'uk');
        });

        function setupAutoTranslate(sId, tId, fL, tL) {
            const s = document.getElementById(sId);
            if(s) s.addEventListener('blur', () => triggerAutoTranslate(sId, tId, fL, tL));
        }

        function triggerAutoTranslate(sId, tId, fL, tL) {
            let text = tinymce.get(sId) ? tinymce.get(sId).getContent({format:'text'}) : document.getElementById(sId).value.trim();
            let targetEmpty = tinymce.get(tId) ? (tinymce.get(tId).getContent({format:'text'}).trim() === '') : (document.getElementById(tId).value.trim() === '');
            if (text && targetEmpty) doTranslate(text, tId, fL, tL);
        }

        function forceTranslate(sId, tId, fL, tL) {
            if (!confirm('Перекласти заново?')) return;
            let text = tinymce.get(sId) ? tinymce.get(sId).getContent({format:'text'}) : document.getElementById(sId).value.trim();
            doTranslate(text, tId, fL, tL);
        }

        async function doTranslate(text, tId, fL, tL) {
            if (!text) return;
            document.body.style.cursor = 'wait';
            try {
                const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${fL}|${tL}`);
                const data = await res.json();
                if (data.responseStatus === 200) {
                    const trans = data.responseData.translatedText;
                    if (tinymce.get(tId)) tinymce.get(tId).setContent(trans);
                    else {
                        const el = document.getElementById(tId);
                        el.value = trans;
                        el.style.backgroundColor = '#d5f5e3';
                        setTimeout(() => el.style.backgroundColor = '', 1000);
                    }
                }
            } catch (e) { console.error(e); }
            finally { document.body.style.cursor = 'default'; }
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>