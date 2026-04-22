<?php
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: auth.php");
    exit;
}

if (!isset($_GET['quiz_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

$quiz_id = (int)$_GET['quiz_id'];
$quiz_result = $conn->query("SELECT * FROM quizzes WHERE id = $quiz_id");
$quiz = $quiz_result->fetch_assoc();

if (!$quiz) {
    header("Location: admin_dashboard.php");
    exit;
}

$questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Questions - <?= htmlspecialchars($quiz['title']) ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-section { background: var(--card-bg); padding: 2rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.1); }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 1rem; color: white; }
        .admin-table th, .admin-table td { padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: left; }
        .admin-table th { color: var(--primary-color); }
        .btn-danger { background-color: var(--error-color); color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .btn-danger:hover { background-color: #ff3333; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: var(--secondary-color); text-decoration: none; }
        .btn-back:hover { color: white; text-decoration: underline; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-group-full { grid-column: 1 / -1; }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo"><img src="logoblanc.png" alt="Logo" width="250" height="120"></a>
            </div>
        </nav>

        <main class="main-content">
            <a href="admin_dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
            
            <h1><i class="fas fa-question-circle"></i> Questions de : <?= htmlspecialchars($quiz['title']) ?></h1>

            <?php if (isset($_GET['success'])): ?>
                <div style="color:var(--success-color); background:var(--correct-bg); padding:10px; border-radius:5px; margin-bottom:1rem;">
                    <i class="fas fa-check-circle"></i> Action réussie avec succès.
                </div>
            <?php endif; ?>

            <div class="admin-section">
                <h2>Nouvelle Question</h2>
                <form action="admin_actions.php" method="POST">
                    <input type="hidden" name="action" value="add_question">
                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                    
                    <div class="form-group-full" style="margin-bottom:15px;">
                        <label>Texte de la question</label>
                        <input type="text" name="question_text" class="form-input" required>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label>Réponse 1</label>
                            <input type="text" name="answer1" class="form-input" required>
                        </div>
                        <div>
                            <label>Réponse 2</label>
                            <input type="text" name="answer2" class="form-input" required>
                        </div>
                        <div>
                            <label>Réponse 3 (Optionnel)</label>
                            <input type="text" name="answer3" class="form-input">
                        </div>
                        <div>
                            <label>Réponse 4 (Optionnel)</label>
                            <input type="text" name="answer4" class="form-input">
                        </div>
                    </div>

                    <div style="margin-top:15px;">
                        <label>Bonne réponse (Numéro : 1, 2, 3 ou 4)</label>
                        <select name="correct_answer" class="form-input" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;"><i class="fas fa-save"></i> Ajouter la question</button>
                </form>
            </div>

            <div class="admin-section">
                <h2>Liste des Questions</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Bonne Rép.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($q = $questions->fetch_assoc()): ?>
                        <tr>
                            <td><?= $q['id'] ?></td>
                            <td><?= htmlspecialchars($q['question_text']) ?></td>
                            <td>Rép <?= $q['correct_answer'] ?></td>
                            <td>
                                <form action="admin_actions.php" method="POST" onsubmit="return confirm('Supprimer cette question ?');">
                                    <input type="hidden" name="action" value="delete_question">
                                    <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                    <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
