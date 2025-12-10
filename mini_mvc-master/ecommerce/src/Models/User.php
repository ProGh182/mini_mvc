<?php
/**
 * Modèle User - Gestion des utilisateurs
 */

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create(string $email, string $password, string $firstname, string $lastname): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (email, password, firstname, lastname) VALUES (?, ?, ?, ?)";
        $result = $this->db->execute($sql, [$email, $hashedPassword, $firstname, $lastname]);
        
        return $result > 0;
    }

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, [$email]);
    }

    /**
     * Trouver un utilisateur par ID
     */
    public function findById(int $id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    /**
     * Vérifier les identifiants et retourner l'utilisateur
     */
    public function login(string $email, string $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }

    /**
     * Mettre à jour les informations de l'utilisateur
     */
    public function update(int $id, array $data): bool
    {
        $allowedFields = ['firstname', 'lastname', 'phone', 'address', 'city', 'postal_code', 'country'];
        $updates = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        
        return $this->db->execute($sql, $params) > 0;
    }
}
