<?php
// Activer les rapports d'erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Échec de la validation CSRF.');
}
unset($_SESSION['csrf_token']);

// Inclusion des dépendances nécessaires via le fichier bootstrap
require_once '../includes/bootstrap.php';

use App\controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\AuthController;

// Initialisation des contrôleurs
$user = new UserController();
$auth = new AuthController();
$post = new PostController();

try {
    // Récupération des données du formulaire
    $user_id = $_POST['user_id'] ?? null;
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $external_link = $_POST['external_link'] ?? null;
    $image_path = $_POST['image_path'] ?? null;

    // Validation de l'ID utilisateur
    if (empty($id) || !is_numeric($id)) {
        throw new InvalidArgumentException("L'ID utilisateur est invalide ou manquant.");
    }

    // Validation des champs obligatoires
    if (empty($title) || empty($description)) {
        throw new InvalidArgumentException("Le titre et la description sont obligatoires !");
    }

    // Vérification de l'upload d'image
    $newFileName = null;
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $temporaryPath = $_FILES['image_path']['tmp_name'];
        $originalName = $_FILES['image_path']['name'];

        // Traitement de l'extension et validation
        $fileParts = explode('.', $originalName);
        $extension = strtolower(end($fileParts));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extension, $allowedExtensions)) {
            throw new RuntimeException("Extension de fichier non autorisée. Extensions valides : " . implode(', ', $allowedExtensions));
        }

        // Génération d'un nouveau nom pour l'image
        $userData = $user->getUser((int)$user_id); // Vérifiez que cette méthode existe
        $prenom = isset($userData['firstname']) ? strtolower($userData['firstname']) : 'undefined';
        $nom = isset($userData['lastname']) ? strtolower($userData['lastname']) : 'undefined';
        $date = date('Ymd_His');

        $newFileName = 'imagePost_' . $nom . '_' . $prenom . '_' . $id . '_' . $date . '.' . $extension;

        // Enregistrement du fichier
        $uploadDir = 'img/posts/';
        $existingAvatar = $post->getImagePostByID($id);

        if (!empty($existingAvatar) && $existingAvatar === 'default.png') {
            error_log("Aucune suppression, l'image par défaut est utilisée : " . $existingAvatar);
        } else {
            if ($existingAvatar && file_exists($uploadDir . $existingAvatar)) {
                unlink($uploadDir . $existingAvatar); // Supprimer l'ancien fichier
            }
        }

        $finalPath = $uploadDir . $newFileName;
        if (!move_uploaded_file($temporaryPath, $finalPath)) {
            throw new RuntimeException("Erreur lors du téléchargement de l'image.");
        }
    } else {
        // Si aucune nouvelle image n'est téléchargée, conserver l'image existante du post
        $existingPost = $post->getPostById((int)$id); // Assurez-vous que cette méthode existe et fonctionne
        $newFileName = $existingPost['image_path'] ?? null; // Conservez l'image existante
    }

    // Modification du post via PostController
    $result = $post->updatePost(
        (int)$id,
        $title ?? '',
        $description ?? '',
        $newFileName ?? '',
        $external_link ?? ''
    );

    // Regénération du token JWT
    $userData = $user->getUser($user_id); // Récupérer les informations utilisateur mises à jour
    $auth->regenerateToken($userData); // Mettre à jour le token JWT

    // Vérifier si la mise à jour a été réussie
    if ($result) {
        header('Location: /profile', true, 302);
        exit();
    } else {
        echo $result;
        throw new RuntimeException("Échec de la mise à jour du post.");
    }
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500);
    echo "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    exit();
}