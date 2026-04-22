<?php
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

$users = $conn->query("SELECT * FROM users");
$quizzes = $conn->query("SELECT * FROM quizzes");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-section { background: var(--card-bg); padding: 2rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.1); }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 1rem; color: white; }
        .admin-table th, .admin-table td { padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: left; }
        .admin-table th { color: var(--primary-color); }
        .btn-danger { background-color: var(--error-color); color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .btn-danger:hover { background-color: #ff3333; }
        .btn-action { background-color: var(--secondary-color); color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; font-size: 14px; }
        .btn-action:hover { background-color: #00b3cc; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout-btn { background-color: rgba(255,255,255,0.1); color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; transition: 0.3s; }
        .logout-btn:hover { background-color: rgba(255,255,255,0.2); }
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
            <div class="admin-header">
                <h1><i class="fas fa-user-shield"></i> Tableau de bord Administrateur</h1>
                <a href="deconnexion.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div style="color:var(--success-color); background:var(--correct-bg); padding:10px; border-radius:5px; margin-bottom:1rem;">
                    <i class="fas fa-check-circle"></i> Action réussie avec succès.
                </div>
            <?php endif; ?>

            <div class="admin-section">
                <h2><i class="fas fa-users"></i> Gestion des Utilisateurs</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= $u['score'] ?></td>
                            <td>
                                <form action="admin_actions.php" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-section">
                <h2><i class="fas fa-list-alt"></i> Gestion des Listes de Quiz</h2>
                
                <form action="admin_actions.php" method="POST" style="margin-bottom: 20px; display:flex; gap:10px;">
                    <input type="hidden" name="action" value="add_quiz">
                    <input type="text" name="title" class="form-input" placeholder="Titre du quiz" required style="width: auto;">
                    <input type="text" name="theme" class="form-input" placeholder="Thème" required style="width: auto;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter</button>
                </form>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Thème</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($q = $quizzes->fetch_assoc()): ?>
                        <tr>
                            <td><?= $q['id'] ?></td>
                            <td><?= htmlspecialchars($q['title']) ?></td>
                            <td><?= htmlspecialchars($q['theme']) ?></td>
                            <td>
                                <a href="admin_questions.php?quiz_id=<?= $q['id'] ?>" class="btn-action"><i class="fas fa-cog"></i> Gérer les questions</a>
                                <form action="admin_actions.php" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce quiz et TOUTES ses questions ?');">
                                    <input type="hidden" name="action" value="delete_quiz">
                                    <input type="hidden" name="quiz_id" value="<?= $q['id'] ?>">
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
