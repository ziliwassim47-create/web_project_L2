<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");
$id_utilisateur = $_SESSION['user_id'];


$sql_p = "SELECT COUNT(*) as total, SUM(is_correct) as corrects FROM phishing_responses WHERE user_id = $id_utilisateur";
$res_p = $conn->query($sql_p)->fetch_assoc();
$score_phishing = ($res_p['total'] > 0) ? round(($res_p['corrects'] / $res_p['total']) * 100) : 0;

$sql_q = "SELECT COUNT(*) as total, SUM(is_correct) as corrects FROM user_responses WHERE user_id = $id_utilisateur";
$res_q = $conn->query($sql_q)->fetch_assoc();
$score_quiz = ($res_q['total'] > 0) ? round(($res_q['corrects'] / $res_q['total']) * 100) : 0;

$score_global = round(($score_phishing + $score_quiz) / 2);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Bilan - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .score-circle-big {
            width: 220px; height: 220px; border-radius: 50%;
            margin: 2rem auto; display: flex; align-items: center; justify-content: center;
            border: 8px solid var(--border-color);
            position: relative;
        }
        .score-circle-big::after {
            content: ''; position: absolute; top: -8px; left: -8px; right: -8px; bottom: -8px;
            border-radius: 50%; border: 8px solid var(--primary-color);
            clip-path: polygon(50% 50%, 50% 0, <?php echo (50 + 50 * sin(deg2rad($score_global * 3.6))); ?>% <?php echo (50 - 50 * cos(deg2rad($score_global * 3.6))); ?>%, 50% 50%);
            /* Note : clip-path est un peu complexe, c'est pour dessiner le contour du score */
        }
        .score-num { font-size: 4rem; font-weight: bold; color: var(--primary-color); z-index: 10; }
        
        .progress-box { background: var(--card-bg); padding: 2rem; border-radius: 10px; border: 1px solid var(--border-color); margin-top: 2rem; }
        .bar-container { width: 100%; height: 15px; background: #000; border-radius: 10px; margin-top: 10px; overflow: hidden; }
        .bar-inner { height: 100%; background: var(--primary-color); border-radius: 10px; transition: width 1s; }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo"><i class="fas fa-shield-alt"></i> CyberAware</a>
                <div class="nav-menu">
                    <a href="accueil.php" class="nav-link">Dashboard</a>
                    <a href="tableau.php" class="nav-link active">Bilan</a>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <h2 class="section-title" style="text-align:center;"><i class="fas fa-chart-line"></i> Votre Niveau de Maîtrise</h2>

            <div class="score-box" style="text-align:center;">
                <div class="score-circle-big">
                    <div class="score-num"><?php echo $score_global; ?>%</div>
                </div>
                <p>C'est votre score de protection actuel.</p>
            </div>

            <div class="progress-box">
                <h3 style="color:var(--primary-color); margin-bottom:1.5rem;">Détail par catégorie</h3>

                <div style="margin-bottom:2rem;">
                    <div style="display:flex; justify-content:space-between;">
                        <span>Théorie (Quiz)</span>
                        <strong><?php echo $score_quiz; ?>%</strong>
                    </div>
                    <div class="bar-container">
                        <div class="bar-inner" style="width:<?php echo $score_quiz; ?>%;"></div>
                    </div>
                </div>

                <div>
                    <div style="display:flex; justify-content:space-between;">
                        <span>Pratique (Phishing)</span>
                        <strong><?php echo $score_phishing; ?>%</strong>
                    </div>
                    <div class="bar-container">
                        <div class="bar-inner" style="width:<?php echo $score_phishing; ?>%; background:var(--secondary-color);"></div>
                    </div>
                </div>
            </div>

            <div style="text-align:center; margin-top:3rem;">
                <a href="accueil.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Retour au Dashboard</a>
            </div>
        </main>
    </div>
</body>
</html>
