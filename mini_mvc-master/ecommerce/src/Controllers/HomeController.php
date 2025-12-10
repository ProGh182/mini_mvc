<?php
/**
 * Contrôleur HomeController - Page d'accueil et gestion générale
 */

class HomeController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Récupérer les filtres de la requête
        $filters = [];
        if (!empty($_GET['category'])) {
            $filters['category_id'] = (int) $_GET['category'];
        }
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (!empty($_GET['min_price'])) {
            $filters['min_price'] = (float) $_GET['min_price'];
        }
        if (!empty($_GET['max_price'])) {
            $filters['max_price'] = (float) $_GET['max_price'];
        }

        $products = $this->productModel->getAll($filters);
        $categories = $this->categoryModel->getAll();

        return $this->render('home/index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $_GET['category'] ?? null,
            'searchTerm' => $_GET['search'] ?? ''
        ]);
    }

    /**
     * Page À Propos
     */
    public function about()
    {
        return $this->render('home/about');
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
