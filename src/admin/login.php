<?php
// src/admin/login.php
require_once __DIR__ . '/../core/auth.php';

if (Auth::check()) {
    header('Location: /admin/index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (Auth::login($login, $password)) {
        header('Location: /admin/index.php');
        exit();
    } else {
        $error = 'Невірний логін або пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід в Адмін-панель</title>
    <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="login-page">

<div class="login-card">
    <h2>Адміністративна панель</h2>

    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label class="form-label">Логін</label>
            <input type="text" name="login" class="form-control" required autofocus placeholder="developer">
        </div>
        <div class="form-group">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required placeholder="******">
        </div>
        <button type="submit" class="btn" style="width: 100%;">Увійти</button>
    </form>

    <a href="/" class="login-back-link">← Повернутися на сайт</a>
</div>

</body>
</html>