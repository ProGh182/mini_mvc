<?php
/**
 * Contrôleur AuthController - Authentification
 */

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Afficher la page de connexion
     */
    public function login()
    {
        if ($this->isLoggedIn()) {
            header('Location: index.php');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Tous les champs sont requis';
            } else {
                $user = $this->userModel->login($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Email ou mot de passe incorrect';
                }
            }
        }

        return $this->render('auth/login', ['error' => $error]);
    }

    /**
     * Afficher la page d'inscription
     */
    public function register()
    {
        if ($this->isLoggedIn()) {
            header('Location: index.php');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';

            // Validation
            if (empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
                $error = 'Tous les champs sont requis';
            } elseif ($password !== $confirmPassword) {
                $error = 'Les mots de passe ne correspondent pas';
            } elseif (strlen($password) < 6) {
                $error = 'Le mot de passe doit contenir au moins 6 caractères';
            } else {
                // Vérifier si l'email existe déjà
                if ($this->userModel->findByEmail($email)) {
                    $error = 'Cet email est déjà utilisé';
                } else {
                    if ($this->userModel->create($email, $password, $firstname, $lastname)) {
                        $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                    } else {
                        $error = 'Erreur lors de l\'inscription';
                    }
                }
            }
        }

        return $this->render('auth/register', [
            'error' => $error,
            'success' => $success
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }

    /**
     * Afficher le profil utilisateur
     */
    public function profile()
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'firstname' => $_POST['firstname'] ?? '',
                'lastname' => $_POST['lastname'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'city' => $_POST['city'] ?? '',
                'postal_code' => $_POST['postal_code'] ?? '',
                'country' => $_POST['country'] ?? ''
            ];

            if ($this->userModel->update($userId, $data)) {
                $success = 'Profil mis à jour';
                $user = $this->userModel->findById($userId);
            } else {
                $error = 'Erreur lors de la mise à jour';
            }
        }

        return $this->render('auth/profile', [
            'user' => $user,
            'error' => $error,
            'success' => $success
        ]);
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    private function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Rendre une vue
     */
    protected function render(string $view, array $data = [])
    {
        extract($data);
        $viewFile = VIEWS_PATH . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("Vue non trouvée: $viewFile");
        }

        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}
