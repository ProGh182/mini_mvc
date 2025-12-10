<?php
require_once __DIR__ . '/config/config.php';
require_once SRC_PATH . '/Database.php';

$db = Database::getInstance();
$products = $db->query('SELECT id, name, image_url FROM products LIMIT 3');

foreach ($products as $p) {
    echo $p['id'] . ' | ' . $p['name'] . ' | ' . $p['image_url'] . "\n";
}
