<?php
session_start();
require_once __DIR__ . '/../model/student.php';
require_once __DIR__ . '/../model/lessons.php';

class StudentController {
    private $studentModel;
    private $lessonModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ../views/auth/login.php');
            exit;
        }
        $this->studentModel = new Student();
        $this->lessonModel = new Lesson();
    }

    public function getAgenda() {
        $student = $this->studentModel->findByUserId($_SESSION['user_id']);
        if ($student) {
            return $this->lessonModel->getLessonsByStudent($student['id']);
        }
        return [];
    }

    public function getStudentInfo() {
        return $this->studentModel->findByUserId($_SESSION['user_id']);
    }

    public function updateLevel() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $level = $_POST['level'] ?? 'Beginner';
            if (!in_array($level, ['Beginner', 'Intermediate', 'Advanced'])) {
                $level = 'Beginner';
            }
            $student = $this->studentModel->findByUserId($_SESSION['user_id']);
            if ($student) {
                $this->studentModel->updateLevel($student['id'], $level);
                $_SESSION['success'] = 'Niveau mis à jour !';
            }
            header('Location: ../views/student/lvl.php');
            exit;
        }
    }
}

$action = $_GET['action'] ?? '';
if ($action === 'update_level') {
    $controller = new StudentController();
    $controller->updateLevel();
}
?>