<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Taghazout Surf Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/sty.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
        <div class="card shadow-lg" style="width: 100%; max-width: 500px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-water fa-3x text-primary mb-2"></i>
                    <h2 class="fw-bold">Inscription Surfeur</h2>
                    <p class="text-muted">Créez votre profil pour rejoindre l'école</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="../../controllers/authcontroller.php?action=register" method="POST">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user me-1"></i>Nom d'utilisateur *</label>
                        <input type="text" name="username" class="form-control" placeholder="surfeur123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-id-card me-1"></i>Nom complet *</label>
                        <input type="text" name="name" class="form-control" placeholder="Votre nom complet" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope me-1"></i>Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock me-1"></i>Mot de passe *</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 caractères" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-globe me-1"></i>Pays</label>
                        <input type="text" name="country" class="form-control" placeholder="Maroc, France...">
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-chart-line me-1"></i>Niveau auto-évalué</label>
                        <select name="level" class="form-select">
                            <option value="Beginner">Débutant</option>
                            <option value="Intermediate">Intermédiaire</option>
                            <option value="Advanced">Avancé</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus me-1"></i>S'inscrire
                    </button>
                </form>
                <p class="text-center mb-0">
                    Déjà inscrit ? <a href="login.php" class="text-decoration-none">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>