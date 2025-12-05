<?php
// src/core/auth.php
require_once __DIR__ . '/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    /**
     * Спроба входу користувача
     */
    public static function login($login, $password) {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE login = :login LIMIT 1");
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_branch_id'] = $user['branch_id'];
            return true;
        }

        return false;
    }

    /**
     * exit
     */
    public static function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_branch_id']);
        session_destroy();
    }

    /**
     * detect
     */
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    /**
     * super admin
     */
    public static function isDev() {
        return self::check() && $_SESSION['user_role'] === 'dev';
    }

    public static function user() {
        if (!self::check()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
            'branch_id' => $_SESSION['user_branch_id']
        ];
    }

    public static function requireLogin() {
        if (!self::check()) {
            header('Location: /admin/login');
            exit();
        }
    }
}