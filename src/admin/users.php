<?php
// src/admin/users.php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/logger.php';

Auth::requireLogin();
$user = Auth::user();

if ($user['role'] === 'branch_admin') {
    die('Доступ заборонено');
}

$db = Database::getInstance()->getConnection();
$error = '';
$success = '';

$editData = null;
if (isset($_GET['edit_id'])) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_id']]);
    $editData = $stmt->fetch();
    if (!$editData) {
        header('Location: /admin/users.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $login = trim($_POST['login']);
        $pass = $_POST['password'];
        $name = trim($_POST['name']);
        $role = $_POST['role'];
        $branchId = !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null;

        if (empty($login) || empty($pass) || empty($name)) {
            $error = 'Заповніть всі обов\'язкові поля!';
        } else {
            $stmt = $db->prepare("SELECT id FROM users WHERE login = ?");
            $stmt->execute([$login]);
            if ($stmt->fetch()) {
                $error = 'Такий логін вже зайнятий!';
            } else {
                $hash = password_hash($pass, PASSWORD_BCRYPT);
                $stmt = $db->prepare("INSERT INTO users (login, password_hash, role, name, branch_id) VALUES (?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$login, $hash, $role, $name, $branchId]);
                    // LOG
                    Logger::log('create', 'user', $db->lastInsertId(), "Створено користувача $login ($role)");
                    $success = "Користувача $name успішно створено!";
                } catch (Exception $e) {
                    $error = 'Помилка БД: ' . $e->getMessage();
                }
            }
        }
    }

    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $login = trim($_POST['login']);
        $name = trim($_POST['name']);
        $role = $_POST['role'];
        $branchId = !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null;
        $newPass = $_POST['password'];

        $stmt = $db->prepare("SELECT id FROM users WHERE login = ? AND id != ?");
        $stmt->execute([$login, $id]);
        if ($stmt->fetch()) {
            $error = 'Такий логін вже зайнятий іншим користувачем!';
        } else {
            try {
                if (!empty($newPass)) {
                    $hash = password_hash($newPass, PASSWORD_BCRYPT);
                    $stmt = $db->prepare("UPDATE users SET login=?, password_hash=?, role=?, name=?, branch_id=? WHERE id=?");
                    $stmt->execute([$login, $hash, $role, $name, $branchId, $id]);
                    $details = "Оновлено дані та пароль для користувача $login";
                } else {
                    $stmt = $db->prepare("UPDATE users SET login=?, role=?, name=?, branch_id=? WHERE id=?");
                    $stmt->execute([$login, $role, $name, $branchId, $id]);
                    $details = "Оновлено дані користувача $login ($role)";
                }

                // LOG
                Logger::log('update', 'user', $id, $details);
                $success = "Дані користувача оновлено!";

                $editData = null;

            } catch (Exception $e) {
                $error = 'Помилка оновлення: ' . $e->getMessage();
            }
        }
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if ($id === $user['id']) {
            $error = 'Ви не можете видалити самі себе!';
        } else {
            $uName = $db->query("SELECT login FROM users WHERE id=$id")->fetchColumn();

            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);

            // LOG
            Logger::log('delete', 'user', $id, "Видалено користувача: $uName");
            $success = 'Користувача видалено.';
        }
    }
}

