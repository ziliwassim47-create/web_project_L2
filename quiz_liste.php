<?php


session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}


$conn = new mysqli("localhost", "root", "", "cyberaware_db");

$conn->set_charset("utf8");


$sql = "SELECT q.id, q.title, q.theme, COUNT(qn.id) as total_questions FROM quizzes q LEFT JOIN questions qn ON q.id = qn.quiz_id GROUP BY q.id";

$resultats = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Quiz de Cybersécurité - CyberAware</title>
    
    <link rel="stylesheet" href="style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo">
                    <i class="fas fa-shield-alt"></i> CyberAware
                </a>
                <div class="nav-menu">
                    <a href="accueil.php" class="nav-link">Tableau de bord</a>
                    <a href="quiz_liste.php" class="nav-link active">Liste des Quiz</a>
                </div>
            </div>
        </nav>

        <main class="main-content">
            
            <header style="margin-bottom: 2rem;">
                <h2 class="section-title">
                    <i class="fas fa-graduation-cap"></i> Choisissez votre défi
                </h2>
                <p style="opacity: 0.8;">Testez vos connaissances en cybersécurité grâce à nos quiz théoriques.</p>
            </header>

            <div class="quiz-grid">
                <?php 
                while ($quiz = $resultats->fetch_assoc()): 
                ?>
                    <article class="quiz-card">
                        <div class="quiz-theme-label">
                            <?php echo htmlspecialchars($quiz['theme']); ?>
                        </div>
                        
                        <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>
                        
                        <p>
                            <i class="fas fa-question-circle"></i> 
                            <?php echo $quiz['total_questions']; ?> questions à résoudre.
                        </p>
                        
                        <a href="quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-secondary" style="margin-top: auto;">
                            <i class="fas fa-play"></i> Commencer le quiz
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center; margin-top:3rem;">
                <a href="accueil.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                </a>
            </div>
        </main>

        <footer class="footer">
            <p>&copy; 2024 - CyberAware : Plateforme pédagogique</p>
        </footer>
        
    </div>
</body>
</html>
