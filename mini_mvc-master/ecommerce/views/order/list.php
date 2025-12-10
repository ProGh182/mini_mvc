<div class="order-list-page">
    <h1>Mes Commandes</h1>

    <?php if (empty($orders)): ?>
        <p>Vous n'avez pas encore de commandes</p>
        <a href="index.php" class="btn btn-primary">Commencer vos achats</a>
    <?php else: ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</td>
                        <td>
                            <span class="status status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?page=order&action=show&id=<?php echo $order['id']; ?>" 
                               class="btn btn-secondary btn-small">
                                Détails
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="index.php?page=auth&action=profile" class="btn btn-secondary">Retour au profil</a>
</div>
