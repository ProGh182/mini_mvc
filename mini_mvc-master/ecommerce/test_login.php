<?php
require_once __DIR__ . '/config/config.php';
require_once SRC_PATH . '/Database.php';

$db = Database::getInstance();
$user = $db->queryOne('SELECT * FROM users WHERE email = ?', ['test@example.com']);
if (!$user) {
    echo "USER_NOT_FOUND\n";
    exit(0);
}

echo "USER_FOUND: id=" . $user['id'] . " email=" . $user['email'] . "\n";
echo "stored_password_hash=" . $user['password'] . "\n";
$ok = password_verify('password123', $user['password']);
echo "password_verify('password123') => " . ($ok ? 'TRUE' : 'FALSE') . "\n";
