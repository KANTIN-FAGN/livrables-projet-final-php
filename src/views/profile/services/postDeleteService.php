<?php
// Activer les rapports d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclusion des dépendances nécessaires
require_once '../includes/bootstrap.php';

use App\controllers\AuthController;
use App\controllers\PostController;
use App\Controllers\UserController;

// Initialisation du contrôleur
$postController = new PostController();
$user = new UserController();
$auth = new AuthController();

try {

    echo "Suppression d'un post : " . $post['id'];

    if (!$post) {
        throw new RuntimeException("Le post avec l'ID" . $post['id'] . "n'existe pas dans la base de données.");
    }

    error_log("Post récupéré (avant suppression) : " . print_r($post, true));

    // Suppression du post
    $result = $postController->deletePostById((int)$post['id']);

    // Regénération du token JWT
    $userData = $user->getUser((int)$post['user_id']); // Récupérer les informations utilisateur mises à jour
    $auth->regenerateToken($userData); // Mettre à jour le token JWT

    if ($result) {
        error_log("Le post avec l'ID" . $post['id'] . "a été supprimé avec succès.");
        header('Location: /profile', true, 303); // Redirection en cas de succès
        exit();
    } else {
        throw new RuntimeException("Échec de suppression du post avec l'ID" . $post['id']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    exit();
}