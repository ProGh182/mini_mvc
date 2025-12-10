<div class="product-detail">
    <div class="product-header">
        <a href="index.php" class="back-link">← Retour aux produits</a>
    </div>

    <div class="product-container">
            <div class="product-image-large">
            <?php
                $imgPath = $product['image_url'] ?? '/images/default.svg';
                $fullImg = ROOT_PATH . '/public' . $imgPath;
                if (!file_exists($fullImg)) {
                    $imgPath = '/images/default.svg';
                }
            ?>
            <img src="<?php echo htmlspecialchars($imgPath); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="category">Catégorie: <strong><?php echo htmlspecialchars($product['category_name']); ?></strong></p>
            
            <div class="product-rating">
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>

            <div class="product-pricing">
                <h2 class="price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</h2>
                
                <div class="stock-status">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="in-stock">Stock disponible: <?php echo $product['stock']; ?> unités</span>
                    <?php else: ?>
                        <span class="out-of-stock">Rupture de stock</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($product['stock'] > 0): ?>
                <form method="POST" action="index.php?page=cart&action=add" class="add-to-cart-form" id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="quantity-selector">
                        <label for="quantity">Quantité:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" 
                               max="<?php echo $product['stock']; ?>">
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button type="submit" class="btn btn-primary btn-large">Ajouter au panier</button>
                    <?php else: ?>
                        <a href="index.php?page=auth&action=login" class="btn btn-primary btn-large">
                            Connexion pour acheter
                        </a>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
<?php if (isset($_SESSION['user_id'])): ?>
    document.getElementById('addToCartForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('index.php?page=cart&action=add', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                document.getElementById('quantity').value = '1';
            } else {
                alert(data.message || 'Erreur');
            }
        } catch (error) {
            alert('Erreur lors de l\'ajout au panier');
        }
    });
<?php endif; ?>
</script>
