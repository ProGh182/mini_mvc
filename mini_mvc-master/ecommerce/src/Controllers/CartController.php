<?php
/**
 * Contrôleur CartController - Gestion du panier
 */

class CartController
{
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    /**
     * Afficher le panier
     */
    public function view()
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getByUser($userId);
        $total = $this->cartModel->getTotal($userId);

        return $this->render('cart/view', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Ajouter un produit au panier
     */
    public function add()
    {
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Non authentifié']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);
        $userId = $_SESSION['user_id'];

        // Vérifier que le produit existe et a du stock
        if (!$this->productModel->hasStock($productId, $quantity)) {
            echo json_encode(['success' => false, 'message' => 'Stock insuffisant']);
            exit;
        }

        $success = $this->cartModel->addProduct($userId, $productId, $quantity);

        echo json_encode([
            'success' => $success,
            'itemCount' => $this->cartModel->getItemCount($userId),
            'message' => $success ? 'Produit ajouté au panier' : 'Erreur'
        ]);
        exit;
    }

    /**
     * Retirer un produit du panier
     */
    public function remove()
    {
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit;
        }

        $productId = (int) $_POST['product_id'];
        $userId = $_SESSION['user_id'];

        $success = $this->cartModel->removeProduct($userId, $productId);
        $total = $this->cartModel->getTotal($userId);

        echo json_encode([
            'success' => $success,
            'total' => $total
        ]);
        exit;
    }

    /**
     * Mettre à jour la quantité
     */
    public function updateQuantity()
    {
        if (!$this->isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            exit;
        }

        $productId = (int) $_POST['product_id'];
        $quantity = (int) $_POST['quantity'];
        $userId = $_SESSION['user_id'];

        $success = $this->cartModel->updateQuantity($userId, $productId, $quantity);
        $total = $this->cartModel->getTotal($userId);

        echo json_encode([
            'success' => $success,
            'total' => $total
        ]);
        exit;
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
