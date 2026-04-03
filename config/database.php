<?php

class Database {
    private $host = 'localhost';
    private $dbName = 'surf_school';
    private $user = 'root';
    private $password = '';
    private $pdo;

    public function connect() {
        $this->pdo = null;
        try {
            $this->pdo = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=utf8mb4',
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die('Database Error: ' . $e->getMessage());
        }
        return $this->pdo;
    }

    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $db = new Database();
            $instance = $db->connect();
        }
        return $instance;
    }
}

$pdo = Database::getInstance();
?>