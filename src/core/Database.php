<?php
/**
 * src/core/Database.php
 */

require_once __DIR__ . '/Config.php';

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $host = config('db.host') ?: getenv('DB_HOST') ?: 'db';
        $db   = config('db.name') ?: getenv('DB_NAME') ?: 'site_db';
        $user = config('db.user') ?: getenv('DB_USER') ?: 'root';
        $pass = config('db.pass') ?: getenv('MYSQL_ROOT_PASSWORD') ?: 'root_password';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log("DB Connection Error: " . $e->getMessage());
            if (config('debug')) {
                die("Database connection failed. Check error logs.");
            }
            die("Database connection failed.");
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }
}