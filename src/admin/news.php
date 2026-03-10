<?php
// src/admin/news.php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Logger.php';
require_once __DIR__ . '/../core/Validator.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();

$uploadDir = __DIR__ . '/../img/news/';
$uploadContentDir = __DIR__ . '/../img/news/content/';
$uploadGalleryDir = __DIR__ . '/../img/news/gallery/';

$uploadUrlPath = '/img/news/';
$uploadContentUrlPath = '/img/news/content/';
$uploadGalleryUrlPath = '/img/news/gallery/';

if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
if (!is_dir($uploadContentDir)) @mkdir($uploadContentDir, 0777, true);
if (!is_dir($uploadGalleryDir)) @mkdir($uploadGalleryDir, 0777, true);

function validateImageUpload($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) throw new Exception('Помилка завантаження');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redirectUrl = '/admin/news.php';

    if ($action === 'upload_tiny_image') {
        header('Content-Type: application/json');
        try {
            $ext = validateImageUpload($_FILES['file'] ?? null);
            $filename = 'content_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadContentDir . $filename)) {
                $uploadedPath = $uploadContentUrlPath . $filename;
                Logger::log('upload', 'media', 0, "Завантажено зображення для TinyMCE: $filename");
                echo json_encode(['location' => $uploadedPath]);
            } else throw new Exception('Server error');
        } catch (Exception $e) {
            http_response_code(500); echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
        Logger::log('delete', 'news', $id, "Видалено новину ID: $id");
        header('Location: ' . $redirectUrl);
        exit;
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

            $existingGallery = !empty($_POST['current_header_gallery']) ? json_decode($_POST['current_header_gallery'], true) : [];
            if (!is_array($existingGallery)) $existingGallery = [];
            if (!empty($_POST['delete_gallery_images'])) {
                $existingGallery = array_diff($existingGallery, $_POST['delete_gallery_images']);
            }
            if (isset($_FILES['header_gallery'])) {
                $files = $_FILES['header_gallery'];
                $count = is_array($files['name']) ? count($files['name']) : 0;
                for ($i = 0; $i < $count; $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        try {
                            $tmpName = $files['tmp_name'][$i];
                            $originalName = $files['name'][$i];
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                            $mime = $finfo->file($tmpName);
                            if (in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                                $newFilename = 'gallery_' . time() . '_' . $i . '.' . $ext;
                                if (move_uploaded_file($tmpName, $uploadGalleryDir . $newFilename)) {
                                    $existingGallery[] = $uploadGalleryUrlPath . $newFilename;
                                }
                            }
                        } catch (Exception $e) {}
                    }
                }
            }
            $headerGalleryJson = !empty($existingGallery) ? json_encode(array_values($existingGallery)) : null;

            $params = [
                    $slug, $imagePath, $headerGalleryJson, $_POST['date_posted'],
                    $titleUk, trim($_POST['title_en']),
                    $_POST['content_uk'], $_POST['content_en'],
                    isset($_POST['is_published']) ? 1 : 0
            ];

            if ($id) {
                $sql = "UPDATE news SET slug=?, image=?, header_gallery=?, date_posted=?, title_uk=?, title_en=?, content_uk=?, content_en=?, is_published=? WHERE id=?";
                $params[] = $id;
            } else {
                $sql = "INSERT INTO news (slug, image, header_gallery, date_posted, title_uk, title_en, content_uk, content_en, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            if ($id) {
                Logger::log('update', 'news', $id, "Оновлено новину: $titleUk");
            } else {
                $newId = $db->lastInsertId();
                Logger::log('create', 'news', $newId, "Створено новину: $titleUk");
            }

            header('Location: ' . $redirectUrl);
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$view = $_GET['view'] ?? 'list';
$editData = null;

