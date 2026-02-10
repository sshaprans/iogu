<?php
// src/admin/files.php
require_once __DIR__ . '/../core/auth.php';

Auth::requireLogin();

// Базова папка для завантажень (створіть папку src/uploads вручну або вона створиться сама)
$baseUploadDir = __DIR__ . '/../uploads/';
$baseUploadUrl = '/uploads/';

if (!is_dir($baseUploadDir)) {
    @mkdir($baseUploadDir, 0777, true);
}

// Отримання списку папок
$folders = array_filter(glob($baseUploadDir . '*'), 'is_dir');
$folderNames = array_map('basename', $folders);

$message = '';
$uploadedPath = '';

// Обробка завантаження
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    try {
        $folderName = trim($_POST['existing_folder']);

        // Якщо введено нову папку, використовуємо її
        if (!empty($_POST['new_folder'])) {
            $newFolder = trim($_POST['new_folder']);
            // Очистка назви папки від зайвих символів
            $newFolder = preg_replace('/[^a-zA-Z0-9_-]/', '', $newFolder);
            if ($newFolder) {
                $folderName = $newFolder;
            }
        }

        // Якщо папка не вибрана і не створена - кидаємо в корінь uploads
        if (empty($folderName)) {
            $targetDir = $baseUploadDir;
            $webDir = $baseUploadUrl;
        } else {
            $targetDir = $baseUploadDir . $folderName . '/';
            $webDir = $baseUploadUrl . $folderName . '/';
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Помилка завантаження файлу.');
        }

        // Очистка імені файлу (транслітерація або просто видалення спецсимволів)
        $filename = basename($file['name']);
        $filename = preg_replace('/[^\w\-\.]/', '_', $filename); // Заміна пробілів та іншого на _

        $destination = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $message = "Файл успішно завантажено!";
            // Формуємо шлях, як ви просили
            $uploadedPath = '<?= FILE_PATH ?>' . $webDir . $filename;
        } else {
            throw new Exception('Не вдалося перемістити файл.');
        }

        // Оновлюємо список папок
        $folders = array_filter(glob($baseUploadDir . '*'), 'is_dir');
        $folderNames = array_map('basename', $folders);

    } catch (Exception $e) {
        $message = "Помилка: " . $e->getMessage();
    }
}

$pageTitle = 'Файловий менеджер';
require_once __DIR__ . '/includes/header.php';
?>

    <div class="header">
        <h1>Завантаження файлів</h1>
        <a href="/admin/index.php" class="btn btn-gray">Назад в меню</a>
    </div>

<?php if ($message): ?>
    <div class="alert <?= strpos($message, 'Помилка') !== false ? 'alert-danger' : 'alert-success' ?>"
         style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

    <div class="form-card" style="max-width: 600px; margin: 0 auto;">
        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Оберіть папку</label>
                <select name="existing_folder" class="form-control">
                    <option value="">-- Коренева папка (uploads) --</option>
                    <?php foreach ($folderNames as $folder): ?>
                        <option value="<?= htmlspecialchars($folder) ?>"><?= htmlspecialchars($folder) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-top: 10px; padding-left: 20px; border-left: 3px solid #eee;">
                <label class="form-label" style="font-size: 0.9em; color: #666;">Або створіть нову папку</label>
                <input type="text" name="new_folder" class="form-control" placeholder="Назва нової папки (напр. documents)">
            </div>

            <div class="form-group">
                <label class="form-label">Файл</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-green" style="width: 100%; margin-top: 20px; padding: 15px;">Завантажити</button>
        </form>

        <?php if ($uploadedPath): ?>
            <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 5px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Шлях до файлу (скопіюйте):</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" value="<?= htmlspecialchars($uploadedPath) ?>" id="resultPath" class="form-control" readonly>
                    <button onclick="copyPath()" class="btn btn-gray">Копіювати</button>
                </div>

                <div style="margin-top: 10px;">
                    <small>Попередній перегляд (якщо це зображення):</small><br>
                    <!-- Тут ми прибираємо PHP тег для прев'ю, бо браузер його не зрозуміє, підставляємо реальний шлях -->
                    <?php $realUrl = str_replace('<?= FILE_PATH ?>', '', $uploadedPath); ?>
                    <a href="<?= $realUrl ?>" target="_blank" style="color: blue; text-decoration: underline;">Відкрити файл</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function copyPath() {
            var copyText = document.getElementById("resultPath");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Шлях скопійовано: " + copyText.value);
        }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>