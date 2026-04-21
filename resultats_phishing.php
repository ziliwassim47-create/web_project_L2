<?php


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");
$id_user = $_SESSION['user_id'];

$sql = "SELECT ps.title, pr.is_correct, pr.user_answer, pr.created_at
        FROM phishing_responses pr
        JOIN phishing_scenarios ps ON pr.scenario_id = ps.id
        WHERE pr.user_id = $id_user
        ORDER BY pr.created_at DESC";

$resultats = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Phishing - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo"><i class="fas fa-shield-alt"></i> CyberAware</a>
                <div class="nav-menu">
                    <a href="accueil.php" class="nav-link">Dashboard</a>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <h2 class="section-title"><i class="fas fa-list-check"></i> Vos Tests Phishing</h2>

            <?php if ($resultats->num_rows > 0): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Nom du Test</th>
                            <th>Votre Choix</th>
                            <th>Résultat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultats->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo ($row['user_answer'] == 1 ? "Phishing" : "Légitime"); ?></td>
                                <td>
                                    <?php if ($row['is_correct']): ?>
                                        <span class="badge badge-success">Correct</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Erreur</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Démarrer une simulation pour voir vos résultats.</div>
            <?php endif; ?>

            <div style="text-align:center; margin-top:2rem;">
                <a href="accueil.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au Dashboard</a>
            </div>
        </main>
    </div>
</body>
</html>
