<?php


$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$conn->query("DELETE FROM user_responses");
$conn->query("DELETE FROM phishing_responses");
$conn->query("DELETE FROM questions");
$conn->query("DELETE FROM quizzes");
$conn->query("DELETE FROM phishing_scenarios");
$conn->query("DELETE FROM users");



$conn->query("ALTER TABLE quizzes AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE questions AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE phishing_scenarios AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE users AUTO_INCREMENT = 1");



$conn->query("INSERT INTO quizzes (id, title, theme) VALUES (1, 'Les Bases de la Cybersécurité', 'Général')");
$conn->query("INSERT INTO quizzes (id, title, theme) VALUES (2, 'Malwares et Ransomwares', 'Menaces')");
$conn->query("INSERT INTO quizzes (id, title, theme) VALUES (3, 'Ingénierie Sociale', 'Social')");
$conn->query("INSERT INTO quizzes (id, title, theme) VALUES (4, 'Sécurité Mobile et Cloud', 'Technologie')");
$conn->query("INSERT INTO quizzes (id, title, theme) VALUES (5, 'Gestion des Mots de Passe', 'Pratiques')");

$conn->query("INSERT INTO questions (quiz_id, question_text, answer1, answer2, answer3, answer4, correct_answer) VALUES
(1, 'Qu\'est-ce qu\'un mot de passe fort ?', 'Un mot de passe court', 'Majuscules, minuscules, chiffres et caractères spéciaux', 'Le même pour tous les comptes', 'Basé sur votre date de naissance', 2),
(1, 'Que signifie phishing ?', 'Une technique de pêche', 'Vol d\'informations via emails frauduleux', 'Un type de virus', 'Un protocole réseau', 2),
(1, 'Qu\'est-ce qu\'un pare-feu ?', 'Un logiciel antivirus', 'Un système qui contrôle le trafic réseau', 'Un type de malware', 'Un protocole de communication', 2)");

$conn->query("INSERT INTO questions (quiz_id, question_text, answer1, answer2, answer3, answer4, correct_answer) VALUES (2, 'Qu\'est-ce qu\'un Ransomware ?', 'Un antivirus gratuit', 'Un malware qui bloque vos fichiers contre rançon', 'Un logiciel de sauvegarde', 'Un outil de nettoyage', 2)");



$conn->query("INSERT INTO phishing_scenarios (id, title, message, is_phishing, explanation) VALUES
(1, 'Email de votre banque', 'De: securite@banque-france-secure.com\nObjet: Action urgente requise\n\nCher client,\nVeuillez cliquer sur le lien pour vérifier votre identité : http://banque-france-secure.com/verify', 1, 'PHISHING: L\'adresse email est suspecte et demande une action urgente.'),
(2, 'Newsletter Google Workspace', 'De: newsletter@google.com\nObjet: Nouveautés - Mars 2024\n\nDécouvrez nos nouvelles fonctions sur workspace.google.com', 0, 'LÉGITIME: Vient du domaine officiel google.com et ne demande aucune donnée sensible.')");



$conn->query("INSERT INTO users (username, email, password, score) VALUES 
('etudiant1', 'etudiant1@test.com', 'password123', 0),
('etudiant2', 'etudiant2@test.com', 'password456', 0)");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Init DB - CyberAware</title>
</head>
<body style="background:#0a0e27; color:white; font-family:sans-serif; text-align:center; padding-top:100px;">
    <h1 style="color:#00d4ff;"> Base de données initialisée </h1>
    <p>Toutes les tables ont été vidées et remplies avec des données de test.</p>
    <br>
    <a href="index.php" style="color:#00d4ff;">Retourner à l'accueil</a>
</body>
</html>
