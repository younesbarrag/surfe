<?php
require_once __DIR__ . '/Database.php';

class Admin {
    private $pdo;

    public function __construct() {
        $db = new Dbs();
        $this->pdo = $db->getPdo();
    }

    public function getTotalStudents() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM students");
        return $stmt->fetch()['total'];
    }

    public function getTotalLessons() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM lessons");
        return $stmt->fetch()['total'];
    }

    public function getUpcomingLessons() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM lessons WHERE lesson_date >= NOW()");
        return $stmt->fetch()['total'];
    }

    public function getPendingPayments() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM lesson_student WHERE payment_status = 'en attente'");
        return $stmt->fetch()['total'];
    }
}
?>