<?php
session_start();
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/student.php';

class AuthController {
    private $userModel;
    private $studentModel;

    public function __construct() {
        $this->userModel = new User();
        $this->studentModel = new Student();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Veuillez remplir tous les champs.';
                header('Location: ../views/auth/login.php');
                exit;
            }

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: ../views/admin/dashbord.php');
                } else {
                    header('Location: ../views/student/agenda.php');
                }
                exit; 
            } else {
                $_SESSION['error'] = 'Email ou mot de passe incorrect.';
                header('Location: ../views/auth/login.php');
                exit;
            }
        }
    }
}

$action = $_GET['action'] ?? '';
if ($action === 'login') {
    $auth = new AuthController();
    $auth->login();
}
