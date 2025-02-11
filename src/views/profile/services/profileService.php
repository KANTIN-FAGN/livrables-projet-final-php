<?php
error_reporting(E_ALL); // Activer les rapports d'erreurs
ini_set('display_errors', 1); // Afficher les erreurs
ini_set('display_startup_errors', 1); // Afficher les erreurs au démarrage

// Lien vers les dépendances nécessaires
require_once '../includes/bootstrap.php';

use App\Controllers\UserController;
use App\controllers\AuthController;

try {
    // Démarrer une session si elle n'est pas active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Récupérer les données transmises via POST
    $id = $_POST['id'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $bio = $_POST['bio'] ?? null;
    $website = $_POST['website'] ?? null;

    // Vérifiez si l'ID est valide
    if (empty($id) || !is_numeric($id)) {
        throw new Exception("L'ID utilisateur est invalide ou manquant.");
    }

    // Initialisation du contrôleur d'utilisateur
    $userController = new UserController();

    // Initialiser les tableaux pour les mises à jour
    $userUpdateData = [];
    $profileUpdateData = [];

    // Préparer les données pour mettre à jour 'users'
    if (!empty($firstname)) {
        $userUpdateData['firstname'] = $firstname;
    }
    if (!empty($lastname)) {
        $userUpdateData['lastname'] = $lastname;
    }

    // Préparer les données pour mettre à jour 'profiles'
    if (!empty($bio)) {
        $profileUpdateData['bio'] = $bio;
    }

    if (!empty($website)) {
        $profileUpdateData['website'] = $website;
    }

    // Compter les lignes mises à jour
    $updatedRows = 0;

    // Exécuter la mise à jour des utilisateurs si nécessaire
    if (!empty($userUpdateData)) {
        error_log("Mise à jour des utilisateurs avec données : " . json_encode($userUpdateData));
        // Récupère les lignes affectées
        $updatedRows += $userController->updateUser($id, $userUpdateData);
    }

    // Exécuter la mise à jour des profils si nécessaire
    if (!empty($profileUpdateData)) {
        error_log("Mise à jour des profils avec données : " . json_encode($profileUpdateData));

        // Récupère les lignes affectées
        $updatedRows += $userController->updateProfile($id, $profileUpdateData);
    }

    // Recharger les données utilisateur mises à jour depuis la base
    $updatedUserData = $userController->getUser($id);

    if (!$updatedUserData) {
        throw new Exception("Impossible de récupérer les données utilisateur mises à jour.");
    }

    // Mettre à jour la session ou le JWT
    $_SESSION['user'] = $updatedUserData; // Met à jour la session utilisateur

    // Facultatif : Regénérer le JWT si vous utilisez des tokens
    $authController = new AuthController();
    $authController->regenerateToken($updatedUserData);

    // Message de retour selon le nombre de lignes affectées
    if ($updatedRows === 0) {
        $_SESSION['success'] = "Votre profil est déjà à jour.";
    } else {
        $_SESSION['success'] = "Profil mis à jour avec succès.";
    }

    // Redirection après la mise à jour
    header('Location: /profile', true, 303); // 303 Redirect pour POST > GET
    exit();
} catch (Exception $e) {
    // En cas d'erreur, journaliser et rediriger
    echo $e->getMessage();
    error_log("Erreur dans profileService.php : " . $e->getMessage());
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['errors']['exception'] = $e->getMessage();
    header('Location: /profile', true, 500);
    exit;
}