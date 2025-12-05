
INSERT INTO categories (nom, description) VALUES
('Informatique', 'Ordinateurs, composants et périphériques'),
('Maison', 'Articles pour la maison et décoration'),
('Sport', 'Équipements et vêtements de sport'),
('Beauté', 'Produits de beauté et soins'),
('Livres', 'Livres papier et ebooks');



INSERT INTO produits (nom, description, prix, stock, id_categorie) VALUES
('Ordinateur portable 14', 'Portable 14 pouces, 8GB RAM, 256GB SSD', 699.00, 10, 1),
('Souris sans fil', 'Souris ergonomique', 19.99, 150, 1),
('Clavier mécanique', 'Clavier rétroéclairé', 89.90, 40, 1),
('Lampe de bureau', 'Lampe LED avec intensité réglable', 29.50, 60, 2),
('Coussin déco', 'Coussin polyester 45x45 cm', 15.00, 120, 2),
('Tapis yoga', 'Tapis antidérapant 6 mm', 25.00, 80, 3),
('Baskets running', 'Chaussures course, pointure 42', 79.99, 25, 3),
('Montre fitness', 'Suivi cardio et pas', 49.99, 35, 3),
('Crème hydratante', 'Pot 50ml', 12.50, 200, 4),
('Shampoing doux', 'Flacon 300ml', 6.90, 180, 4),
('Roman policier', 'Thriller captivant', 9.99, 50, 5),
('Guide voyage France', '100 lieux à visiter', 14.90, 30, 5),
('SSD 1TB', 'Disque SSD NVMe 1 To', 129.90, 15, 1),
('Carte mère ATX', 'Socket AM4', 109.00, 8, 1),
('Tasse céramique', 'Tasse 300ml', 7.50, 90, 2),
('Veste coupe-vent', 'Veste légère sport', 59.90, 22, 3),
('Palette maquillage', '12 couleurs', 22.00, 45, 4),
('Lampe de chevet', 'Lampe tactile', 34.99, 27, 2),
('Guide programmation PHP', 'Apprendre PHP moderne', 24.90, 20, 5),
('Écouteurs Bluetooth', 'Sans fil, réduction bruit', 39.99, 60, 1),
('Housse canapé', 'Housse élastique 3 places', 45.00, 12, 2),
('Gourde inox', 'Bouteille 750ml', 19.00, 75, 3),
('Soin visage nuit', 'Sérum 30ml', 29.90, 32, 4),
('Roman science-fiction', 'Space opera', 11.50, 40, 5),
('Clé USB 64GB', 'USB 3.0', 12.99, 100, 1);



INSERT INTO clients (nom, prenom, email, mot_de_passe, adresse, ville, code_postal, telephone) VALUES
('Dupont','Alice','alice.dupont@example.com','$2y$10$EXEMPLEHASH1','12 rue A', 'Paris','75001','0600000001'),
('Martin','Benoit','benoit.martin@example.com','$2y$10$EXEMPLEHASH2','5 avenue B', 'Lyon','69001','0600000002'),
('Nguyen','Camille','camille.nguyen@example.com','$2y$10$EXEMPLEHASH3','8 rue C', 'Marseille','13001','0600000003'),
('Durand','David','david.durand@example.com','$2y$10$EXEMPLEHASH4','20 impasse D', 'Toulouse','31000','0600000004'),
('Moreau','Emma','emma.moreau@example.com','$2y$10$EXEMPLEHASH5','2 place E', 'Nice','06000','0600000005');



INSERT INTO administrateurs (username, email, mot_de_passe, role) VALUES
('admin1','admin1@example.com','$2y$10$ADMINHASH1','admin'),
('superadmin','superadmin@example.com','$2y$10$ADMINHASH2','super_admin');




INSERT INTO commandes (numero_commande, id_client, statut, montant_total, adresse_livraison, ville_livraison, code_postal_livraison)
VALUES
('CMD-20250401-001', 1, 'payee', 729.99, '12 rue A', 'Paris', '75001'),
('CMD-20250402-002', 2, 'en_attente', 39.98, '5 avenue B', 'Lyon', '69001'),
('CMD-20250403-003', 3, 'expediee', 59.90, '8 rue C', 'Marseille', '13001'),
('CMD-20250404-004', 1, 'livree', 24.90, '12 rue A', 'Paris', '75001'),
('CMD-20250405-005', 4, 'annulee', 0.00, '20 impasse D', 'Toulouse', '31000'),
('CMD-20250406-006', 5, 'payee', 149.90, '2 place E', 'Nice', '06000'),
('CMD-20250407-007', 3, 'payee', 39.99, '8 rue C', 'Marseille', '13001'),
('CMD-20250408-008', 2, 'en_attente', 129.90, '5 avenue B', 'Lyon', '69001'),
('CMD-20250409-009', 4, 'expediee', 89.90, '20 impasse D', 'Toulouse', '31000'),
('CMD-20250410-010', 5, 'livree', 19.00, '2 place E', 'Nice', '06000');



INSERT INTO lignes_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES
(1, 1, 1, 699.00),
(1, 2, 1, 19.99),
(1, 11, 1, 9.99),

(2, 2, 2, 19.99),

(3, 16, 1, 59.90),

(4, 12, 1, 14.90),
(4, 11, 1, 9.99),

(5,32,1,31.66),

(6, 13, 1, 129.90),
(6, 15, 2, 7.50),

(7, 20, 1, 39.99),

(8, 14, 1, 109.00),
(8, 15, 1, 7.50),
(8, 5, 1, 15.00),

(9, 3, 1, 89.90),

(10, 22, 1, 19.00);
