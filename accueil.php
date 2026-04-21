<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

$id_user = $_SESSION['user_id'];


$sql_quiz_count = "SELECT COUNT(DISTINCT q.quiz_id) as total FROM user_responses ur JOIN questions q ON ur.question_id = q.id WHERE ur.user_id = $id_user";
$res_quiz = $conn->query($sql_quiz_count);
$nb_quiz_faits = $res_quiz->fetch_assoc()['total'];


$sql_moyenne = "SELECT AVG(is_correct) * 100 as moyenne FROM user_responses WHERE user_id = $id_user";
$res_moy = $conn->query($sql_moyenne);
$moyenne_quiz = round($res_moy->fetch_assoc()['moyenne'] ?? 0);


$sql_phish = "SELECT COUNT(*) as total FROM phishing_responses WHERE user_id = $id_user";
$nb_phishing_faits = $conn->query($sql_phish)->fetch_assoc()['total'];


$sql_moy_phish = "SELECT AVG(is_correct) * 100 as moyenne FROM phishing_responses WHERE user_id = $id_user";
$moyenne_phishing = round($conn->query($sql_moy_phish)->fetch_assoc()['moyenne'] ?? 0);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Formation - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo"><i class="fas fa-shield-alt"></i> CyberAware</a>
                <div class="nav-menu">
                    <a href="accueil.php" class="nav-link active">Tableau de bord</a>
                    <a href="deconnexion.php" style="background-color:#FF0000; color:white; padding:8px 12px; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:6px;"><i class="fas fa-power-off"></i>Déconnexion</a>
                </div>
            </div>
        </nav>

        <main class="main-content">
            
            <header style="margin-bottom: 2.5rem;">
                <h1 style="font-size: 2rem;">
                    Bienvenue, <span style="color:var(--primary-color); underline;"><?php echo htmlspecialchars($_SESSION['username']); ?></span> !
                </h1>
                <p style="opacity: 0.8;">Continuez votre apprentissage pour devenir un expert en cybersécurité.</p>
            </header>

            <div class="stats-grid" style="margin-bottom: 3rem;">
                <article class="stat-card">
                    <h3>Quiz Complétés</h3><p class="stat-value"><?php echo $nb_quiz_faits; ?></p>
                </article>

                <article class="stat-card">
                    <h3>Réussite Quiz</h3>
                    <p class="stat-value"><?php echo $moyenne_quiz; ?>%</p>
                </article>

                <article class="stat-card">
                    <h3>Tests Phishing</h3>
                    <p class="stat-value"><?php echo $nb_phishing_faits; ?></p>
                </article>

                <article class="stat-card">
                    <h3>Score Phishing</h3>
                    <p class="stat-value"><?php echo $moyenne_phishing; ?>%</p>
                </article>
            </div>

            <h2 class="section-title"><i class="fas fa-rocket"></i> Actions de formation</h2>

            <div class="dashboard-grid">
                
                <a href="quiz_liste.php" class="dashboard-card" style="color:white; text-decoration:none;">
                    <h3>Liste des Quiz</h3>
                    <p>Découvrez les différents thèmes et testez vos connaissances théoriques.</p>
                </a>

                <a href="phishing.php" class="dashboard-card" style="color:white; text-decoration:none;">
                    <h3>Détecter le Phishing</h3>
                    <p>Mettez-vous en situation réelle : saurez-vous repérer les emails piégés ?</p>
                </a>

                <a href="resultats_quiz.php" class="dashboard-card" style="color:white; text-decoration:none;">
                    <h3>Historique des Quiz</h3>
                    <p>Consultez vos résultats passés et les corrections pour progresser.</p>
                </a>

                <a href="resultats_phishing.php" class="dashboard-card" style="color:white; text-decoration:none;">
                    <h3>Scores Phishing</h3>
                    <p>Analysez vos réflexes face aux tentatives d'arnaques par email.</p>
                </a>

                <a href="tableau.php" class="dashboard-card" style="color:white; text-decoration:none;">
                    <h3>Statistiques Globales</h3>
                    <p>Visualisez votre progression globale à travers des graphiques simples.</p>
                </a>

            </div>
        </main>

        <footer class="footer">
            <p>&copy; 2024 - CyberAware : Votre sécurité numérique nous tient à cœur</p>
        </footer>
        
    </div>
</body>
</html>
