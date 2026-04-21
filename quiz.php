<?php

session_start();

if (!isset($_SESSION['user_id'])) { 
    header("Location: auth.php"); 
    exit; 
}
$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

if (!isset($_GET['id'])) { 
    header("Location: quiz_liste.php"); 
    exit; 
}
$quiz_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$quiz_info = $conn->query("SELECT title FROM quizzes WHERE id = $quiz_id")->fetch_assoc();

$sql_questions = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
$res_questions = $conn->query($sql_questions);
$total_questions = $res_questions->num_rows;

$liste_questions = [];
while ($row = $res_questions->fetch_assoc()) {
    $liste_questions[] = $row;
}

if (!isset($_SESSION['current_quiz_id']) || $_SESSION['current_quiz_id'] != $quiz_id) {
    $_SESSION['quiz_progress'] = 0;
    $_SESSION['current_quiz_id'] = $quiz_id;
    $_SESSION['quiz_score'] = 0;
}

if (isset($_POST['reponse'])) {
    $index_actuel = $_SESSION['quiz_progress'];
    $reponse_choisie = intval($_POST['reponse']);
    $bonne_reponse = intval($liste_questions[$index_actuel]['correct_answer']);
    $question_id = $liste_questions[$index_actuel]['id'];

    $est_correct = ($reponse_choisie == $bonne_reponse) ? 1 : 0;
    
    $sql_res = "INSERT INTO user_responses (user_id, question_id, is_correct) VALUES ($user_id, $question_id, $est_correct)";
    $conn->query($sql_res);

    if ($est_correct) { 
        $_SESSION['quiz_score']++; 
    }

    $_SESSION['quiz_progress']++;

    if ($_SESSION['quiz_progress'] >= $total_questions) {
        header("Location: resultat.php");
        exit;
    }
}

$index = $_SESSION['quiz_progress'];
$question_actuelle = $liste_questions[$index];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz en cours - <?php echo htmlspecialchars($quiz_info['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <nav class="navbar">
            <div class="nav-content">
                <a href="accueil.php" class="logo"><i class="fas fa-shield-alt"></i> CyberAware</a>
                <div style="font-weight: bold; color: var(--primary-color);">
                    Question <?php echo ($index + 1); ?> / <?php echo $total_questions; ?>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <div class="quiz-container">
                
                <h2 style="margin-bottom: 1.5rem; text-align: center;"><?php echo htmlspecialchars($quiz_info['title']); ?></h2>
                
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: <?php echo (($index + 1) / $total_questions * 100); ?>%;"></div>
                </div>

                <section class="question-card">
                    <h3 class="question-text"><?php echo htmlspecialchars($question_actuelle['question_text']); ?></h3>
                    
                    <form method="POST" class="options-list">
                        <button type="submit" name="reponse" value="1" class="option-btn">
                            <span class="option-letter">A</span> <?php echo htmlspecialchars($question_actuelle['answer1']); ?>
                        </button>
                        
                        <button type="submit" name="reponse" value="2" class="option-btn">
                            <span class="option-letter">B</span> <?php echo htmlspecialchars($question_actuelle['answer2']); ?>
                        </button>
                        
                        <button type="submit" name="reponse" value="3" class="option-btn">
                            <span class="option-letter">C</span> <?php echo htmlspecialchars($question_actuelle['answer3']); ?>
                        </button>
                        
                        <button type="submit" name="reponse" value="4" class="option-btn">
                            <span class="option-letter">D</span> <?php echo htmlspecialchars($question_actuelle['answer4']); ?>
                        </button>
                    </form>
                </section>

                <div style="text-align:center; margin-top:2rem;">
                    <a href="accueil.php" style="color: grey; text-decoration: none;" onclick="return confirm('Vraiment abandonner ? Votre progression sera perdue.')">
                        <i class="fas fa-times"></i> Abandonner le quiz
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>
</html>
