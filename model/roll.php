<?php
require_once __DIR__ . '/Database.php';

class Roll {
    private $pdo;

    public function __construct() {
        $db = new Dbs();
        $this->pdo = $db->getPdo();
    }

    public function getEnrollmentsByLesson($lessonId) {
        $stmt = $this->pdo->prepare(
            "SELECT ls.*, s.name, s.level, s.country 
             FROM lesson_student ls 
             JOIN students s ON ls.student_id = s.id 
             WHERE ls.lesson_id = ?"
        );
        $stmt->execute([$lessonId]);
        return $stmt->fetchAll();
    }

    public function togglePayment($enrollmentId) {
        $stmt = $this->pdo->prepare("SELECT payment_status FROM lesson_student WHERE id = ?");
        $stmt->execute([$enrollmentId]);
        $row = $stmt->fetch();
        if ($row) {
            $newStatus = ($row['payment_status'] === 'payé') ? 'en attente' : 'payé';
            $stmt = $this->pdo->prepare("UPDATE lesson_student SET payment_status = ? WHERE id = ?");
            return $stmt->execute([$newStatus, $enrollmentId]);
        }
        return false;
    }
}
?>