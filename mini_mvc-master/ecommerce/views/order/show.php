<div class="order-detail-page">
    <h1>Détail de la Commande #<?php echo $order['id']; ?></h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Commande créée avec succès! Merci pour votre achat.
        </div>
    <?php endif; ?>

    <div class="order-info">
        <div class="order-header">
            <h2>Informations de la commande</h2>
            <p>Date: <strong><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></strong></p>
            <p>Statut: <span class="status status-<?php echo $order['status']; ?>">
                <?php echo ucfirst($order['status']); ?>
            </span></p>
        </div>

        <table class="order-items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="order-total">
            <h3>Total: <?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</h3>
        </div>

        <a href="index.php?page=order&action=list" class="btn btn-secondary">Retour aux commandes</a>
    </div>
</div>
