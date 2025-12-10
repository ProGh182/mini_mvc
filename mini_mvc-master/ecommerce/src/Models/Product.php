<?php
/**
 * Modèle Product - Gestion des produits
 */

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtenir tous les produits
     */
    public function getAll(array $filters = [])
    {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        // Filtre par catégorie
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        // Filtre par prix minimum
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }

        // Filtre par prix maximum
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }

        // Recherche par nom
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY p.created_at DESC";

        return $this->db->query($sql, $params);
    }

    /**
     * Obtenir un produit par ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    /**
     * Obtenir les produits d'une catégorie
     */
    public function getByCategory(int $categoryId)
    {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ? ORDER BY p.name";
        return $this->db->query($sql, [$categoryId]);
    }

    /**
     * Obtenir les produits populaires (top ventes)
     */
    public function getTopProducts(int $limit = 6)
    {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    /**
     * Créer un produit
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO products (category_id, name, description, price, stock, image_url) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['image_url'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Mettre à jour le stock d'un produit
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        $sql = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
        return $this->db->execute($sql, [$quantity, $productId, $quantity]) > 0;
    }

    /**
     * Vérifier si un produit a suffisamment de stock
     */
    public function hasStock(int $productId, int $quantity): bool
    {
        $product = $this->getById($productId);
        return $product && $product['stock'] >= $quantity;
    }
}
