<div class="auth-page">
    <div class="auth-container">
        <h1>Connexion</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <p class="auth-link">
            Pas encore de compte? <a href="index.php?page=auth&action=register">S'inscrire</a>
        </p>

        <div class="test-credentials">
            <h3>Identifiants de test:</h3>
            <p>Email: <code>test@example.com</code></p>
            <p>Mot de passe: <code>password123</code></p>
        </div>
    </div>
</div>
