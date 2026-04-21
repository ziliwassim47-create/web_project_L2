<?php


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");
$id_user = $_SESSION['user_id'];

$sql = "SELECT q.title, DATE(ur.created_at) as date_t, COUNT(ur.id) as total, SUM(ur.is_correct) as bonnes
        FROM user_responses ur
        JOIN questions qn ON ur.question_id = qn.id
        JOIN quizzes q ON qn.quiz_id = q.id
        WHERE ur.user_id = $id_user
        GROUP BY q.id, date_t
        ORDER BY date_t DESC";

$resultats = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Quiz - CyberAware</title>
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
            <h2 class="section-title"><i class="fas fa-history"></i> Historique de vos Quiz</h2>

            <?php if ($resultats->num_rows > 0): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Date</th>
                            <th>Bonnes Réponses</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultats->fetch_assoc()): 
                            $pourcent = round(($row['bonnes'] / $row['total']) * 100);
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                <td><?php echo $row['date_t']; ?></td>
                                <td><?php echo $row['bonnes']; ?> / <?php echo $row['total']; ?></td>
                                <td>
                                    <span class="badge <?php echo ($pourcent >= 60 ? 'badge-success' : 'badge-danger'); ?>">
                                        <?php echo $pourcent; ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">Vous n'avez pas encore de résultats.</div>
            <?php endif; ?>

            <div style="text-align:center; margin-top:2rem;">
                <a href="accueil.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au Dashboard</a>
            </div>
        </main>
    </div>
</body>
</html>
