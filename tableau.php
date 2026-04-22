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

$conn->query("UPDATE users SET score = $score_global WHERE id = $id_utilisateur");

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
            background: conic-gradient(var(--primary-color) <?php echo $score_global; ?>%, var(--border-color) 0);
            position: relative;
        }
        .score-circle-big::before {
            content: ''; position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px;
            background: var(--card-bg); border-radius: 50%;
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
                <a href="index.php" class="logo"><input type="image" src="logoblanc.png" width="250px" height="120px"></a>
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
