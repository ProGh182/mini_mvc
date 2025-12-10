<?php
/**
 * Modèle Category - Gestion des catégories
 */

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtenir toutes les catégories
     */
    public function getAll()
    {
        $sql = "SELECT * FROM categories ORDER BY name";
        return $this->db->query($sql);
    }

    /**
     * Obtenir une catégorie par ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    /**
     * Créer une catégorie
     */
    public function create(string $name, string $description = ''): int
    {
        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $this->db->execute($sql, [$name, $description]);
        return (int) $this->db->lastInsertId();
    }
}
