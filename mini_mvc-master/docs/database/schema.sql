
CREATE TABLE categories (
id_categorie INT AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
description TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



CREATE TABLE produits (
id_produit INT AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(150) NOT NULL,
description TEXT,
prix DECIMAL(10,2) NOT NULL ,
quantite_dispo INT NOT NULL DEFAULT 0 ,
id_categorie INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (category_id) REFERENCES categories(id_categorie) ON DELETE SET NULL ON UPDATE CASCADE
);



CREATE TABLE clients (
id_client INT AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(100) NOT NULL,
prenom VARCHAR(100),
email VARCHAR(150) NOT NULL UNIQUE,
mot_de_passe VARCHAR(255) NOT NULL,
adresse VARCHAR(255),
ville VARCHAR(100),
code_postal VARCHAR(20),
telephone VARCHAR(30),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
deleted_at TIMESTAMP NULL
);



CREATE TABLE commandes (
id_commande INT AUTO_INCREMENT PRIMARY KEY,
numero_commande VARCHAR(50) NOT NULL UNIQUE,
id_client INT NOT NULL,
statut ENUM('en_attente','payee','expediee','livree','annulee') NOT NULL DEFAULT 'en_attente',
montant_total DECIMAL(10,2) DEFAULT 0 ,
adresse_livraison VARCHAR(255),
ville_livraison VARCHAR(100),
code_postal_livraison VARCHAR(20),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (id_client) REFERENCES clients(id_client) ON DELETE RESTRICT ON UPDATE CASCADE
);



CREATE TABLE lignes_commande (
id_lignes_commande INT AUTO_INCREMENT PRIMARY KEY,
id_commande INT NOT NULL,
id_produit INT NOT NULL,
quantite INT NOT NULL ,
prix_unitaire DECIMAL(10,2) NOT NULL ,
sous_total DECIMAL(10,2) GENERATED ALWAYS AS (quantite * prix_unitaire) VIRTUAL,
FOREIGN KEY (commande_id) REFERENCES commandes(id_commande) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (produit_id) REFERENCES produits(id_produit) ON DELETE RESTRICT ON UPDATE CASCADE
);



CREATE TABLE administrateurs (
id_admin INT AUTO_INCREMENT PRIMARY KEY,
nom_utilisateur VARCHAR(100) NOT NULL UNIQUE,
email VARCHAR(150) NOT NULL UNIQUE,
mot_de_passe VARCHAR(255) NOT NULL,
role ENUM('admin','super_admin') NOT NULL DEFAULT 'admin',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_produits_categorie ON produits(id_categorie);
CREATE INDEX idx_commandes_client ON commandes(id_client);
CREATE INDEX idx_lignes_commande_commande ON lignes_commandes(id_commande);
CREATE INDEX idx_panier_client ON panier(id_client);