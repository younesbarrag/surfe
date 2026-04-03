<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $pdo;

    public function __construct() {
        // Connexion à la base de données (Singleton)
        $this->pdo = Database::getInstance();
    }

    // 🔐 LOGIN
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification mot de passe
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // 📝 REGISTER (CREATE USER)
    public function create($username, $email, $password, $role = 'student') {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $username,
            $email,
            $hashedPassword,
            $role
        ]);

        return $this->pdo->lastInsertId();
    }

    // 🔍 GET USER BY ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 📋 GET ALL USERS
    public function getAll() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ❌ DELETE USER
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}