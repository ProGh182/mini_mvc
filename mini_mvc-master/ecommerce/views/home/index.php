<div class="home-page">
    <section class="hero">
        <h1>Bienvenue dans notre boutique</h1>
        <p>Découvrez nos produits de qualité à prix imbattables</p>
    </section>

    <section class="filters">
        <form method="GET" class="filter-form">
            <input type="hidden" name="page" value="home">
            
            <input type="text" name="search" placeholder="Rechercher un produit..." 
                   value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input">
            
            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                            <?php echo ($currentCategory == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>
    </section>

    <section class="products">
        <h2>Nos Produits</h2>
        
        <?php if (empty($products)): ?>
            <p class="no-products">Aucun produit trouvé</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php
                                // Résolution sûre du chemin d'image : si le fichier n'existe pas, utiliser le placeholder
                                $imgPath = $product['image_url'] ?? '/images/default.svg';
                                $fullImg = ROOT_PATH . '/public' . $imgPath;
                                if (!file_exists($fullImg)) {
                                    $imgPath = '/images/default.svg';
                                }
                            ?>
                            <img src="<?php echo htmlspecialchars($imgPath); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                            <p class="description"><?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...</p>
                            <div class="product-footer">
                                <span class="price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                                <div class="stock">
                                    <?php if ($product['stock'] > 0): ?>
                                        <span class="in-stock">Stock: <?php echo $product['stock']; ?></span>
                                    <?php else: ?>
                                        <span class="out-of-stock">Rupture de stock</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="index.php?page=product&id=<?php echo $product['id']; ?>" class="btn btn-secondary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
