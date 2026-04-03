<?php
session_start();
require_once __DIR__ . '/../model/student.php';
require_once __DIR__ . '/../model/lessons.php';
require_once __DIR__ . '/../model/admin.php';
require_once __DIR__ . '/../model/roll.php';

class AdminController {
    private $studentModel;
    private $lessonModel;
    private $adminModel;
    private $rollModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../views/auth/login.php');
            exit;
        }
        $this->studentModel = new Student();
        $this->lessonModel = new Lesson();
        $this->adminModel = new Admin();
        $this->rollModel = new Roll();
    }

    public function createLesson() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $coach = trim($_POST['coach'] ?? '');
            $lessonDate = $_POST['lesson_date'] ?? '';

            if (empty($title) || empty($coach) || empty($lessonDate)) {
                $_SESSION['error'] = 'Tous les champs sont obligatoires.';
                header('Location: ../views/admin/create_lesson.php');
                exit;
            }

            $this->lessonModel->create($title, $coach, $lessonDate);
            $_SESSION['success'] = 'Session de surf créée avec succès !';
            header('Location: ../views/admin/dashbord.php');
            exit;
        }
    }

    public function editStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['student_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $country = trim($_POST['country'] ?? '');
            $level = $_POST['level'] ?? 'Beginner';

            if ($id <= 0 || empty($name)) {
                $_SESSION['error'] = 'Données invalides.';
                header('Location: ../views/admin/dashbord.php');
                exit;
            }

            if (!in_array($level, ['Beginner', 'Intermediate', 'Advanced'])) {
                $level = 'Beginner';
            }

            $this->studentModel->update($id, $name, $country, $level);
            $_SESSION['success'] = 'Élève mis à jour avec succès !';
            header('Location: ../views/admin/dashbord.php');
            exit;
        }
    }

    public function deleteStudent() {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->studentModel->delete($id);
            $_SESSION['success'] = 'Élève supprimé.';
        }
        header('Location: ../views/admin/dashbord.php');
        exit;
    }

    public function enrollStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lessonId = intval($_POST['lesson_id'] ?? 0);
            $studentId = intval($_POST['student_id'] ?? 0);
            $paymentStatus = $_POST['payment_status'] ?? 'en attente';

            if ($lessonId > 0 && $studentId > 0) {
                $result = $this->lessonModel->enrollStudent($lessonId, $studentId, $paymentStatus);
                if ($result) {
                    $_SESSION['success'] = 'Élève inscrit à la session.';
                } else {
                    $_SESSION['error'] = 'Cet élève est déjà inscrit à cette session.';
                }
            }
            header('Location: ../views/admin/create_lesson.php?lesson_id=' . $lessonId);
            exit;
        }
    }

    public function togglePayment() {
        $enrollmentId = intval($_GET['id'] ?? 0);
        $redirect = $_GET['redirect'] ?? '../views/admin/dashbord.php';
        if ($enrollmentId > 0) {
            $this->rollModel->togglePayment($enrollmentId);
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function deleteLesson() {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->lessonModel->delete($id);
            $_SESSION['success'] = 'Session supprimée.';
        }
        header('Location: ../views/admin/dashbord.php');
        exit;
    }

    public function removeStudentFromLesson() {
        $lessonId = intval($_GET['lesson_id'] ?? 0);
        $studentId = intval($_GET['student_id'] ?? 0);
        if ($lessonId > 0 && $studentId > 0) {
            $this->lessonModel->removeStudent($lessonId, $studentId);
            $_SESSION['success'] = 'Élève retiré de la session.';
        }
        header('Location: ../views/admin/create_lesson.php?lesson_id=' . $lessonId);
        exit;
    }
}

$action = $_GET['action'] ?? '';
$controller = new AdminController();

switch ($action) {
    case 'create_lesson':
        $controller->createLesson();
        break;
    case 'edit_student':
        $controller->editStudent();
        break;
    case 'delete_student':
        $controller->deleteStudent();
        break;
    case 'enroll_student':
        $controller->enrollStudent();
        break;
    case 'toggle_payment':
        $controller->togglePayment();
        break;
    case 'delete_lesson':
        $controller->deleteLesson();
        break;
    case 'remove_student_lesson':
        $controller->removeStudentFromLesson();
        break;
    default:
        header('Location: ../views/admin/dashbord.php');
        break;
}
?>