if ($view === 'edit' && isset($_GET['id'])) {
    $fetchId = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$fetchId]);
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
            <th width="120">Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($newsList as $news): ?>
            <tr>
                <td><?php if($news['image']): ?><img src="<?= htmlspecialchars($news['image']) ?>" class="news-thumb" alt=""><?php endif; ?></td>
                <td><?= htmlspecialchars($news['date_posted']) ?></td>
                <td><strong><?= $news['title_uk'] ?></strong></td>
                <td>
                    <a href="/admin/news.php?view=edit&id=<?= $news['id'] ?>" class="btn btn-gray">✎</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Видалити?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $news['id'] ?>">
                        <button type="submit" class="btn btn-red">X</button>
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

    <form class="form-card" method="POST" enctype="multipart/form-data" id="newsForm">
        <input type="hidden" name="action" value="save">
        <?php if($editData): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($editData['image']) ?>">
            <input type="hidden" name="current_header_gallery" value="<?= htmlspecialchars($editData['header_gallery'] ?? '') ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group"><label class="form-label">Дата публікації</label><input type="date" name="date_posted" class="form-control" required value="<?= $editData['date_posted'] ?? date('Y-m-d') ?>"></div>
            <div class="form-group"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" placeholder="auto" value="<?= htmlspecialchars($editData['slug'] ?? '') ?>"></div>
        </div>

        <div class="form-group" style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
            <label class="form-label">Головне фото (Прев'ю)</label>
            <?php if(!empty($editData['image'])): ?><img src="<?= htmlspecialchars($editData['image']) ?>" style="height: 80px; border-radius: 4px; margin-bottom:5px;"><?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>


        <div class="form-group" style="margin-top:15px;"><label><input type="checkbox" name="is_published" <?= (!isset($editData) || $editData['is_published']) ? 'checked' : '' ?>> Опублікувати</label></div>

        <div class="tabs">
            <button type="button" class="tab-btn active" onclick="openTab('uk')">UK (Українська)</button>
            <button type="button" class="tab-btn" onclick="openTab('en')">EN (Англійська)</button>
        </div>

        <div id="tab-uk" class="tab-content active">
            <div class="form-group"><label class="form-label">Заголовок (UK)</label><div style="display:flex; align-items:center; gap:10px;"><input type="text" name="title_uk" id="title_uk" class="form-control" value="<?= $editData['title_uk'] ?? '' ?>"><button type="button" class="btn btn-gray" onclick="translateField('title_uk', 'title_en', 'uk', 'en')" title="Перекласти заголовок на EN">➞ EN</button></div></div>
            <div class="form-group"><label class="form-label">Текст (UK)</label><textarea name="content_uk" id="content_uk" class="editor"><?= htmlspecialchars($editData['content_uk'] ?? '') ?></textarea><button type="button" class="btn btn-gray" onclick="translateEditor('content_uk', 'content_en', 'uk', 'en')" style="margin-top:5px; font-size:12px;">⬇ Перекласти текст на EN</button></div>
        </div>

        <div id="tab-en" class="tab-content">
            <div class="form-group"><label class="form-label">Title (EN)</label><div style="display:flex; align-items:center; gap:10px;"><input type="text" name="title_en" id="title_en" class="form-control" value="<?= $editData['title_en'] ?? '' ?>"><button type="button" class="btn btn-gray" onclick="translateField('title_en', 'title_uk', 'en', 'uk')" title="Перекласти заголовок на UK">➞ UK</button></div></div>
            <div class="form-group"><div class="form-label-row"><label class="form-label">Content (EN)</label><button type="button" class="btn-translate-manual" onclick="forceTranslate('content_uk', 'content_en', 'uk', 'en')">↻ з UK</button></div><textarea name="content_en" id="content_en" class="form-control editor"><?= htmlspecialchars($editData['content_en'] ?? '') ?></textarea></div>
        </div>

        <button type="submit" class="btn btn-green" style="width: 100%; margin-top: 20px; padding: 15px;">Зберегти новину</button>
    </form>
<?php endif; ?>

    <script>
        function openTab(lang) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + lang).classList.add('active');
            const btns = document.querySelectorAll('.tab-btn');
            if (lang === 'uk') btns[0].classList.add('active'); else btns[1].classList.add('active');
        }
        async function fetchTranslation(text, fromLang, toLang) {
            if (!text) return '';
            const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${fromLang}|${toLang}`;
            try {
                const res = await fetch(url);
                const data = await res.json();
                if (data.responseStatus === 200) return data.responseData.translatedText;
                else { console.warn('API Error', data); return null; }
            } catch (e) { console.error(e); return null; }
        }
        async function translateField(sourceId, targetId, fromLang, toLang) {
            const sourceVal = document.getElementById(sourceId).value.trim();
            if (!sourceVal) return alert('Поле пусте!');
            document.body.style.cursor = 'wait';
            const transVal = await fetchTranslation(sourceVal, fromLang, toLang);
            document.body.style.cursor = 'default';
            if (transVal) {
                const targetEl = document.getElementById(targetId);
                targetEl.value = transVal;
                targetEl.style.backgroundColor = '#d5f5e3';
                setTimeout(() => targetEl.style.backgroundColor = '', 1000);
            } else alert('Помилка перекладу');
        }
        async function translateEditor(sourceId, targetId, fromLang, toLang) {
            if (typeof tinymce === 'undefined') return;
            const sourceEditor = tinymce.get(sourceId);
            const targetEditor = tinymce.get(targetId);
            if (!sourceEditor || !targetEditor) return;
            const sourceHtml = sourceEditor.getContent();
            if (!sourceHtml.trim()) return alert('Редактор пустий!');
            if (!confirm('Переклад HTML вмісту може бути неточним. Продовжити?')) return;
            document.body.style.cursor = 'wait';
            const transHtml = await fetchTranslation(sourceHtml, fromLang, toLang);
            document.body.style.cursor = 'default';
            if (transHtml) {
                targetEditor.setContent(transHtml);
                alert('Переклад вставлено в редактор');
            } else alert('Помилка перекладу');
        }
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.editor', height: 400, menubar: true,
                plugins: 'advlist autolink lists link image charmap preview searchreplace code fullscreen table wordcount',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
                automatic_uploads: true,
                images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('action', 'upload_tiny_image');
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    fetch('/admin/news.php', { method: 'POST', body: formData })
                        .then(r => r.json()).then(json => resolve(json.location)).catch(e => reject(e));
                })
            });
        }
    </script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>