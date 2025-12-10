<?php
/**
 * Contrôleur OrderController - Gestion des commandes
 */

class OrderController
{
    private $orderModel;
    private $cartModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
    }

    /**
     * Afficher la liste des commandes de l'utilisateur
     */
    public function list()
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getByUser($userId);

        return $this->render('order/list', [
            'orders' => $orders
        ]);
    }

    /**
     * Afficher le détail d'une commande
     */
    public function show()
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $orderId = (int) ($_GET['id'] ?? 0);
        $userId = $_SESSION['user_id'];

        $order = $this->orderModel->getById($orderId);

        // Vérifier que l'utilisateur est propriétaire de la commande
        if (!$order || $order['user_id'] != $userId) {
            http_response_code(404);
            return $this->render('errors/404');
        }

        $items = $this->orderModel->getItems($orderId);

        return $this->render('order/show', [
            'order' => $order,
            'items' => $items
        ]);
    }

    /**
     * Traiter la validation du panier et créer une commande
     */
    public function checkout()
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=cart');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartTotal = $this->cartModel->getTotal($userId);

        if ($cartTotal <= 0) {
            header('Location: index.php?page=cart&error=empty_cart');
            exit;
        }

        try {
            $orderId = $this->orderModel->createFromCart($userId, $cartTotal);
            header('Location: index.php?page=order&action=show&id=' . $orderId . '&success=1');
            exit;
        } catch (Exception $e) {
            header('Location: index.php?page=cart&error=checkout_failed');
            exit;
        }
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    private function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Rendre une vue
     */
    protected function render(string $view, array $data = [])
    {
        extract($data);
        $viewFile = VIEWS_PATH . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("Vue non trouvée: $viewFile");
        }

        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}
