<?php
require_once __DIR__ . '/../config/database.php';

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            $this->pdo->exec("SET NAMES utf8mb4");
        }
        catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            die('Database Connection Error. Por favor contacte al administrador.');
        }
    }

    // OPT-03: Prevenir clonación de la instancia singleton
    private function __clone() {}

    // OPT-03: Prevenir deserialización de la instancia singleton
    public function __wakeup()
    {
        throw new \Exception('No se puede deserializar el singleton Database.');
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
