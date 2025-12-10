<?php
/**
 * Point d'entrée principal de l'application
 */

require_once '../config/config.php';
require_once SRC_PATH . '/Database.php';
require_once SRC_PATH . '/Router.php';

// Charger les modèles
require_once SRC_PATH . '/Models/User.php';
require_once SRC_PATH . '/Models/Product.php';
require_once SRC_PATH . '/Models/Category.php';
require_once SRC_PATH . '/Models/Cart.php';
require_once SRC_PATH . '/Models/Order.php';

// Charger les contrôleurs
require_once SRC_PATH . '/Controllers/HomeController.php';
require_once SRC_PATH . '/Controllers/ProductController.php';
require_once SRC_PATH . '/Controllers/CartController.php';
require_once SRC_PATH . '/Controllers/AuthController.php';
require_once SRC_PATH . '/Controllers/OrderController.php';

// Routage
$router = new Router();
// Récupère le contenu rendu par le contrôleur
$content = $router->dispatch();

// Inclut le layout principal qui utilise la variable $content
require VIEWS_PATH . '/layout.php';
