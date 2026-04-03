<?php
require_once __DIR__ . '/Database.php';

class Student {
    private $pdo;

    public function __construct() {
        $db = new Dbs();
        $this->pdo = $db->getPdo();
    }

    // 1. جلب الطالب بواسطة معرف المستخدم (للأجندة)
    public function findByUserId($userId) {
        $sql = "SELECT * FROM students WHERE user_id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT s.*, u.email FROM students s 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $country, $level) {
        $sql = "UPDATE students SET name = ?, country = ?, level = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $country, $level, $id]);
    }

    public function updateLevel($id, $level) {
        $sql = "UPDATE students SET level = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$level, $id]);
    }

    public function create($userId, $name, $country, $level = 'Beginner') {
        $sql = "INSERT INTO students (user_id, name, country, level) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $name, $country, $level]);
        return $this->pdo->lastInsertId();
    }

    public function delete($id) {
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findAll() {
        $sql = "SELECT s.*, u.email FROM students s 
                LEFT JOIN users u ON s.user_id = u.id 
                ORDER BY s.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}