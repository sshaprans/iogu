<?php
// src/admin/includes/header.php
$currentPage = basename($_SERVER['PHP_SELF']);
$user = Auth::user();

$dbHeader = Database::getInstance()->getConnection();
$stmtH = $dbHeader->prepare("SELECT name, role, avatar FROM users WHERE id = ?");
$stmtH->execute([$user['id']]);
$freshUser = $stmtH->fetch();
$userAvatar = !empty($freshUser['avatar']) ? $freshUser['avatar'] : null;
$userName = htmlspecialchars($freshUser['name'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Адмін-панель' ?></title>
    <link rel="stylesheet" href="/admin/assets/admin.css?v=1.2">
    <?php if(isset($useTinyMCE) && $useTinyMCE): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="no-referrer"></script>
    <?php endif; ?>
    <style>
        .user-profile-widget { padding: 20px; text-align: center; background: #1a252f; border-bottom: 1px solid #34495e; }
        .user-avatar-small { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #3498db; margin-bottom: 10px; }
        .user-name { color: #ecf0f1; font-weight: bold; display: block; }
        .user-role { color: #7f8c8d; font-size: 0.85em; }

        .sidebar-footer { padding: 20px; margin-top: auto; border-top: 1px solid #34495e; }
        .util-btn { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; margin-bottom: 10px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9em; font-weight: bold; transition: 0.2s; }
        .btn-site { background: #34495e; color: #ecf0f1; }
        .btn-site:hover { background: #2c3e50; color: #fff; }
        .btn-cache { background: #e67e22; color: #fff; }
        .btn-cache:hover { background: #d35400; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="user-profile-widget">
        <a href="/admin/profile.php" style="text-decoration:none;">
            <?php if($userAvatar): ?>
                <img src="<?= htmlspecialchars($userAvatar) ?>" class="user-avatar-small" alt="Avatar">
            <?php else: ?>
                <div class="user-avatar-small" style="background:#34495e; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-size:24px;">
                    <?= mb_substr($userName, 0, 1) ?>
                </div>
            <?php endif; ?>
            <span class="user-name"><?= $userName ?></span>
            <span class="user-role"><?= htmlspecialchars($freshUser['role']) ?></span>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li><a href="/admin/index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">Головна / Логи</a></li>
        <li><a href="/admin/profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">Мій профіль</a></li>
        <li style="border-top: 1px solid #34495e; margin: 10px 0;"></li>
        <li><a href="/admin/translations.php" class="<?= $currentPage == 'translations.php' ? 'active' : '' ?>">Переклади сайту</a></li>
        <li><a href="/admin/news.php" class="<?= $currentPage == 'news.php' ? 'active' : '' ?>">Новини</a></li>
        <li><a href="/admin/branches.php" class="<?= $currentPage == 'branches.php' ? 'active' : '' ?>">Філії</a></li>
        <li>
            <a href="/admin/messages.php" class="<?= $currentPage == 'messages.php' ? 'active' : '' ?>">
                📬 Повідомлення
            </a>
        </li>
        <?php if($user['role'] === 'dev' || $user['role'] === 'admin'): ?>
            <li>
                <a href="/admin/users.php" class="<?= $currentPage == 'users.php' ? 'active' : '' ?>">
                    👥 Користувачі
                </a>
            </li>
        <?php endif; ?>

        <li><a href="/admin/settings.php" class="<?= $currentPage == 'settings.php' ? 'active' : '' ?>">Налаштування</a></li>

        <li><a href="/admin/index.php?logout=true" style="color: #e74c3c; margin-top: 20px;">Вихід</a></li>
    </ul>

    <div class="sidebar-footer">
        <a href="/" target="_blank" class="util-btn btn-site" title="Відкрити сайт у новій вкладці">
            <span>🌍</span> На сайт
        </a>
        <button onclick="hardRefresh()" class="util-btn btn-cache" title="Очистити кеш браузера та перезавантажити">
            <span>🧹</span> Очистити кеш
        </button>
    </div>
</div>

<div class="main-content">

    <script>
        function hardRefresh() {
            if(confirm('Перезавантажити сторінку зі скиданням кешу?')) {
                window.location.reload(true);
            }
        }
    </script>