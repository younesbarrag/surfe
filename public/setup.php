<?php
require_once __DIR__ . '/../config/database.php';

echo "<h2>Taghazout Surf Expo - Setup</h2>";

try {
    $pdo = Database::getInstance();
    
    // Test connection
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();
    echo "<p style='color:green;'>✅ Connexion à la base de données réussie !</p>";
    echo "<p>Nombre d'utilisateurs : " . $result['total'] . "</p>";

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM students");
    $result = $stmt->fetch();
    echo "<p>Nombre d'élèves : " . $result['total'] . "</p>";

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM lessons");
    $result = $stmt->fetch();
    echo "<p>Nombre de sessions : " . $result['total'] . "</p>";

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM lesson_student");
    $result = $stmt->fetch();
    echo "<p>Nombre d'inscriptions : " . $result['total'] . "</p>";

    echo "<hr>";
    echo "<h3>Comptes de test :</h3>";
    echo "<p><strong>Admin :</strong> admin@test.com / admin123</p>";
    echo "<p><strong>Élève 1 :</strong> student1@test.com / student123</p>";
    echo "<p><strong>Élève 2 :</strong> student2@test.com / student123</p>";
    echo "<hr>";
    echo "<a href='../views/auth/login.php'>→ Aller à la page de connexion</a>";

} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<p>Assurez-vous d'avoir exécuté le fichier <strong>surfedb.sql</strong> dans phpMyAdmin.</p>";
}
?>
