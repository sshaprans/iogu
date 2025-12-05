<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {

        $host = getenv('DB_HOST') ?: 'db';
        $db   = getenv('DB_NAME') ?: 'site_db';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('MYSQL_ROOT_PASSWORD') ?: 'root_password';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}