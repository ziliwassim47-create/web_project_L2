<?php


session_start();


if (!isset($_SESSION['quiz_score'])) {
    header("Location: accueil.php");
    exit;
}

$score = $_SESSION['quiz_score'];
$total = $_SESSION['quiz_progress'];

$pourcent = ($total > 0) ? round(($score / $total) * 100) : 0;

unset($_SESSION['quiz_progress']);
unset($_SESSION['quiz_score']);
unset($_SESSION['current_quiz_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de votre Quiz - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <main class="main-content" style="display: flex; align-items: center; justify-content: center;">
            
            <article class="result-card" style="text-align: center;">
                
                <?php if ($pourcent >= 80): ?>
                    <i class="fas fa-trophy" style="font-size: 5rem; color: #ffca28; margin-bottom: 1rem;"></i>
                <?php else: ?>
                    <i class="fas fa-flag-checkered" style="font-size: 5rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                <?php endif; ?>

                <h1>Quiz Terminé !</h1>
                
                <div class="score-display">
                    <p style="font-size: 1.2rem; opacity: 0.8;">Votre score final est de :</p>
                    <div style="font-size: 4rem; font-weight: bold; color: var(--primary-color);">
                        <?php echo $score; ?><span style="font-size: 2rem; color: grey;"> / <?php echo $total; ?></span>
                    </div>
                </div>

                <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                    <p style="font-size: 1.1rem; font-style: italic;">
                        <?php 
                        if ($pourcent >= 80) {
                            echo "Bravo ! Vous avez une excellente maîtrise de ce sujet.";
                        } elseif ($pourcent >= 50) {
                            echo "C'est un bon début ! Relisez les corrections pour atteindre 100%.";
                        } else {
                            echo "N'abandonnez pas ! Prenez le temps de revoir les bases de la cybersécurité.";
                        }
                        ?>
                    </p>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
                    <a href="accueil.php" class="btn btn-primary">
                        <i class="fas fa-home"></i> Retour à l'accueil
                    </a>
                    <a href="quiz_liste.php" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Faire un autre quiz
                    </a>
                </div>

            </article>

        </main>

    </div>
</body>
</html>