// GET
$usersList = $db->query("
    SELECT u.*, b.name_uk as branch_name 
    FROM users u 
    LEFT JOIN branches b ON u.branch_id = b.id 
    ORDER BY u.id ASC
")->fetchAll();

$branchesList = $db->query("SELECT id, name_uk FROM branches ORDER BY name_uk ASC")->fetchAll();

$pageTitle = 'Користувачі';
require_once __DIR__ . '/includes/header.php';
?>

    <div class="header">
        <h1>Керування користувачами</h1>
    </div>

<?php if ($error): ?>
    <div style="background:#fadbd8; color:#c0392b; padding:15px; border-radius:4px; margin-bottom:20px;"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div style="background:#d5f5e3; color:#27ae60; padding:15px; border-radius:4px; margin-bottom:20px;"><?= $success ?></div>
<?php endif; ?>

    <div class="form-card" style="margin-bottom: 30px; background: <?= $editData ? '#fffdf0' : '#fff' ?>;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3 style="margin:0;"><?= $editData ? 'Редагування користувача' : 'Додати нового користувача' ?></h3>
            <?php if($editData): ?>
                <a href="/admin/users" class="btn btn-gray" style="font-size:0.8em;">Скасувати</a>
            <?php endif; ?>
        </div>

        <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Ім'я (ПІБ)</label>
                <input type="text" name="name" class="form-control" required placeholder="Іван Іванов" value="<?= htmlspecialchars($editData['name'] ?? '') ?>">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Логін</label>
                <input type="text" name="login" class="form-control" required placeholder="admin_kyiv" value="<?= htmlspecialchars($editData['login'] ?? '') ?>">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Пароль</label>
                <input type="text" name="password" class="form-control" placeholder="<?= $editData ? 'Залиште пустим, щоб не міняти' : '******' ?>" <?= $editData ? '' : 'required' ?>>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Роль</label>
                <select name="role" class="form-control" id="roleSelect" onchange="toggleBranchSelect()">
                    <option value="branch_admin" <?= ($editData && $editData['role'] === 'branch_admin') ? 'selected' : '' ?>>Адмін Філії</option>
                    <option value="admin" <?= ($editData && $editData['role'] === 'admin') ? 'selected' : '' ?>>Головний Адмін (Центр)</option>
                    <?php if($user['role'] === 'dev'): ?>
                        <option value="dev" <?= ($editData && $editData['role'] === 'dev') ? 'selected' : '' ?>>Розробник</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" id="branchGroup" style="margin-bottom:0;">
                <label class="form-label">Філія</label>
                <select name="branch_id" class="form-control">
                    <option value="">-- Оберіть філію --</option>
                    <?php foreach ($branchesList as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= ($editData && $editData['branch_id'] == $b['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['name_uk']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <button type="submit" class="btn btn-green" style="width:100%; height: 42px;">
                    <?= $editData ? 'Зберегти зміни' : '+ Створити' ?>
                </button>
            </div>
        </form>
    </div>

    <!-- ТАБЛИЦЯ КОРИСТУВАЧІВ -->
    <div class="form-card" style="max-width: 100%;">
        <table>
            <thead>
            <tr>
                <th width="50">ID</th>
                <th width="60">Аватар</th>
                <th>Ім'я / Логін</th>
                <th>Роль</th>
                <th>Прив'язка</th>
                <th width="120">Дії</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usersList as $u): ?>
                <tr style="<?= ($editData && $editData['id'] == $u['id']) ? 'background:#f0f8ff;' : '' ?>">
                    <td><?= $u['id'] ?></td>
                    <td>
                        <?php if(!empty($u['avatar'])): ?>
                            <img src="<?= htmlspecialchars($u['avatar']) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <div style="width:40px; height:40px; border-radius:50%; background:#eee; color:#999; display:flex; align-items:center; justify-content:center; font-size:12px;">?</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($u['name']) ?></strong><br>
                        <span style="color:#777; font-size:0.9em;">@<?= htmlspecialchars($u['login']) ?></span>
                    </td>
                    <td>
                        <?php
                        $badgeColor = '#95a5a6';
                        $roleName = 'Невідомо';
                        if ($u['role'] === 'dev') { $badgeColor = '#8e44ad'; $roleName = 'Розробник'; }
                        if ($u['role'] === 'admin') { $badgeColor = '#c0392b'; $roleName = 'Головний Адмін'; }
                        if ($u['role'] === 'branch_admin') { $badgeColor = '#2980b9'; $roleName = 'Адмін Філії'; }
                        ?>
                        <span class="status-badge" style="background:<?= $badgeColor ?>; color:white;"><?= $roleName ?></span>
                    </td>
                    <td>
                        <?php if ($u['branch_id']): ?>
                            🏢 <?= htmlspecialchars($u['branch_name']) ?>
                        <?php elseif ($u['role'] === 'branch_admin'): ?>
                            <span style="color:red;">⚠ Не прив'язано!</span>
                        <?php else: ?>
                            <span style="color:#aaa;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="?edit_id=<?= $u['id'] ?>" class="btn btn-gray" style="padding: 5px 10px;" title="Редагувати">✎</a>

                            <?php if ($u['id'] !== $user['id']): ?>
                                <form method="POST" onsubmit="return confirm('Видалити користувача?');" style="margin:0;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-red" style="padding: 5px 10px;" title="Видалити">X</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleBranchSelect() {
            const role = document.getElementById('roleSelect').value;
            const branchGroup = document.getElementById('branchGroup');
            const branchSelect = branchGroup.querySelector('select');

            if (role === 'branch_admin') {
                branchGroup.style.opacity = '1';
                branchGroup.style.pointerEvents = 'auto';
                branchSelect.required = true;
            } else {
                branchGroup.style.opacity = '0.3';
                branchGroup.style.pointerEvents = 'none';
                if(!<?= $editData ? 'true' : 'false' ?>) branchSelect.value = "";
                branchSelect.required = false;
            }
        }
        toggleBranchSelect();
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>