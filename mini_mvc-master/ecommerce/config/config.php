<?php
/**
 * Configuration de l'application e-commerce
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce');
define('DB_PORT', 3306);

// Configuration de l'application
define('APP_NAME', 'E-Commerce Shop');
define('APP_DEBUG', true);
define('APP_URL', 'http://localhost:8000');

// Chemins de l'application
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('SRC_PATH', ROOT_PATH . '/src');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Configuration de session
define('SESSION_LIFETIME', 3600); // 1 heure
session_start();
