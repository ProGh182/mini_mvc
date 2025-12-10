<?php
/**
 * Contrôleur ProductController - Gestion des produits
 */

class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * Afficher le détail d'un produit
     */
    public function show()
    {
        if (empty($_GET['id'])) {
            header('Location: index.php');
            exit;
        }

        $productId = (int) $_GET['id'];
        $product = $this->productModel->getById($productId);

        if (!$product) {
            http_response_code(404);
            return $this->render('errors/404');
        }

        return $this->render('product/show', [
            'product' => $product
        ]);
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
