<?php



session_start();

$est_connecte = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberAware - Apprendre la Cybersécurité</title>
    
    <link rel="stylesheet" href="style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        
        <nav class="navbar">
            <div class="nav-content">
                <a href="index.php" class="logo">
                    <i class="fas fa-shield-alt"></i> <span>CyberAware</span>
                </a>
                <div class="nav-menu">
                    <a href="index.php" class="nav-link active">Accueil</a>
                    
                    <?php if ($est_connecte): ?>
                        <a href="accueil.php" class="nav-link">Mon Espace</a>
                        <a href="deconnexion.php" class="btn btn-secondary" style="background:#f44336;">
                            <i class="fas fa-sign-out-alt"></i> Quitter
                        </a>
                    <?php else: ?>
                        <a href="auth.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <main class="main-content">
            
            <section style="text-align: center; padding: 4rem 2rem; background: rgba(0,212,255,0.05); border-radius: 15px; margin-bottom: 3rem;">
                <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">
                    Bienvenue sur <span style="color:var(--primary-color);">CyberAware</span>
                </h1>
                <p style="font-size: 1.2rem; opacity: 0.8; max-width: 800px; margin: 0 auto 2rem;">
                    La plateforme interactive pour apprendre à détecter les pièges du web et protéger vos données personnelles.
                </p>
                
                <?php if ($est_connecte): ?>
                    <a href="accueil.php" class="btn btn-primary" style="padding:1rem 2rem; font-size:1.1rem;">
                        <i class="fas fa-tachometer-alt"></i> Accéder à ma formation
                    </a>
                <?php else: ?>
                    <a href="auth.php" class="btn btn-primary" style="padding:1rem 2rem; font-size:1.1rem;">
                        <i class="fas fa-rocket"></i> Commencer maintenant
                    </a>
                <?php endif; ?>
            </section>

            <h2 class="section-title">
                <i class="fas fa-bug"></i> Menaces courantes sur Internet
            </h2>
            
            <div class="attack-grid">
                <article class="attack-card">
                    <i class="fas fa-envelope-open-text" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>1. Phishing (Hameçonnage)</h3>
                    <p>Des emails qui imitent votre banque ou un service connu pour voler vos mots de passe.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-file-medical-alt" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>2. Ransomware (Rançongiciel)</h3>
                    <p>Un virus qui bloque vos fichiers et vous demande de payer pour les récupérer.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-user-secret" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>3. Ingénierie Sociale</h3>
                    <p>L'art de manipuler les gens pour qu'ils donnent des informations secrètes par erreur.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-wifi" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>4. Man-in-the-Middle</h3>
                    <p>Un pirate qui s'interpose entre vous et un site web (souvent sur un Wi-Fi public).</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-network-wired" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>5. Attaque DDoS</h3>
                    <p>Envoyer tellement de trafic sur un site qu'il finit par "planter" et devenir inaccessible.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-code" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>6. Injection SQL</h3>
                    <p>Ajouter du code malveillant dans un formulaire pour pirater une base de données.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-key" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>7. Brute Force</h3>
                    <p>Essayer des milliers de mots de passe automatiquement jusqu'à trouver le bon.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-ghost" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>8. Spyware (Logicial espion)</h3>
                    <p>Un programme caché qui surveille ce que vous faites et filme parfois votre écran.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-link" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>9. Adware (Publicités)</h3>
                    <p>Des logiciels qui installent des tonnes de pubs intrusives sur votre ordinateur.</p>
                </article>

                <article class="attack-card">
                    <i class="fas fa-microchip" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>10. Cheval de Troie</h3>
                    <p>Un logiciel malveillant déguisé en programme utile (ex: un faux jeu gratuit).</p>
                </article>
            </div>
            
        </main>

        <footer class="footer">
            <p>&copy; 2024 CyberAware - Guide éducatif pour débutants</p>
        </footer>
        
    </div>
</body>
</html>
