<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../model/student.php';
require_once __DIR__ . '/../../model/lessons.php';

$studentModel = new Student();
$lessonModel = new Lesson();

$students = $studentModel->findAll();
$lessons = $lessonModel->findAll();

// If managing a specific lesson
$selectedLesson = null;
$enrolledStudents = [];
if (isset($_GET['lesson_id'])) {
    $lessonId = intval($_GET['lesson_id']);
    $selectedLesson = $lessonModel->findById($lessonId);
    if ($selectedLesson) {
        $enrolledStudents = $lessonModel->getEnrolledStudents($lessonId);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Sessions - Taghazout Surf Expo</title>
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

        <div class="row">
            <!-- Create Lesson Form -->
            <div class="col-md-5 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Créer une Session</h5>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/admincontroller.php?action=create_lesson" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Titre de la session *</label>
                                <input type="text" name="title" class="form-control" placeholder="Ex: Morning Surf" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Coach *</label>
                                <input type="text" name="coach" class="form-control" placeholder="Nom du coach" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date & Heure *</label>
                                <input type="datetime-local" name="lesson_date" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-save me-1"></i>Créer la Session
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Enroll Student to a Lesson -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Inscrire un Élève</h5>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/admincontroller.php?action=enroll_student" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Session *</label>
                                <select name="lesson_id" class="form-select" required>
                                    <option value="">-- Choisir une session --</option>
                                    <?php foreach ($lessons as $l): ?>
                                        <option value="<?= $l['id'] ?>" <?= ($selectedLesson && $selectedLesson['id'] == $l['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($l['title']) ?> - <?= date('d/m/Y H:i', strtotime($l['lesson_date'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Élève *</label>
                                <select name="student_id" class="form-select" required>
                                    <option value="">-- Choisir un élève --</option>
                                    <?php foreach ($students as $s): ?>
                                        <option value="<?= $s['id'] ?>">
                                            <?= htmlspecialchars($s['name']) ?> (<?= $s['level'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Statut Paiement</label>
                                <select name="payment_status" class="form-select">
                                    <option value="en attente">En attente</option>
                                    <option value="payé">Payé</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info w-100 text-white">
                                <i class="fas fa-check me-1"></i>Inscrire
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enrolled Students for Selected Lesson -->
            <div class="col-md-7 mb-4">
                <?php if ($selectedLesson): ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Inscrits : <?= htmlspecialchars($selectedLesson['title']) ?>
                            <small class="d-block mt-1">Coach: <?= htmlspecialchars($selectedLesson['coach']) ?> | <?= date('d/m/Y H:i', strtotime($selectedLesson['lesson_date'])) ?></small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($enrolledStudents)): ?>
                            <p class="text-muted text-center">Aucun élève inscrit à cette session.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nom</th>
                                            <th>Niveau</th>
                                            <th>Paiement</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($enrolledStudents as $es): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($es['name']) ?></strong></td>
                                            <td>
                                                <?php
                                                $badgeClass = match($es['level']) {
                                                    'Beginner' => 'bg-success',
                                                    'Intermediate' => 'bg-warning text-dark',
                                                    'Advanced' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $es['level'] ?></span>
                                            </td>
                                            <td>
                                                <?php if ($es['payment_status'] === 'payé'): ?>
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Payé</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>En attente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="../../controllers/rollcontroller.php?action=toggle_payment&id=<?= $es['enrollment_id'] ?>&lesson_id=<?= $selectedLesson['id'] ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Basculer paiement">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </a>
                                                <a href="../../controllers/admincontroller.php?action=remove_student_lesson&lesson_id=<?= $selectedLesson['id'] ?>&student_id=<?= $es['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   onclick="return confirm('Retirer cet élève ?')" title="Retirer">
                                                    <i class="fas fa-user-minus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-hand-pointer fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sélectionnez une session depuis le Dashboard pour voir les inscrits</h5>
                        <a href="dashbord.php" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-left me-1"></i>Retour au Dashboard
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
