/**
 * JavaScript principal de l'application e-commerce
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});

/**
 * Initialiser les événements
 */
function initializeEventListeners() {
    // Fermer les alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });

    // Validation des formulaires
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', validateForm);
    });
}

/**
 * Valider un formulaire avant l'envoi
 */
function validateForm(e) {
    const form = e.target;
    const requiredFields = form.querySelectorAll('[required]');
    
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires');
    }
}

/**
 * Formatter un nombre en devise
 */
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}

/**
 * Afficher une notification
 */
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    
    const main = document.querySelector('main');
    main.insertBefore(notification, main.firstChild);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

/**
 * Charger les données d'un utilisateur (optionnel)
 */
async function loadUserData() {
    try {
        const response = await fetch('api/user/current');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Erreur lors du chargement des données utilisateur:', error);
    }
}

/**
 * Effectuer une requête AJAX
 */
async function apiRequest(url, options = {}) {
    try {
        const response = await fetch(url, {
            method: options.method || 'GET',
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            body: options.body ? JSON.stringify(options.body) : undefined
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Erreur API:', error);
        return null;
    }
}

/**
 * Ajouter un produit au panier (AJAX)
 */
async function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    try {
        const response = await fetch('index.php?page=cart&action=add', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            showNotification('Produit ajouté au panier', 'success');
            updateCartCount(data.itemCount);
            return true;
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
            return false;
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Une erreur s\'est produite', 'error');
        return false;
    }
}

/**
 * Mettre à jour le nombre d'articles du panier
 */
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

/**
 * Supprimer un produit du panier
 */
async function removeFromCart(productId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit?')) {
        return false;
    }

    const formData = new FormData();
    formData.append('product_id', productId);

    try {
        const response = await fetch('index.php?page=cart&action=remove', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            showNotification('Produit supprimé du panier', 'success');
            return true;
        } else {
            showNotification('Erreur lors de la suppression', 'error');
            return false;
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Une erreur s\'est produite', 'error');
        return false;
    }
}

/**
 * Appliquer un code de réduction (optionnel - à implémenter)
 */
function applyDiscountCode(code) {
    // À implémenter selon vos besoins
    console.log('Code de réduction à appliquer:', code);
}

/**
 * Valider une adresse email
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Valider un numéro de téléphone
 */
function validatePhone(phone) {
    const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

/**
 * Afficher/Masquer un modal
 */
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
    }
}

/**
 * Exporter les données (optionnel)
 */
function exportData(data, filename) {
    const json = JSON.stringify(data, null, 2);
    const blob = new Blob([json], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
}
