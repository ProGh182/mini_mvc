<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <a href="index.php"><?php echo APP_NAME; ?></a>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="index.php?page=cart">Panier</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php?page=order&action=list">Mes Commandes</a></li>
                        <li><a href="index.php?page=auth&action=profile">Profil</a></li>
                        <li><a href="index.php?page=auth&action=logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=auth&action=login">Connexion</a></li>
                        <li><a href="index.php?page=auth&action=register">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <?php if (!empty($content)): ?>
                <?php echo $content; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 <?php echo APP_NAME; ?>. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
