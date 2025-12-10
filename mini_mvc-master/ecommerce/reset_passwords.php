<?php
require_once __DIR__ . '/config/config.php';
require_once SRC_PATH . '/Database.php';

$db = Database::getInstance();
$hash = password_hash('password123', PASSWORD_BCRYPT);

$updated = $db->execute('UPDATE users SET password = ? WHERE email IN (?, ?)', [$hash, 'test@example.com', 'admin@example.com']);

echo "Updated rows: " . $updated . "\n";
echo "New hash: $hash\n";
