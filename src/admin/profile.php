<?php
// src/admin/profile.php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Logger.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();
$user = Auth::user();
$dev = Auth::isDev();
$message = '';
$error = '';

$avatarDir = __DIR__ . '/../img/avatars/';
$avatarUrlPath = '/img/avatars/';
if (!is_dir($avatarDir)) @mkdir($avatarDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';
    $avatarPath = $_POST['current_avatar'];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $filename = 'user_' . $user['id'] . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarDir . $filename)) {
                $avatarPath = $avatarUrlPath . $filename;
            }
        } else {
            $error = 'Тільки JPG, PNG, WEBP';
        }
    }

    if (!$error && !empty($newPass)) {
        if ($newPass !== $confirmPass) $error = 'Паролі не співпадають!';
        elseif (strlen($newPass) < 6) $error = 'Пароль надто короткий (мінімум 6 символів)';
    }

    if (!$error) {
        try {
            if (!empty($newPass)) {
                $hash = password_hash($newPass, PASSWORD_BCRYPT);
                $stmt = $db->prepare("UPDATE users SET name = ?, avatar = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$name, $avatarPath, $hash, $user['id']]);
                Logger::log('update', 'profile', $user['id'], "Змінено профіль та пароль");
            } else {
                $stmt = $db->prepare("UPDATE users SET name = ?, avatar = ? WHERE id = ?");
                $stmt->execute([$name, $avatarPath, $user['id']]);
                Logger::log('update', 'profile', $user['id'], "Змінено дані профілю");
            }
            $_SESSION['user_name'] = $name;
            $message = 'Профіль успішно оновлено!';
        } catch (Exception $e) {
            $error = 'Помилка бази даних: ' . $e->getMessage();
        }
    }
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$userData = $stmt->fetch();

$pageTitle = 'Налаштування профілю';
require_once __DIR__ . '/includes/header.php';
?>

    <div class="header"><h1>Налаштування профілю</h1></div>

<?php if ($message): ?><div class="alert-success"><?= $message ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert-danger"><?= $error ?></div><?php endif; ?>

    <form class="form-card" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
        <input type="hidden" name="current_avatar" value="<?= htmlspecialchars($userData['avatar'] ?? '') ?>">
        <div class="form-group" style="text-align:center;">
            <?php if (!empty($userData['avatar'])): ?>
                <img src="<?= htmlspecialchars($userData['avatar']) ?>" style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:10px; border: 3px solid #eee;">
            <?php else: ?>
                <div style="width:120px; height:120px; border-radius:50%; background:#eee; display:inline-flex; align-items:center; justify-content:center; font-size:40px; color:#ccc; margin: 0 auto 10px auto;">?</div>
            <?php endif; ?>
            <br>
            <label class="btn btn-gray" style="cursor:pointer;">📸 Змінити фото<input type="file" name="avatar" style="display:none;" accept="image/*" onchange="this.form.submit()"></label>
        </div>
        <div class="form-group"><label class="form-label">Ваше ім'я</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userData['name']) ?>" required></div>
        <div class="form-group"><label class="form-label">Логін (не змінюється)</label><input type="text" class="form-control" value="<?= htmlspecialchars($userData['login']) ?>" disabled style="background:#eee; color: #777;"></div>
        <hr style="margin:30px 0; border:0; border-top:1px solid #eee;">
        <h3 style="margin-top:0;">Зміна паролю</h3>
        <div class="form-group"><label class="form-label">Новий пароль</label><input type="password" name="new_password" class="form-control" placeholder="Введіть новий пароль"></div>
        <div class="form-group"><label class="form-label">Повторіть пароль</label><input type="password" name="confirm_password" class="form-control" placeholder="Повторіть новий пароль"><div class="hint">Заповнюйте ці поля, тільки якщо хочете змінити пароль. Мінімум 6 символів.</div></div>
        <button type="submit" class="btn btn-green" style="width:100%; padding: 12px; font-size: 16px;">Зберегти зміни</button>
    </form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>