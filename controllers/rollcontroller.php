<?php
session_start();
require_once __DIR__ . '/../model/roll.php';

class RollController {
    private $rollModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../views/auth/login.php');
            exit;
        }
        $this->rollModel = new Roll();
    }

    public function getEnrollments($lessonId) {
        return $this->rollModel->getEnrollmentsByLesson($lessonId);
    }

    public function togglePayment() {
        $enrollmentId = intval($_GET['id'] ?? 0);
        $lessonId = intval($_GET['lesson_id'] ?? 0);
        if ($enrollmentId > 0) {
            $this->rollModel->togglePayment($enrollmentId);
            $_SESSION['success'] = 'Statut de paiement mis à jour.';
        }
        header('Location: ../views/admin/create_lesson.php?lesson_id=' . $lessonId);
        exit;
    }
}

$action = $_GET['action'] ?? '';
if ($action === 'toggle_payment') {
    $controller = new RollController();
    $controller->togglePayment();
}
?>
