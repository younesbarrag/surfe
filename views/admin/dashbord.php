<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../model/student.php';
require_once __DIR__ . '/../../model/lessons.php';
require_once __DIR__ . '/../../model/admin.php';

$studentModel = new Student();
$lessonModel = new Lesson();
$adminModel = new Admin();

$students = $studentModel->findAll();
$lessons = $lessonModel->findAll();
$totalStudents = $adminModel->getTotalStudents();
$totalLessons = $adminModel->getTotalLessons();
$upcomingLessons = $adminModel->getUpcomingLessons();
$pendingPayments = $adminModel->getPendingPayments();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Taghazout Surf Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/sty.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-water me-2"></i>Taghazout Surf Expo
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="fas fa-user-shield me-1"></i><?= htmlspecialchars($_SESSION['username']) ?>
                </span>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Tableau de Bord</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h3><?= $totalStudents ?></h3>
                        <p class="mb-0">Élèves</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar fa-2x mb-2"></i>
                        <h3><?= $totalLessons ?></h3>
                        <p class="mb-0">Sessions Totales</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h3><?= $upcomingLessons ?></h3>
                        <p class="mb-0">À Venir</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h3><?= $pendingPayments ?></h3>
                        <p class="mb-0">Paiements En Attente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Liste des Élèves</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Pays</th>
                                <th>Niveau</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr><td colspan="6" class="text-center text-muted">Aucun élève inscrit.</td></tr>
                            <?php else: ?>
                                <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><?= $s['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                                    <td><?= htmlspecialchars($s['email'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($s['country'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match($s['level']) {
                                            'Beginner' => 'bg-success',
                                            'Intermediate' => 'bg-warning text-dark',
                                            'Advanced' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        $levelLabel = match($s['level']) {
                                            'Beginner' => 'Débutant',
                                            'Intermediate' => 'Intermédiaire',
                                            'Advanced' => 'Avancé',
                                            default => $s['level']
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $levelLabel ?></span>
                                    </td>
                                    <td>
                                        <a href="editstud.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="../../controllers/admincontroller.php?action=delete_student&id=<?= $s['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Supprimer cet élève ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lessons Table -->
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Sessions de Surf</h5>
                <a href="create_lesson.php" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>Nouvelle Session
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Coach</th>
                                <th>Date & Heure</th>
                                <th>Inscrits</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lessons)): ?>
                                <tr><td colspan="6" class="text-center text-muted">Aucune session programmée.</td></tr>
                            <?php else: ?>
                                <?php foreach ($lessons as $l): 
                                    $enrolled = $lessonModel->getEnrolledStudents($l['id']);
                                ?>
                                <tr>
                                    <td><?= $l['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($l['title']) ?></strong></td>
                                    <td><?= htmlspecialchars($l['coach']) ?></td>
                                    <td>
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($l['lesson_date'])) ?>
                                    </td>
                                    <td><span class="badge bg-info"><?= count($enrolled) ?> élève(s)</span></td>
                                    <td>
                                        <a href="create_lesson.php?lesson_id=<?= $l['id'] ?>" class="btn btn-sm btn-outline-info" title="Gérer inscriptions">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                        <a href="../../controllers/admincontroller.php?action=delete_lesson&id=<?= $l['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Supprimer cette session ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
