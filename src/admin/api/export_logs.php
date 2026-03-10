<?php
// src/admin/api/export_logs.php
require_once __DIR__ . '/../../core/Auth.php';
require_once __DIR__ . '/../../core/Database.php';

Auth::requireLogin();

$user = Auth::user();
if (!$user || !in_array($user['role'], ['admin', 'dev'], true)) {
    http_response_code(403);
    die('Доступ заборонено. Експорт доступний лише для адміністраторів та розробників.');
}

$filename = 'iogu_logs_' . date('Y-m-d_H-i-s') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($output, [
    'ID',
    'User ID',
    'User Name',
    'Role',
    'Action',
    'Entity Type',
    'Entity ID',
    'Details',
    'Created At'
]);

try {
    $db = Database::getInstance()->getConnection();
    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

    $stmt = $db->query("SELECT id, user_id, user_name, user_role, action, entity_type, entity_id, details, created_at FROM activity_logs ORDER BY created_at DESC");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['id'],
            $row['user_id'],
            $row['user_name'],
            $row['user_role'],
            $row['action'],
            $row['entity_type'],
            $row['entity_id'],
            $row['details'],
            $row['created_at']
        ]);
    }

    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

} catch (Exception $e) {
    error_log("[Export Logs Error] " . $e->getMessage());
}

fclose($output);
exit;