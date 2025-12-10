<div class="profile-page">
    <h1>Mon Profil</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="profile-form">
        <div class="form-row">
            <div class="form-group">
                <label for="firstname">Prénom:</label>
                <input type="text" id="firstname" name="firstname" 
                       value="<?php echo htmlspecialchars($user['firstname'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="lastname">Nom:</label>
                <input type="text" id="lastname" name="lastname" 
                       value="<?php echo htmlspecialchars($user['lastname'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>

        <div class="form-group">
            <label for="phone">Téléphone:</label>
            <input type="tel" id="phone" name="phone" 
                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="address">Adresse:</label>
            <input type="text" id="address" name="address" 
                   value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="city">Ville:</label>
                <input type="text" id="city" name="city" 
                       value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="postal_code">Code Postal:</label>
                <input type="text" id="postal_code" name="postal_code" 
                       value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="country">Pays:</label>
            <input type="text" id="country" name="country" 
                   value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
        <a href="index.php?page=order&action=list" class="btn btn-secondary">Mes commandes</a>
        <a href="index.php?page=auth&action=logout" class="btn btn-danger">Déconnexion</a>
    </form>
</div>
