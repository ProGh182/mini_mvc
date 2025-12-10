-- Création de la base de données e-commerce
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    city VARCHAR(100),
    postal_code VARCHAR(10),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des paniers
CREATE TABLE IF NOT EXISTS carts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'paid', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des détails de commandes
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insertion des catégories
INSERT INTO categories (name, description) VALUES
('Électronique', 'Produits électroniques et informatiques'),
('Vêtements', 'Vêtements et accessoires'),
('Livres', 'Livres et e-books'),
('Maison', 'Articles pour la maison'),
('Sports', 'Articles de sport et loisirs');

-- Insertion des produits
INSERT INTO products (category_id, name, description, price, stock, image_url) VALUES
(1, 'Laptop Dell XPS 13', 'Ordinateur portable haute performance', 1299.99, 5, '/images/laptop.jpg'),
(1, 'iPhone 14 Pro', 'Smartphone dernier modèle', 999.99, 10, '/images/iphone.jpg'),
(1, 'AirPods Pro', 'Écouteurs sans fil premium', 249.99, 20, '/images/airpods.jpg'),
(2, 'T-Shirt Coton', 'T-shirt confortable et durable', 29.99, 50, '/images/tshirt.jpg'),
(2, 'Jeans Bleu', 'Jeans classique bleu indigo', 79.99, 30, '/images/jeans.jpg'),
(3, 'Livre: Clean Code', 'Guide complet du code propre', 49.99, 15, '/images/clean-code.jpg'),
(3, 'Livre: Design Patterns', 'Patterns de conception en Java', 54.99, 12, '/images/design-patterns.jpg'),
(4, 'Lampe LED', 'Lampe LED économe en énergie', 34.99, 25, '/images/lamp.jpg'),
(4, 'Oreiller Ergonomique', 'Oreiller pour un meilleur sommeil', 44.99, 20, '/images/pillow.jpg'),
(5, 'Haltères 20kg', 'Set d\'haltères réglables', 89.99, 8, '/images/dumbbells.jpg');

-- Insertion d'utilisateurs de test
INSERT INTO users (email, password, firstname, lastname, phone, address, city, postal_code, country) VALUES
('admin@example.com', '$2y$10$vxYHPlSwmaqzGZOXOBDkE.YTBGF.y1hI7AKrmKwlJxMJPUMwGVECi', 'Admin', 'User', '0123456789', '123 Rue Admin', 'Paris', '75000', 'France'),
('test@example.com', '$2y$10$vxYHPlSwmaqzGZOXOBDkE.YTBGF.y1hI7AKrmKwlJxMJPUMwGVECi', 'Jean', 'Dupont', '0987654321', '456 Rue Client', 'Lyon', '69000', 'France');
-- Les mots de passe hashés correspondent à "password123"
