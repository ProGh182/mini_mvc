<?php
require 'config/config.php';
require 'src/Database.php';

$db = Database::getInstance();

$updates = [
    1 => '/images/laptop.jpg',
    2 => '/images/iphone.jpg',
    3 => '/images/airpods.jpg',
    4 => '/images/tshirt.jpg',
    5 => '/images/jeans.jpg',
    6 => '/images/clean-code.jpg',
    7 => '/images/design-patterns.jpg',
    8 => '/images/lamp.jpg',
    9 => '/images/pillow.jpg',
    10 => '/images/dumbbells.jpg',
];

foreach ($updates as $product_id => $image_url) {
    $db->execute('UPDATE products SET image_url = ? WHERE id = ?', [$image_url, $product_id]);
    echo "✓ Product $product_id → $image_url\n";
}

echo "\n✓ All product image URLs updated successfully!\n";
?>
