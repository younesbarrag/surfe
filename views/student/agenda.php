<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../model/student.php';
require_once __DIR__ . '/../../model/lessons.php';

$studentModel = new Student();
$lessonModel = new Lesson();

$student = $studentModel->findByUserId($_SESSION['user_id']);

if (!$student) {
    die("Erreur : Profil étudiant introuvable. Veuillez contacter l'administrateur pour lier votre compte à l'ID utilisateur " . $_SESSION['user_id']);
}

$lessons = $lessonModel->getLessonsByStudent($student['id']);

$lvl = $student['level'] ?? 'Débutant';
$badgeClass = match($lvl) {
    'Beginner', 'Débutant' => 'bg-success',
    'Intermediate', 'Intermédiaire' => 'bg-warning text-dark',
    'Advanced', 'Avancé' => 'bg-danger',
    default => 'bg-secondary'
};

$label = match($lvl) {
    'Beginner', 'Débutant' => 'Débutant',
    'Intermediate', 'Intermédiaire' => 'Intermédiaire',
    'Advanced', 'Avancé' => 'Avancé',
    default => 'Non défini'
};
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Agenda - Taghazout Surf Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/sty.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-water me-2"></i>Taghazout Surf Expo
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 d-none d-md-inline">
                    <i class="fas fa-user-circle me-1"></i><?= htmlspecialchars($student['name']) ?>
                </span>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="fw-bold text-primary">Bonjour, <?= htmlspecialchars($student['name']) ?> !</h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($student['country'] ?? 'Taghazout') ?> | 
                            <span class="badge <?= $badgeClass ?>"><?= $label ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check text-primary me-2"></i>Mes Sessions de Surf</h5>
            </div>
            <div class="card-body">
                <?php if (empty($lessons)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-light mb-3"></i>
                        <h5 class="text-muted">Aucun cours trouvé.</h5>
                        <p class="small text-muted">Inscrivez-vous à une session auprès de l'accueil.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Cours</th>
                                    <th>Coach</th>
                                    <th>Date</th>
                                    <th>Paiement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lessons as $l): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($l['title']) ?></td>
                                    <td><?= htmlspecialchars($l['coach']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($l['lesson_date'])) ?></td>
                                    <td>
                                        <?php if (($l['payment_status'] ?? '') === 'payé'): ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Payé</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>