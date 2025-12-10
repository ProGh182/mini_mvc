<?php
/**
 * Script pour télécharger les images de produits en local
 */

$baseDir = __DIR__ . '/public/images/';

// Créer le dossier s'il n'existe pas
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
}

$images = [
    'laptop.jpg' => 'https://m.media-amazon.com/images/I/71H-iRxYZQL.jpg',
    'iphone.jpg' => 'https://m.media-amazon.com/images/I/61HHS0HrjpL._AC_UF1000,1000_QL80_.jpg',
    'airpods.jpg' => 'https://store.storeimages.cdn-apple.com/1/as-images.apple.com/is/airpods-pro-3-hero-select-202509_FMT_WHH?wid=752&hei=636&fmt=jpeg&qlt=90&.v=1758077264181',
    'tshirt.jpg' => 'https://www.ateliertuffery.com/cdn/shop/files/t-shirt-homme-100-coton-bio-blanc-1.jpg?v=1694598857/',
    'jeans.jpg' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQINGDj_aI3PYBIpQlajdGkz_Emw90D62GfJA&s',
    'clean-code.jpg' => 'https://m.media-amazon.com/images/I/71nj3JM-igL.jpg',
    'design-patterns.jpg' => 'https://static.fnac-static.com/multimedia/FR/Images_Produits/FR/fnac.com/Visual_Principal_340/5/7/8/9782746038875/tsp20120923064609/Design-patterns.jpg',
    'lamp.jpg' => 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTwlsJCwULVVyAFb2i-cf83-cFgIVYW5GWpPUZwS8JJzd4t5-ACqhCPQsZJVeY1ytCOaGn9bZh03offANBbVc35lPenyREGCVI9KyHAeYKSExMW8hh5QiRH8uger7PE0gkBuvrS8i8&usqp=CAc',
    'pillow.jpg' => 'https://www.ikea.com/nl/nl/images/products/rosenskarm-ergonomisch-kussen-zij-rugslaper__0722542_pe733642_s5.jpg?f=s',
    'dumbbells.jpg' => 'https://media.intersport.fr/is/image/intersportfr/12060__73C_Q2?$product_grey$&layer=comp&fit=constrain,0&$produit_xl$&fmt=webp'
];

$downloaded = 0;
$failed = 0;

foreach ($images as $filename => $url) {
    $filepath = $baseDir . $filename;
    
    // Éviter de re-télécharger si le fichier existe
    if (file_exists($filepath)) {
        echo "✓ $filename (déjà présent)\n";
        continue;
    }
    
    $imageData = @file_get_contents($url);
    if ($imageData === false) {
        echo "✗ $filename (échec du téléchargement)\n";
        $failed++;
        continue;
    }
    
    file_put_contents($filepath, $imageData);
    echo "✓ $filename (téléchargé)\n";
    $downloaded++;
}

echo "\n$downloaded téléchargées, $failed échouées\n";
