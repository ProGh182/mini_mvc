# E-Commerce Shop - PHP Vanilla

Une application e-commerce complète développée en **PHP Vanilla** (PHP pur, sans framework) avec une base de données **MySQL**.

## Fonctionnalités Implémentées

### Fonctionnalités Minimales ✅
- ✅ **Page d'accueil** affichant une liste de produits
- ✅ **Page détail produit** avec informations complètes
- ✅ **Système de panier** (ajout, suppression, modification de quantités, affichage du total)
- ✅ **Authentification utilisateur** (inscription + connexion)
- ✅ **Passage de commande** (validation du panier, création de commande)
- ✅ **Espace client** permettant de voir l'historique des commandes

### Fonctionnalités Bonus ✅
- ✅ **Interface responsive** (mobile, tablette, desktop)
- ✅ **Gestion du stock** (vérification de disponibilité, mise à jour après commande)
- ✅ **Filtre de produits** (par catégories, recherche par nom)
- ✅ **Profil utilisateur** (modification des informations de livraison)
- ✅ **Gestion de catégories** de produits

## Architecture du Projet

```
ecommerce/
├── config/
│   └── config.php              # Configuration de l'application
├── src/
│   ├── Controllers/            # Contrôleurs
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── AuthController.php
│   │   └── OrderController.php
│   ├── Models/                 # Modèles
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Cart.php
│   │   ├── Order.php
│   │   └── Category.php
│   ├── Database.php            # Classe de gestion BD
│   └── Router.php              # Routeur simple
├── views/                      # Vues (templates)
│   ├── layout.php              # Template principal
│   ├── home/
│   │   └── index.php
│   ├── product/
│   │   └── show.php
│   ├── cart/
│   │   └── view.php
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── profile.php
│   ├── order/
│   │   ├── list.php
│   │   └── show.php
│   └── errors/
│       └── 404.php
├── public/
│   ├── index.php               # Point d'entrée
│   ├── css/
│   │   └── style.css           # CSS responsive
│   └── js/
│       └── app.js              # JavaScript (optionnel)
└── database/
    └── schema.sql              # Script de création BD
```

## Installation

### Prérequis
- **PHP** 7.4+
- **MySQL** 5.7+ ou **MariaDB**
- **Apache** avec `mod_rewrite` ou **PHP Built-in Server**

### Étapes d'installation

#### 1. Créer la base de données

```sql
-- Option 1: Via phpMyAdmin ou MySQL client
mysql -u root -p < ecommerce/database/schema.sql

-- Option 2: Manuellement via phpMyAdmin
-- 1. Créer une nouvelle base de données "ecommerce"
-- 2. Sélectionner la base
-- 3. Aller dans l'onglet SQL
-- 4. Copier/coller le contenu de database/schema.sql
-- 5. Exécuter
```

#### 2. Configurer la connexion à la base de données

Éditer le fichier `ecommerce/config/config.php` et adapter les paramètres:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Votre mot de passe MySQL
define('DB_NAME', 'ecommerce');
define('DB_PORT', 3306);
```

#### 3. Lancer l'application

**Option A - Avec PHP Built-in Server (Recommandé pour développement)**

```bash
cd ecommerce/public
php -S localhost:8000
```

Puis accéder à: `http://localhost:8000`

**Option B - Avec Apache/XAMPP**

1. Copier le dossier `ecommerce` dans `C:\xampp\htdocs\`
2. Accéder à: `http://localhost/ecommerce/public/`

**Option C - Avec un Virtual Host**

Éditer `C:\xampp\apache\conf\extra\httpd-vhosts.conf` et ajouter:

```apache
<VirtualHost *:80>
    ServerName ecommerce.local
    DocumentRoot "C:\xampp\htdocs\mini_mvc-master\mini_mvc-master\ecommerce\public"
    <Directory "C:\xampp\htdocs\mini_mvc-master\mini_mvc-master\ecommerce\public">
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

Puis ajouter dans `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 ecommerce.local
```

Accéder à: `http://ecommerce.local`

## Identifiants de Test

Après l'installation, des utilisateurs de test sont déjà créés:

**Admin/Client**
- **Email**: `test@example.com`
- **Mot de passe**: `password123`

**Admin**
- **Email**: `admin@example.com`
- **Mot de passe**: `password123`

## Structure de la Base de Données

### Tables

