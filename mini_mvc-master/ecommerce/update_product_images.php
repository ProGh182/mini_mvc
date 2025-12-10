<?php
require_once __DIR__ . '/config/config.php';
require_once SRC_PATH . '/Database.php';

$db = Database::getInstance();

// Tableau des mises à jour: nom du produit => URL de l'image
$updates = [
    'Laptop Dell XPS 13' => 'https://m.media-amazon.com/images/I/71H-iRxYZQL.jpg',
    'iPhone 14 Pro' => 'https://m.media-amazon.com/images/I/61HHS0HrjpL._AC_UF1000,1000_QL80_.jpg',
    'AirPods Pro' => 'https://store.storeimages.cdn-apple.com/1/as-images.apple.com/is/airpods-pro-3-hero-select-202509_FMT_WHH?wid=752&hei=636&fmt=jpeg&qlt=90&.v=1758077264181',
    'T-Shirt Coton' => 'https://www.ateliertuffery.com/cdn/shop/files/t-shirt-homme-100-coton-bio-blanc-1.jpg?v=1694598857/',
    'Jeans Bleu' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQINGDj_aI3PYBIpQlajdGkz_Emw90D62GfJA&s',
    'Livre: Clean Code' => 'https://m.media-amazon.com/images/I/71nj3JM-igL.jpg',
    'Livre: Design Patterns' => 'https://static.fnac-static.com/multimedia/FR/Images_Produits/FR/fnac.com/Visual_Principal_340/5/7/8/9782746038875/tsp20120923064609/Design-patterns.jpg',
    'Lampe LED' => 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTwlsJCwULVVyAFb2i-cf83-cFgIVYW5GWpPUZwS8JJzd4t5-ACqhCPQsZJVeY1ytCOaGn9bZh03offANBbVc35lPenyREGCVI9KyHAeYKSExMW8hh5QiRH8uger7PE0gkBuvrS8i8&usqp=CAc',
    'Oreiller Ergonomique' => 'https://www.ikea.com/nl/nl/images/products/rosenskarm-ergonomisch-kussen-zij-rugslaper__0722542_pe733642_s5.jpg?f=s',
    'Haltères 20kg' => 'https://media.intersport.fr/is/image/intersportfr/12060__73C_Q2?$product_grey$&layer=comp&fit=constrain,0&$produit_xl$&fmt=webp'
];

$count = 0;
foreach ($updates as $productName => $imageUrl) {
    $result = $db->execute(
        'UPDATE products SET image_url = ? WHERE name = ?',
        [$imageUrl, $productName]
    );
    if ($result > 0) {
        echo "✓ $productName\n";
        $count++;
    } else {
        echo "✗ $productName (NOT FOUND)\n";
    }
}

echo "\n$count produits mis à jour avec succès!\n";
