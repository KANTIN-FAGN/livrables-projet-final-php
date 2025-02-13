<?php
// Activer les rapports d'erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Inclusion des dépendances nécessaires via le fichier bootstrap
require_once '../includes/bootstrap.php';

use App\Controllers\UserController;
use App\Controllers\AuthController;

try {
    // Récupérer les données de la requête POST avec des valeurs par défaut si elles sont absentes
    $id = $_POST['id'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $website_link = $_POST['website_link'] ?? null;
    $skills = $_POST['skills'] ?? [];
    $levels = $_POST['levels'] ?? [];

    $avatar = $_POST['avatar'] ?? null;

    // Validation : vérifier l'ID utilisateur
    if (empty($id) || !is_numeric($id)) {
        throw new InvalidArgumentException("L'ID utilisateur est invalide ou manquant.");
    }

    // Instanciation du contrôleur utilisateur
    $userController = new UserController();

    // Vérifier la présence d'un fichier uploadé dans $_FILES['avatar']
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        // Récupérer temporairement le chemin du fichier et ses informations
        $temporaryPath = $_FILES['avatar']['tmp_name'];
        $originalName = $_FILES['avatar']['name'];

        // Extraire l'extension du fichier
        $fileParts = explode('.', $originalName);
        $extension = strtolower(end($fileParts));

        // Vérifier que l'extension du fichier est valide
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new RuntimeException("Extension de fichier non autorisée. Fichiers acceptés : " . implode(', ', $allowedExtensions));
        }

        // Générer un nouveau nom pour l'image avec les informations de l'utilisateur
        $prenom = isset($userData['firstname']) ? strtolower($userData['firstname']) : 'undefined';
        $nom = isset($userData['lastname']) ? strtolower($userData['lastname']) : 'undefined';
        $id = $userData['id'] ?? '0';
        $date = date('Ymd_His');

        $newFileName = $nom . '_' . $prenom . '_' . $id . '_' . $date . '.' . $extension;

        // Définir le dossier pour les uploads
        $uploadDir = 'img/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Créer le dossier s'il n'existe pas
        }

        // Vérifier et supprimer l'ancienne image s'il y en a une
        $existingAvatar = $userController->getAvatarByUserId($id); // Méthode à créer pour récupérer l'ancien avatar
        if ($existingAvatar && file_exists($uploadDir . $existingAvatar)) {
            unlink($uploadDir . $existingAvatar); // Supprimer l'ancien fichier
        }

        // Déplacer le fichier vers le dossier final
        $finalPath = $uploadDir . $newFileName;
        if (!move_uploaded_file($temporaryPath, $finalPath)) {
            throw new RuntimeException("Erreur lors du téléchargement de l'image.");
        }

        // Mettre à jour l'avatar en base de données
        $userController->updateAvatar($id, $newFileName);
    }

    // Préparation des données pour les tables 'users' et 'profiles'
    $userUpdateData = array_filter([
        'firstname' => $firstname,
        'lastname' => $lastname
    ]);

    $profileUpdateData = array_filter([
        'bio' => $bio,
        'website_link' => $website_link
    ]);

    // Initialisation des lignes affectées (somme totale)
    $updatedRows = 0;

    // Mise à jour des données 'users' si nécessaire
    if (!empty($userUpdateData)) {
        $userRows = $userController->updateUser($id, $userUpdateData);
        error_log("Lignes mises à jour dans users : " . json_encode($userRows));
        $updatedRows += is_numeric($userRows) ? intval($userRows) : 0;
    }

    // Mise à jour des données 'profiles' si nécessaire
    if (!empty($profileUpdateData)) {
        // Vérifiez si le profil existe
        if (!$userController->profileExists($id)) {
            // Créez un profil par défaut si le profil n'existe pas
            $userController->createDefaultProfile($id);
        }

        $profileRows = $userController->updateProfile($id, $profileUpdateData);
        error_log("Lignes mises à jour dans profiles : " . json_encode($profileRows));
        $updatedRows += is_numeric($profileRows) ? intval($profileRows) : 0;
    }

    // Mise à jour des compétences et niveaux si disponibles et valides
    if (!empty($skills) && !empty($levels)) {
        if (count($skills) !== count($levels)) {
            throw new InvalidArgumentException("Le nombre de compétences et de niveaux ne correspond pas.");
        }
        $skillsRows = $userController->updateUserSkills($id, $skills, $levels);
        error_log("Lignes mises à jour dans skills : " . json_encode($skillsRows));
        $updatedRows += is_numeric($skillsRows) ? intval($skillsRows) : 0;
    }

    // Récupérer les données utilisateur mises à jour
    $updatedUserData = $userController->getUser($id);

    // Validation : s'assurer que les données utilisateur sont bien récupérées
    if (empty($updatedUserData)) {
        throw new RuntimeException("Impossible de récupérer les données utilisateur mises à jour.");
    }

    // Mise à jour des données utilisateur dans la session
    $_SESSION['user'] = $updatedUserData;

    // Optionnel : Regénération du token JWT (si vous utilisez une gestion avec JWT pour l'authentification)
    $authController = new AuthController(); // Instance pour gérer les tokens et l'authentification
    $authController->regenerateToken($updatedUserData); // Regénère un token basé sur les nouvelles données utilisateur

    // Préparation d'un message de retour sur le statut de la mise à jour
    if ($updatedRows === 0) {
        // Si aucune ligne n'a été mise à jour, l'utilisateur est informé que son profil est à jour
        $_SESSION['success'] = "Votre profil est déjà à jour.";
    } else {
        // Si des lignes ont été mises à jour, informer que la mise à jour a réussi
        $_SESSION['success'] = "Profil mis à jour avec succès.";
    }

    // Redirection après mise à jour
    header('Location: /profile', true, 303); // Redirection vers la page du profil (statut HTTP 303 pour éviter la re-soumission du formulaire)
    exit(); // Terminer le script pour garantir qu'aucune autre sortie n'est envoyée

} catch (InvalidArgumentException $e) {
    // Gestion des erreurs de validation des données d'entrée
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (RuntimeException $e) {
    // Gestion des erreurs liées à la logique ou aux données
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    // Gestion globale des exceptions (erreurs imprévues)
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Une erreur inattendue s\'est produite : ' . $e->getMessage()
    ]);
}