<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../model/student.php';

$studentModel = new Student();
$student = $studentModel->findByUserId($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Niveau - Taghazout Surf Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/sty.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="agenda.php">
                <i class="fas fa-water me-2"></i>Taghazout Surf Expo
            </a>
            <div class="d-flex align-items-center">
                <a href="agenda.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-calendar-alt me-1"></i>Mon Agenda
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

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Mon Niveau de Surf</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($student): ?>
                            <div class="text-center mb-4">
                                <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                                <h4><?= htmlspecialchars($student['name']) ?></h4>
                                <p class="text-muted"><i class="fas fa-globe me-1"></i><?= htmlspecialchars($student['country'] ?? 'Non renseigné') ?></p>
                                
                                <?php
                                $badgeClass = match($student['level']) {
                                    'Beginner' => 'bg-success',
                                    'Intermediate' => 'bg-warning text-dark',
                                    'Advanced' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                $levelLabel = match($student['level']) {
                                    'Beginner' => 'Débutant',
                                    'Intermediate' => 'Intermédiaire',
                                    'Advanced' => 'Avancé',
                                    default => $student['level']
                                };
                                ?>
                                <h5>Niveau actuel : <span class="badge <?= $badgeClass ?> fs-6"><?= $levelLabel ?></span></h5>
                            </div>

                            <!-- Level Progress -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-success fw-bold">Débutant</span>
                                    <span class="text-warning fw-bold">Intermédiaire</span>
                                    <span class="text-danger fw-bold">Avancé</span>
                                </div>
                                <?php
                                $progress = match($student['level']) {
                                    'Beginner' => 33,
                                    'Intermediate' => 66,
                                    'Advanced' => 100,
                                    default => 0
                                };
                                $progressColor = match($student['level']) {
                                    'Beginner' => 'bg-success',
                                    'Intermediate' => 'bg-warning',
                                    'Advanced' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar <?= $progressColor ?>" role="progressbar" 
                                         style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?= $progress ?>%
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-1"></i>
                                Votre niveau est évalué par les coachs après chaque session. 
                                Contactez l'école si vous pensez que votre niveau devrait être mis à jour.
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-muted">Profil élève non trouvé.</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>