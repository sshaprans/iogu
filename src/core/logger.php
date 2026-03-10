<?php
// src/core/Logger.php
require_once __DIR__ . '/i18n.php';


class Logger {
    public static function log(string $action, string $entityType, string $entityId, string $details = ''): void {
        if (!Auth::check()) {
            return;
        }

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

    public static function getLogs(int $limit = 50): array {
        if (!Auth::check()) {
            return [];
        }

        $user = Auth::user();
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM activity_logs";
        $params = [];

        if ($user['role'] === 'admin') {
            $sql .= " WHERE user_role IN ('admin', 'branch_admin')";
        } elseif ($user['role'] === 'branch_admin') {
            $sql .= " WHERE user_id = ?";
            $params[] = $user['id'];
        }

        $sql .= " ORDER BY created_at DESC LIMIT " . $limit;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}