<?php
// src/core/logger.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

class Logger {
    public static function log($action, $entityType, $entityId, $details = '') {
        if (!Auth::check()) return;

        $user = Auth::user();
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, user_name, user_role, action, entity_type, entity_id, details) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user['id'],
            $user['name'],
            $user['role'],
            $action,
            $entityType,
            $entityId,
            $details
        ]);
    }

    public static function getLogs($limit = 50) {
        if (!Auth::check()) return [];
        $user = Auth::user();
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM activity_logs";
        $params = [];


        if ($user['role'] === 'dev') {
        }
        elseif ($user['role'] === 'admin') {
            $sql .= " WHERE user_role IN ('admin', 'branch_admin')";
        }
        elseif ($user['role'] === 'branch_admin') {
            $sql .= " WHERE user_id = ?";
            $params[] = $user['id'];
        }

        $sql .= " ORDER BY created_at DESC LIMIT " . (int)$limit;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
?>