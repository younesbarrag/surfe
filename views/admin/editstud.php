<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../model/student.php';

$studentModel = new Student();
$studentId = intval($_GET['id'] ?? 0);

if ($studentId <= 0) {
    header('Location: dashbord.php');
    exit;
}

$student = $studentModel->findById($studentId);
if (!$student) {
    $_SESSION['error'] = 'Élève introuvable.';
    header('Location: dashbord.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Élève - Taghazout Surf Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/sty.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashbord.php">
                <i class="fas fa-water me-2"></i>Taghazout Surf Expo
            </a>
            <div class="d-flex align-items-center">
                <a href="dashbord.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i>Dashboard
                </a>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Modifier l'Élève</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="../../controllers/admincontroller.php?action=edit_student" method="POST">
                            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-id-card me-1"></i>Nom complet *</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= htmlspecialchars($student['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-globe me-1"></i>Pays</label>
                                <input type="text" name="country" class="form-control" 
                                       value="<?= htmlspecialchars($student['country'] ?? '') ?>">
                            </div>
                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-chart-line me-1"></i>Niveau *</label>
                                <select name="level" class="form-select">
                                    <option value="Beginner" <?= $student['level'] === 'Beginner' ? 'selected' : '' ?>>Débutant</option>
                                    <option value="Intermediate" <?= $student['level'] === 'Intermediate' ? 'selected' : '' ?>>Intermédiaire</option>
                                    <option value="Advanced" <?= $student['level'] === 'Advanced' ? 'selected' : '' ?>>Avancé</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-save me-1"></i>Enregistrer
                                </button>
                                <a href="dashbord.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>