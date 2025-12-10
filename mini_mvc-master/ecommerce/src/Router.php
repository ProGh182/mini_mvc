<?php
/**
 * Classe Router - Routage simple des requêtes
 */

class Router
{
    private $page;
    private $action;

    public function __construct()
    {
        $this->page = $_GET['page'] ?? 'home';
        // Keep action null when not provided so controller-specific defaults apply
        $this->action = $_GET['action'] ?? null;
    }

    /**
     * Router la requête vers le bon contrôleur
     */
    public function dispatch(): string
    {
        $controller = null;
        $method = 'index';

        switch ($this->page) {
            case 'product':
                $controller = new ProductController();
                $method = 'show';
                break;
            case 'cart':
                $controller = new CartController();
                // default action for cart is 'view'
                $method = $this->action ?: 'view';
                break;
            case 'auth':
                $controller = new AuthController();
                // default action for auth is 'login'
                $method = $this->action ?: 'login';
                break;
            case 'order':
                $controller = new OrderController();
                // default action for orders is 'list'
                $method = $this->action ?: 'list';
                break;
            default:
                $controller = new HomeController();
                $method = 'index';
        }

        // If the requested method doesn't exist on the controller, return 404 view
        if (!method_exists($controller, $method)) {
            http_response_code(404);
            // Render a simple 404 view if available
            $viewFile = VIEWS_PATH . '/errors/404.php';
            if (file_exists($viewFile)) {
                return include $viewFile;
            }
            return '404 - Not Found';
        }

        return $controller->$method();
    }

    /**
     * Obtenir la page actuelle
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * Obtenir l'action actuelle
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
