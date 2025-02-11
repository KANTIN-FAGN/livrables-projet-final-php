<?php
error_reporting(E_ALL); // Activer les rapports d'erreurs
ini_set('display_errors', 1); // Afficher les erreurs
ini_set('display_startup_errors', 1); // Afficher les erreurs au démarrage

// Lien vers les dépendances nécessaires
require_once '../includes/bootstrap.php';

use App\Controllers\UserController;
use App\controllers\AuthController;

try {
    // Vérifiez et démarrez une session si elle n'est pas déjà active
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start(); // Démarrage de la session utilisateur indispensable pour stocker les messages ou données de session
    }

    // Collecte des données envoyées via le formulaire en méthode POST
    // Utilisation de l'opérateur null coalescent (??) pour fournir des valeurs par défaut si les champs sont absents
    $id = $_POST['id'] ?? null; // Identifiant utilisateur
    $firstname = $_POST['firstname'] ?? null; // Prénom de l'utilisateur
    $lastname = $_POST['lastname'] ?? null; // Nom de l'utilisateur
    $bio = $_POST['bio'] ?? null; // Biographie de l'utilisateur
    $website = $_POST['website'] ?? null; // URL du site web personnel de l'utilisateur
    $skills = $_POST['skills'] ?? []; // Liste des compétences sélectionnées
    $levels = $_POST['levels'] ?? []; // Liste des niveaux correspondant aux compétences

    // Validation : vérifier si l'ID est valide (non vide et numérique)
    if (empty($id) || !is_numeric($id)) {
        throw new Exception("L'ID utilisateur est invalide ou manquant."); // Lancer une exception si la validation échoue
    }

    // Préparation des données pour la mise à jour
    $userUpdateData = []; // Données pour la table 'users'
    $profileUpdateData = []; // Données pour la table 'profiles'

    // Construction des données à mettre à jour dans la table 'users'
    if (!empty($firstname)) {
        $userUpdateData['firstname'] = $firstname; // Ajout du prénom
    }
    if (!empty($lastname)) {
        $userUpdateData['lastname'] = $lastname; // Ajout du nom
    }

    // Construction des données à mettre à jour dans la table 'profiles'
    if (!empty($bio)) {
        $profileUpdateData['bio'] = $bio; // Ajout de la biographie
    }
    if (!empty($website)) {
        $profileUpdateData['website'] = $website; // Ajout de l'URL du site web
    }

    // Initialiser le contrôleur utilisateur pour interagir avec la base de données via le modèle
    $userController = new UserController(); // Instance du contrôleur utilisateur

    // Variable pour suivre le nombre total de lignes affectées par les mises à jour
    $updatedRows = 0;

    // Mise à jour des données dans la table 'users' si nécessaire
    if (!empty($userUpdateData)) {
        $updatedRows += $userController->updateUser($id, $userUpdateData); // Appel à la méthode de mise à jour
    }

    // Mise à jour des données dans la table 'profiles' si nécessaire
    if (!empty($profileUpdateData)) {
        $updatedRows += $userController->updateProfile($id, $profileUpdateData); // Appel à la méthode de mise à jour
    }

    // Mise à jour des compétences de l'utilisateur s'il y a des compétences et niveaux fournis
    if (!empty($skills) && !empty($levels) && count($skills) === count($levels)) {
        $updatedRows += $userController->updateUserSkills($id, $skills, $levels); // Mise à jour des compétences si les données sont cohérentes
    }

    // Recharge des données utilisateur mises à jour depuis la base de données pour refléter les changements récents
    $updatedUserData = $userController->getUser($id);

    // Validation : si les données utilisateur mises à jour ne sont pas récupérées, une exception est levée
    if (!$updatedUserData) {
        throw new Exception("Impossible de récupérer les données utilisateur mises à jour.");
    }

    // Mise à jour de la session utilisateur avec les nouvelles données
    $_SESSION['user'] = $updatedUserData; // Stocke les données de l'utilisateur dans la session pour un accès futur

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