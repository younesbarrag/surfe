<?php

class Dbs {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->pdo = Database::getInstance();
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>