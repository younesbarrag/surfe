<?php
require_once __DIR__ . '/Database.php';

class Lesson {
    private $pdo;
    private $id;
    private $title;
    private $coach;
    private $lessonDate;

    public function __construct() {
        $db = new Dbs();
        $this->pdo = $db->getPdo();
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getCoach() { return $this->coach; }
    public function getLessonDate() { return $this->lessonDate; }

    // Setters
    public function setTitle($title) { $this->title = $title; }
    public function setCoach($coach) { $this->coach = $coach; }
    public function setLessonDate($date) { $this->lessonDate = $date; }

    public function create($title, $coach, $lessonDate) {
        $stmt = $this->pdo->prepare("INSERT INTO lessons (title, coach, lesson_date) VALUES (?, ?, ?)");
        $stmt->execute([$title, $coach, $lessonDate]);
        return $this->pdo->lastInsertId();
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM lessons ORDER BY lesson_date ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM lessons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function enrollStudent($lessonId, $studentId, $paymentStatus = 'en attente') {
        // Check if already enrolled
        $stmt = $this->pdo->prepare("SELECT id FROM lesson_student WHERE lesson_id = ? AND student_id = ?");
        $stmt->execute([$lessonId, $studentId]);
        if ($stmt->fetch()) {
            return false;
        }
        $stmt = $this->pdo->prepare("INSERT INTO lesson_student (lesson_id, student_id, payment_status) VALUES (?, ?, ?)");
        return $stmt->execute([$lessonId, $studentId, $paymentStatus]);
    }

    public function getEnrolledStudents($lessonId) {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, ls.payment_status, ls.id as enrollment_id 
             FROM lesson_student ls 
             JOIN students s ON ls.student_id = s.id 
             WHERE ls.lesson_id = ?"
        );
        $stmt->execute([$lessonId]);
        return $stmt->fetchAll();
    }

    public function getLessonsByStudent($studentId) {
        $stmt = $this->pdo->prepare(
            "SELECT l.*, ls.payment_status 
             FROM lesson_student ls 
             JOIN lessons l ON ls.lesson_id = l.id 
             WHERE ls.student_id = ? 
             ORDER BY l.lesson_date ASC"
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function updatePaymentStatus($enrollmentId, $status) {
        $stmt = $this->pdo->prepare("UPDATE lesson_student SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $enrollmentId]);
    }

    public function removeStudent($lessonId, $studentId) {
        $stmt = $this->pdo->prepare("DELETE FROM lesson_student WHERE lesson_id = ? AND student_id = ?");
        return $stmt->execute([$lessonId, $studentId]);
    }
}
?>
