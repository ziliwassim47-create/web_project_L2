<?php


session_start();

$conn = new mysqli("localhost", "root", "", "cyberaware_db");
$conn->set_charset("utf8");

$erreur = "";
$succes = "";


if (isset($_POST['connexion'])) {

$username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $resultat = $conn->query($sql);
    if ($resultat->num_rows > 0) {
        $user = $resultat->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        header("Location: accueil.php");
        exit;
    } else {
        $erreur = "Identifiants incorrects. Veuillez réessayer.";
    }
}

if (isset($_POST['inscription'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_conf = $_POST['password_conf'];

    if ($password != $password_conf) {
        $erreur = "Les deux mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 4) {
        $erreur = "Le mot de passe doit faire au moins 4 caractères.";
    } else {
        $sql_verif = "SELECT id FROM users WHERE username = '$username'";
        $verif = $conn->query($sql_verif);
        
        if ($verif->num_rows > 0) {
            $erreur = "Désolé, ce nom d'utilisateur est déjà pris.";
        } else {
            $sql_insert = "INSERT INTO users (username, email, password, score) VALUES ('$username', '$email', '$password', 0)";
            
            if ($conn->query($sql_insert)) {
                $succes = "Votre compte a été créé avec succès ! Connectez-vous maintenant.";
            } else {
                $erreur = "Erreur lors de la création du compte.";
            }
        }
    }
}

if (isset($_SESSION['user_id'])) {
    header("Location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription - CyberAware</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo"><i class="fas fa-shield-alt"></i> CyberAware</a>
            </div>
        </nav>

        <main class="main-content">
            
            <div class="auth-card">
                
                <?php $mode = isset($_GET['mode']) ? $_GET['mode'] : 'login'; ?>
                
                <div style="display:flex; gap:1.5rem; justify-content:center; margin-bottom:2rem;">
                    <a href="auth.php?mode=login" class="nav-link <?php echo ($mode == 'login' ? 'active' : ''); ?>" style="font-weight:bold;">
                        SE CONNECTER
                    </a>
                    <a href="auth.php?mode=register" class="nav-link <?php echo ($mode == 'register' ? 'active' : ''); ?>" style="font-weight:bold;">
                        S'INSCRIRE
                    </a>
                </div>

                <?php if($erreur): ?>
                    <div style="color:var(--error-color); background:var(--incorrect-bg); padding:10px; border-radius:5px; margin-bottom:1rem; border-left:4px solid var(--error-color);">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $erreur; ?>
                    </div>
                <?php endif; ?>

                <?php if($succes): ?>
                    <div style="color:var(--success-color); background:var(--correct-bg); padding:10px; border-radius:5px; margin-bottom:1rem; border-left:4px solid var(--success-color);">
                        <i class="fas fa-check-circle"></i> <?php echo $succes; ?>
                    </div>
                <?php endif; ?>

                <?php if ($mode == 'login'): ?>
                    <form method="POST">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nom d'utilisateur</label>
                            <input type="text" name="username" class="form-input" required placeholder="Tapez votre pseudo">
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Mot de passe</label>
                            <input type="password" name="password" class="form-input" required placeholder="Tapez votre mot de passe">
                        </div>
                        
                        <button type="submit" name="connexion" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:1rem;">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </form>
                <?php else: ?>
                    <form method="POST">
                        <div class="form-group">
                            <label>Nom d'utilisateur</label>
                            <input type="text" name="username" class="form-input" required placeholder="Ex: JeanDupont">
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-input" required placeholder="exemple@mail.com">
                        </div>
                        
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="password" class="form-input" required placeholder="4 caractères minimum">
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" name="password_conf" class="form-input" required>
                        </div>
                        
                        <button type="submit" name="inscription" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:1rem;">
                            <i class="fas fa-user-plus"></i> Créer mon compte
                        </button>
                    </form>
                <?php endif; ?>

            </div>

        </main>

        <footer class="footer">
            <p>&copy; 2024 - Session de sensibilisation</p>
        </footer>

    </div>
</body>
</html>
