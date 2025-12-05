# CONCEPTION.md - Réponses aux questions de réflexion

## 1. Pourquoi stocker le prix unitaire dans la table des lignes de commande plutôt que d'utiliser directement le prix du produit ?

**Réponse :** Nous stockons le prix unitaire dans `lignes_commande` pour figer le prix au moment de l'achat. Le prix d'un produit peut évoluer dans le temps (promotions, inflation, changement de tarif). Si nous nous contentions de référencer le prix actuel du produit, cela créerait plusieurs problèmes :

- Un client qui regarderait son historique de commandes verrait des prix différents de ceux qu'il a réellement payés
- Les rapports financiers seraient inexacts (le chiffre d'affaires historique changerait)
- En cas de litige, nous ne pourrions pas prouver le prix payé

**Exemple :**
- 1er janvier : Produit A = 50€
- Commande le 15 janvier : Client achète Produit A à 50€
- 1er février : Produit A passe à 60€
- **Sans stockage du prix** : L'historique montrerait 60€ (incorrect)
- **Avec stockage du prix** : L'historique montre 50€ (correct)

## 2. Quelle stratégie avez-vous choisie pour gérer les suppressions ?

### Pour chaque relation :

#### **a) Produits → Lignes de commande** : `ON DELETE RESTRICT`
**Justification :** On ne peut pas supprimer un produit qui a déjà été commandé. Cela garantit l'intégrité historique des commandes. Si un produit doit être retiré du catalogue, on le marque comme "inactif" plutôt que de le supprimer.

#### **b) Commandes → Lignes de commande** : `ON DELETE CASCADE`
**Justification :** Si une commande est annulée ou supprimée (erreur administrative), toutes ses lignes doivent disparaître avec elle. Cela évite les orphelins dans la base.

#### **c) Catégories → Produits** : `ON DELETE SET NULL`
**Justification :** Si une catégorie est supprimée, les produits ne disparaissent pas mais deviennent "sans catégorie". Cela permet de réorganiser le catalogue sans perdre les produits.

#### **d) Clients → Commandes** : `ON DELETE RESTRICT`
**Justification :** On ne peut pas supprimer un client qui a passé des commandes. Cela préserve l'historique commercial et légal. Pour désactiver un compte, on pourrait ajouter un champ `is_active` plutôt que supprimer.

#### **e) Soft Delete** : Implémenté avec `deleted_at` (optionnel)
**Justification :** Plutôt que de supprimer physiquement, on marque comme supprimé. Cela permet :
- De restaurer en cas d'erreur
- De conserver les données pour analyses
- De respecter les obligations légales de conservation

## 3. Comment gérez-vous les stocks ?

### Stratégie de gestion :

#### **a) Si rupture de stock :**
1. **Affichage produit :** "Indisponible" au lieu de "Ajouter au panier"
2. **Panier existant :** Message d'alerte "Ce produit n'est plus disponible"
3. **Commande :** Impossible de valider une commande contenant un produit en rupture

#### **b) Quand décrémenter le stock :**
**Phase 1 - Ajout au panier :** PAS de décrémentation
- Raison : Plusieurs clients pourraient mettre le même produit en panier

**Phase 2 - Validation panier :** Vérification du stock mais pas encore de décrémentation
- On vérifie que le stock est suffisant pour tous les articles

**Phase 3 - Paiement validé :** Décrémentation IMMÉDIATE
- `UPDATE produits SET quantite_dispo = quantite_dispo - ? WHERE id_produit = ?`
- Transaction SQL pour éviter les conflits

**Phase 4 - Annulation commande :** Réincrémentation du stock
- Si annulation avant expédition, on remet le stock disponible

#### **c) Contrôles techniques :**
```sql
-- Contrainte pour éviter les stocks négatifs
CHECK (quantite_dispo >= 0)

-- Transaction pour garantir l'atomicité
BEGIN;
SELECT quantite_dispo FROM produits WHERE id_produit = ? FOR UPDATE;
-- Vérifier suffisance
UPDATE produits SET quantite_dispo = quantite_dispo - ? WHERE id_produit = ?;
COMMIT;
```

## 4. Avez-vous prévu des index ? Lesquels et pourquoi ?

### Index essentiels :

#### **a) `idx_produits_categorie` (produits.id_categorie)**
**Pourquoi :** Les requêtes les plus fréquentes seront "afficher tous les produits d'une catégorie". Sans index, MySQL devra parcourir toute la table.

#### **b) `idx_commandes_client` (commandes.id_client)**
**Pourquoi :** Chaque client veut voir "mes commandes". Cet index accélère considérablement `WHERE id_client = ?`.

#### **c) `idx_lignes_commande_commande` (lignes_commande.id_commande)**
**Pourquoi :** Pour afficher le détail d'une commande, on joint toutes ses lignes. Index crucial pour les `JOIN`.

#### **d) `idx_lignes_commande_produit` (lignes_commande.id_produit)**
**Pourquoi :** Pour analyser "quels produits se vendent le plus" ou "trouver toutes les commandes contenant un produit".

#### **e) `idx_panier_client` (panier.id_client)**
**Pourquoi :** Le panier est consulté à chaque visite utilisateur. Performance critique.

### Index secondaires envisageables :
- `produits.prix` : pour les tris par prix
- `commandes.created_at` : pour les requêtes "commandes récentes"
- `produits.nom` : pour la recherche texte (préférer FULLTEXT pour de vrais moteurs de recherche)

## 5. Comment assurez-vous l'unicité du numéro de commande ?

### Solution technique :

#### **a) Contrainte d'unicité :**
```sql
numero_commande VARCHAR(50) NOT NULL UNIQUE
```

#### **b) Format structuré :**
```
CMD-AAAA-MM-JJ-NNN
```
Exemple : `CMD-2024-04-15-001`

- `CMD` : Préfixe fixe
- `AAAA-MM-JJ` : Date de création
- `NNN` : Numéro séquentiel du jour (redémarre à 001 chaque jour)

#### **c) Génération en PHP :**
```php
public function generateOrderNumber(): string
{
    $date = date('Y-m-d');
    $lastNumber = $this->getLastOrderNumberOfDay($date);
    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    
    return "CMD-{$date}-{$nextNumber}";
}
```

#### **d) Avantages :**
- Lisible par un humain (on voit la date)
- Unique par construction
- Pas de collision possible
- Supporte jusqu'à 999 commandes par jour

## 6. Quelles sont les extensions possibles de votre modèle ?

### Extensions immédiates :

#### **a) Gestion de plusieurs adresses par client**
```sql
CREATE TABLE adresses (
    id_adresse INT PRIMARY KEY AUTO_INCREMENT,
    id_client INT NOT NULL,
    type ENUM('livraison', 'facturation', 'les_deux'),
    adresse TEXT,
    ville VARCHAR(100),
    code_postal VARCHAR(20),
    pays VARCHAR(50) DEFAULT 'France',
    est_par_defaut BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_client) REFERENCES clients(id_client)
);
```

#### **b) Historique des prix**
```sql
CREATE TABLE historique_prix (
    id_historique INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT NOT NULL,
    ancien_prix DECIMAL(10,2),
    nouveau_prix DECIMAL(10,2),
    date_changement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    raison VARCHAR(100), -- 'promotion', 'inflation', 'changement_tarif'
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);
```

#### **c) Avis clients**
```sql
CREATE TABLE avis (
    id_avis INT PRIMARY KEY AUTO_INCREMENT,
    id_client INT NOT NULL,
    id_produit INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    est_valide BOOLEAN DEFAULT FALSE, -- modération
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id_client),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);
```

#### **d) Images multiples par produit**
```sql
CREATE TABLE images_produit (
    id_image INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT NOT NULL,
    url_image VARCHAR(255),
    ordre INT DEFAULT 0, -- pour ordonner l'affichage
    est_principale BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);
```

#### **e) Promotions et codes de réduction**
```sql
CREATE TABLE promotions (
    id_promotion INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE,
    type ENUM('pourcentage', 'montant_fixe'),
    valeur DECIMAL(10,2),
    date_debut DATETIME,
    date_fin DATETIME,
    utilisations_max INT,
    utilisations_actuelles INT DEFAULT 0
);

CREATE TABLE promotion_produit (
    id_promotion INT,
    id_produit INT,
    PRIMARY KEY (id_promotion, id_produit)
);
```

#### **f) Suivi des stocks avancé**
```sql
CREATE TABLE mouvements_stock (
    id_mouvement INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT NOT NULL,
    type ENUM('entree', 'sortie', 'ajustement'),
    quantite INT,
    raison VARCHAR(100),
    reference VARCHAR(50), -- numéro de commande, bon de livraison, etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### **g) Wishlist (liste de souhaits)**
```sql
CREATE TABLE wishlist (
    id_wishlist INT PRIMARY KEY AUTO_INCREMENT,
    id_client INT NOT NULL,
    id_produit INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist (id_client, id_produit)
);
```

### Évolution à long terme :

1. **Système de parrainage** : Clients qui recommandent, gagnent des points
2. **Programme de fidélité** : Points cumulables, échangeables
3. **Abonnements** : Produits livrés régulièrement
4. **Livraison en point relais** : Gestion des points de retrait
5. **Interface multi-langue** : Pour expansion internationale
6. **API REST** : Pour application mobile
7. **Système de tags** : Catégorisation flexible des produits
8. **Comparateur de produits** : Tableau comparatif dynamique

### Conclusion d'évolutivité :
Le modèle actuel est volontairement simple mais conçu pour être étendu. La clé est de maintenir une bonne normalisation (3FN) tout en anticipant les besoins métiers futurs. Chaque extension peut être ajoutée progressivement sans nécessiter de refonte complète.