<?php
// Activer les rapports d'erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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
    // Récupérer les données de la requête POST
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $external_link = $_POST['external_link'] ?? null;
    $image_path = $_POST['image_path'] ?? null;

    // Validation de l'ID utilisateur
    if (empty($id) || !is_numeric($id)) {
        throw new InvalidArgumentException("L'ID utilisateur est invalide ou manquant.");
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
        $userData = $user->getUser($id);
        $prenom = isset($userData['firstname']) ? strtolower($userData['firstname']) : 'undefined';
        $nom = isset($userData['lastname']) ? strtolower($userData['lastname']) : 'undefined';
        $date = date('Ymd_His');

        $newFileName = 'imagePost_' . $nom . '_' . $prenom . '_' . $id . '_' . $date . '.' . $extension;

        // Enregistrement du fichier
        $uploadDir = 'img/posts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $finalPath = $uploadDir . $newFileName;
        if (!move_uploaded_file($temporaryPath, $finalPath)) {
            throw new RuntimeException("Erreur lors du téléchargement de l'image.");
        }
    }

    // Création du post via PostController
    $result = $post->createPost(
        (int) $id,
        $title ?? '',
        $description ?? '',
        $newFileName ?? '',
        $external_link ?? ''
    );

    // Regénération du token JWT
    $userData = $user->getUser($id); // Récupérer les informations utilisateur mises à jour
    $auth->regenerateToken($userData); // Mettre à jour le token JWT

    // Vérification du résultat et redirection
    if ($result['status'] === 'success') {
        header('Location: /profile', true, 303);
        exit();
    } else {
        throw new RuntimeException($result['message'] ?? "Erreur lors de la création du post.");
    }

} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (RuntimeException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Une erreur inattendue s\'est produite : ' . $e->getMessage()
    ]);
}