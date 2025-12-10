<div class="cart-page">
    <h1>Mon Panier</h1>

    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <p>Votre panier est vide</p>
            <a href="index.php" class="btn btn-primary">Continuer les achats</a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr class="cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                            <td class="product-name">
                                <a href="index.php?page=product&id=<?php echo $item['product_id']; ?>">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </a>
                            </td>
                            <td class="unit-price"><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                            <td class="quantity">
                                <input type="number" value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="<?php echo $item['stock']; ?>" 
                                       class="qty-input">
                            </td>
                            <td class="subtotal">
                                <?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €
                            </td>
                            <td class="actions">
                                <button class="btn btn-danger btn-small remove-item" 
                                        data-product-id="<?php echo $item['product_id']; ?>">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="total">
                    <h2>Total: <span id="cart-total"><?php echo number_format($total, 2, ',', ' '); ?></span> €</h2>
                </div>

                <form method="POST" action="index.php?page=order&action=checkout" class="checkout-form">
                    <button type="submit" class="btn btn-primary btn-large">Procéder au paiement</button>
                    <a href="index.php" class="btn btn-secondary">Continuer les achats</a>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', async (e) => {
        const quantity = parseInt(e.target.value);
        const productId = e.target.closest('.cart-item').dataset.productId;
        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        
        try {
            const response = await fetch('index.php?page=cart&action=updateQuantity', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                document.getElementById('cart-total').textContent = 
                    (data.total).toFixed(2).replace('.', ',');
            }
        } catch (error) {
            alert('Erreur lors de la mise à jour');
        }
    });
});

document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const productId = btn.dataset.productId;
        
        const formData = new FormData();
        formData.append('product_id', productId);
        
        try {
            const response = await fetch('index.php?page=cart&action=remove', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                btn.closest('.cart-item').remove();
                document.getElementById('cart-total').textContent = 
                    (data.total).toFixed(2).replace('.', ',');
                
                if (document.querySelectorAll('.cart-item').length === 0) {
                    location.reload();
                }
            }
        } catch (error) {
            alert('Erreur lors de la suppression');
        }
    });
});
</script>
