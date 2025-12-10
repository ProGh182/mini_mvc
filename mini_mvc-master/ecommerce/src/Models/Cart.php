<?php
/**
 * Modèle Cart - Gestion du panier
 */

class Cart
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtenir le panier d'un utilisateur
     */
    public function getByUser(int $userId)
    {
        $sql = "SELECT c.*, p.name, p.price, p.stock FROM carts c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        return $this->db->query($sql, [$userId]);
    }

    /**
     * Ajouter un produit au panier
     */
    public function addProduct(int $userId, int $productId, int $quantity = 1): bool
    {
        // Vérifier si le produit existe déjà dans le panier
        $existing = $this->db->queryOne(
            "SELECT id FROM carts WHERE user_id = ? AND product_id = ?",
            [$userId, $productId]
        );

        if ($existing) {
            // Mettre à jour la quantité
            $sql = "UPDATE carts SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
            return $this->db->execute($sql, [$quantity, $userId, $productId]) > 0;
        } else {
            // Insérer un nouveau produit
            $sql = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
            return $this->db->execute($sql, [$userId, $productId, $quantity]) > 0;
        }
    }

    /**
     * Retirer un produit du panier
     */
    public function removeProduct(int $userId, int $productId): bool
    {
        $sql = "DELETE FROM carts WHERE user_id = ? AND product_id = ?";
        return $this->db->execute($sql, [$userId, $productId]) > 0;
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function updateQuantity(int $userId, int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeProduct($userId, $productId);
        }

        $sql = "UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?";
        return $this->db->execute($sql, [$quantity, $userId, $productId]) > 0;
    }

    /**
     * Vider le panier
     */
    public function clear(int $userId): bool
    {
        $sql = "DELETE FROM carts WHERE user_id = ?";
        return $this->db->execute($sql, [$userId]) > 0;
    }

    /**
     * Obtenir le total du panier
     */
    public function getTotal(int $userId): float
    {
        $result = $this->db->queryOne(
            "SELECT SUM(c.quantity * p.price) as total FROM carts c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = ?",
            [$userId]
        );

        return (float) ($result['total'] ?? 0);
    }

    /**
     * Obtenir le nombre de produits dans le panier
     */
    public function getItemCount(int $userId): int
    {
        $result = $this->db->queryOne(
            "SELECT COUNT(*) as count FROM carts WHERE user_id = ?",
            [$userId]
        );

        return (int) ($result['count'] ?? 0);
    }
}
