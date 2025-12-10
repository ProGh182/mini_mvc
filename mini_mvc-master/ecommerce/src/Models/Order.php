<?php
/**
 * Modèle Order - Gestion des commandes
 */

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer une commande à partir du panier
     */
    public function createFromCart(int $userId, float $totalAmount): int
    {
        try {
            // Commencer une transaction
            $conn = $this->db->getConnection();
            $conn->beginTransaction();

            // Créer la commande
            $sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')";
            $this->db->execute($sql, [$userId, $totalAmount]);
            $orderId = (int) $this->db->lastInsertId();

            // Obtenir le panier
            $cartItems = $this->db->query(
                "SELECT c.*, p.price FROM carts c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?",
                [$userId]
            );

            // Créer les items de la commande et mettre à jour le stock
            foreach ($cartItems as $item) {
                $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $this->db->execute($itemSql, [$orderId, $item['product_id'], $item['quantity'], $item['price']]);

                // Mettre à jour le stock
                $updateSql = "UPDATE products SET stock = stock - ? WHERE id = ?";
                $this->db->execute($updateSql, [$item['quantity'], $item['product_id']]);
            }

            // Vider le panier
            $this->db->execute("DELETE FROM carts WHERE user_id = ?", [$userId]);

            // Valider la transaction
            $conn->commit();

            return $orderId;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    /**
     * Obtenir toutes les commandes d'un utilisateur
     */
    public function getByUser(int $userId)
    {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$userId]);
    }

    /**
     * Obtenir une commande par ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    /**
     * Obtenir les items d'une commande
     */
    public function getItems(int $orderId)
    {
        $sql = "SELECT oi.*, p.name FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        return $this->db->query($sql, [$orderId]);
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $allowedStatus = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $allowedStatus)) {
            return false;
        }

        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $orderId]) > 0;
    }

    /**
     * Obtenir les statistiques
     */
    public function getStats()
    {
        return [
            'total_orders' => $this->db->queryOne("SELECT COUNT(*) as count FROM orders")['count'] ?? 0,
            'total_revenue' => $this->db->queryOne("SELECT SUM(total_amount) as total FROM orders")['total'] ?? 0,
            'pending_orders' => $this->db->queryOne("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")['count'] ?? 0,
        ];
    }
}