**users** - Utilisateurs
```sql
id, email, password, firstname, lastname, phone, address, city, postal_code, country, created_at
```

**categories** - Catégories de produits
```sql
id, name, description, created_at
```

**products** - Produits
```sql
id, category_id, name, description, price, stock, image_url, created_at
```

**carts** - Paniers
```sql
id, user_id, product_id, quantity, added_at
```

**orders** - Commandes
```sql
id, user_id, total_amount, status, created_at, updated_at
```

**order_items** - Détails des commandes
```sql
id, order_id, product_id, quantity, price
```

## Flux d'Utilisation

1. **Accueil** → Liste de tous les produits avec filtres (catégories, recherche)
2. **Détail Produit** → Affiche les informations complètes et permet l'ajout au panier
3. **Panier** → Visualise les produits ajoutés, modifier les quantités, voir le total
4. **Authentification** → Inscription ou connexion requise pour acheter
5. **Profil** → Modification des informations de livraison
6. **Commande** → Validation du panier et création de la commande
7. **Historique** → Visualisation des commandes passées

## Fonctionnalités Avancées

### Gestion du Stock
- Vérification automatique du stock disponible avant l'ajout au panier
- Mise à jour du stock lors de la validation d'une commande
- Affichage du statut de disponibilité sur chaque produit

### Sécurité
- **Hashage des mots de passe** avec `password_hash()` (bcrypt)
- **Validation des données** côté serveur
- **Protection contre les injections SQL** avec requêtes préparées (PDO)
- **Sessions PHP** pour l'authentification
- **Vérification des propriétaires** (un utilisateur ne peut voir que ses commandes)

### Formulaires
- **Inscription** avec validation du mot de passe
- **Connexion** avec vérification des identifiants
- **Profil** pour compléter les informations de livraison
- **Panier** avec modification des quantités

### Recherche et Filtres
- Recherche par nom et description de produits
- Filtrage par catégories
- Filtrage par prix (peut être amélioré)

## Points d'Extension

Pour améliorer l'application:

### 1. Paiement
```php
// Ajouter une intégration Stripe/PayPal
class PaymentController {
    public function process() { /* ... */ }
}
```

### 2. Notifications Email
```php
// Envoyer un email de confirmation de commande
mail($user['email'], 'Commande confirmée', $message);
```

### 3. Admin Panel
```php
// Créer un contrôleur AdminController pour gérer produits/commandes
class AdminController { /* ... */ }
```

### 4. API REST
```php
// Créer une API JSON pour mobile
header('Content-Type: application/json');
echo json_encode($data);
```

### 5. Tests Unitaires
```php
// Ajouter PHPUnit pour tester les modèles
require 'vendor/autoload.php';
```

## Technologies Utilisées

- **Backend**: PHP 7.4+
- **Base de données**: MySQL 5.7+
- **Frontend**: HTML5, CSS3 (Responsive), JavaScript (Vanilla)
- **Sécurité**: PDO, bcrypt, Sessions PHP
- **Architecture**: MVC simple (sans framework)

## Améliorations Possibles

- [ ] Intégration d'un système de paiement (Stripe/PayPal)
- [ ] Notifications par email
- [ ] Panel d'administration
- [ ] Système de notation/commentaires
- [ ] Wishlist
- [ ] Code de réduction
- [ ] Pagination des produits
- [ ] Historique complet des transactions
- [ ] Export de factures PDF
- [ ] Image upload et galerie

## Troubleshooting

### Erreur: "Fatal error: Class 'Database' not found"
→ Vérifier que les fichiers de `config/config.php` et `src/Database.php` sont bien inclus dans `public/index.php`

### Erreur: "SQLSTATE[HY000]: General error: 2006 MySQL server has gone away"
→ Vérifier que le serveur MySQL est lancé et accessible

### Erreur: "Access denied for user 'root'@'localhost'"
→ Modifier les identifiants dans `config/config.php` (DB_USER, DB_PASS)

### Les pages ne s'affichent pas correctement
→ Vérifier que le CSS est bien charger (`public/css/style.css`)
→ Si vous utilisez Apache, assurez que `mod_rewrite` est activé

## Licence

MIT License - Libre d'utilisation à but éducatif

## Auteur

Projet développé à des fins éducatives en PHP Vanilla.

---

**Besoin d'aide?** Consultez la documentation PHP officielle: https://www.php.net/docs.php
