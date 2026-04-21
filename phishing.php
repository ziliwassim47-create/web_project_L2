<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");
$id_utilisateur = $_SESSION['user_id'];

$message_resultat = "";
$couleur_resultat = "";


if (isset($_POST['choix_utilisateur'])) {
    $id_scenario = intval($_POST['id_scenario']);
    $choix = intval($_POST['choix_utilisateur']);

    $sql = "SELECT is_phishing, explanation FROM phishing_scenarios WHERE id = $id_scenario";
    $vrai_scenario = $conn->query($sql)->fetch_assoc();

    $correct = ($choix == $vrai_scenario['is_phishing']) ? 1 : 0;
    
    $sql_save = "INSERT INTO phishing_responses (user_id, scenario_id, user_answer, is_correct) VALUES ($id_utilisateur, $id_scenario, $choix, $correct)";
    $conn->query($sql_save);

    if ($correct == 1) {
        $message_resultat = "<strong>Bravo !</strong> C'est la bonne réponse.<br><br>" . $vrai_scenario['explanation'];
        $couleur_resultat = "correct";
    } else {
        $message_resultat = "<strong>Dommage !</strong> Vous vous êtes fait piéger.<br><br>" . $vrai_scenario['explanation'];
        $couleur_resultat = "incorrect";
    }
}


$res_faits = $conn->query("SELECT scenario_id FROM phishing_responses WHERE user_id = $id_utilisateur");
$ids_deja_faits = [];
while ($row = $res_faits->fetch_assoc()) {
    $ids_deja_faits[] = $row['scenario_id'];
}

$tous_scenarios = $conn->query("SELECT * FROM phishing_scenarios");
$scenario_actuel = null;

while ($s = $tous_scenarios->fetch_assoc()) {
    if (!in_array($s['id'], $ids_deja_faits)) {
        $scenario_actuel = $s;
        break;
    }
}

$total_scenarios = $tous_scenarios->num_rows;
$nombre_faits = count($ids_deja_faits);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice Phishing - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <nav class="navbar">
            <div class="nav-content">
                <a href="accueil.php" class="logo"><input type="image" src="logoblanc.png" width="250px" height="120px"></a>
                <div style="opacity: 0.8; font-size: 0.9rem;">
                    Scénario <?php echo $nombre_faits + ($scenario_actuel ? 1 : 0); ?> / <?php echo $total_scenarios; ?>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <div class="phishing-container">
                
                <h2 class="section-title"><i class="fas fa-envelope-open-text"></i> Détection de Phishing</h2>
                
                <?php if ($message_resultat): ?>
                    <div class="resultat-box <?php echo $couleur_resultat; ?>">
                        <p><?php echo $message_resultat; ?></p>
                        <hr style="margin: 1rem 0; opacity: 0.2;">
                        <a href="phishing.php" class="btn btn-secondary" style="font-size: 0.8rem;">Continuer</a>
                    </div>
                <?php endif; ?>

                <?php if ($scenario_actuel && !$message_resultat): ?>
                    <p style="margin-bottom: 1.5rem;">Examinez attentivement le message ci-dessous et donnez votre verdict :</p>
                    
                    <article class="email-card">
                        <?php
                        $lignes = explode("\n", $scenario_actuel['message']);
                        $expediteur = str_replace("De: ", "", $lignes[0] ?? "Inconnu");
                        $sujet = str_replace("Objet: ", "", $lignes[1] ?? "Pas d'objet");
                        $corps = implode("\n", array_slice($lignes, 2));
                        ?>
                        <header class="email-header">
                            <div><strong>Expéditeur :</strong> <?php echo htmlspecialchars($expediteur); ?></div>
                            <div><strong>Sujet :</strong> <?php echo htmlspecialchars($sujet); ?></div>
                        </header>
                        <div class="email-body">
                            <?php echo nl2br(htmlspecialchars($corps)); ?>
                        </div>
                    </article>

                    <form method="POST" class="phishing-buttons">
                        <input type="hidden" name="id_scenario" value="<?php echo $scenario_actuel['id']; ?>">
                        
                        <button type="submit" name="choix_utilisateur" value="1" class="btn-phishing">
                            <i class="fas fa-skull-crossbones"></i> C'est un PHISHING
                        </button>
                        
                        <button type="submit" name="choix_utilisateur" value="0" class="btn-legitime">
                            <i class="fas fa-check-circle"></i> C'est LÉGITIME
                        </button>
                    </form>

                <?php elseif (!$scenario_actuel && !$message_resultat): ?>
                    <div class="result-card" style="text-align: center;">
                        <i class="fas fa-medal" style="font-size: 4rem; color: gold; margin-bottom: 1rem;"></i>
                        <h2>Félicitations !</h2>
                        <p>Vous avez terminé tous les scénarios de simulation disponibles.</p>
                        <br>
                        <a href="accueil.php" class="btn btn-primary">Retour au Dashboard</a>
                    </div>
                <?php endif; ?>

            </div>
        </main>

        <footer class="footer">
            <p>&copy; 2024 - Atelier de sécurité numérique</p>
        </footer>
        
    </div>
</body>
</html>
