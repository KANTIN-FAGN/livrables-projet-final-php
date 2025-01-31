<?php

require_once dirname(__DIR__, 4) . '/../vendor/autoload.php';
use App\Controllers\UserController;

session_start(); // Démarrage de la session pour gérer les erreurs

$userController = new UserController();
$_SESSION["errors"] = []; // Réinitialiser les erreurs au début

// Vérification des champs postés
$hasEmail = isset($_POST["email"]) && !empty($_POST["email"]);
if (!$hasEmail) {
    $_SESSION["errors"]["email"] = "Il faut saisir une adresse email.";
} else {
    $email = $_POST["email"];

    if ($userController->userExists($email)) {
        $_SESSION["errors"]["email"] = "Un utilisateur avec cette adresse email existe déjà.";
    }
}

$hasPassword = isset($_POST["password"]) && !empty($_POST["password"]);
if (!$hasPassword) {
    $_SESSION["errors"]["password"] = "Il faut saisir un mot de passe.";
}

$hasFirstname = isset($_POST["firstname"]) && !empty($_POST["firstname"]);
if (!$hasFirstname) {
    $_SESSION["errors"]["firstname"] = "Il faut saisir un prénom.";
}

$hasLastname = isset($_POST["lastname"]) && !empty($_POST["lastname"]);
if (!$hasLastname) {
    $_SESSION["errors"]["lastname"] = "Il faut saisir un nom.";
}

// Rediriger vers le formulaire si des erreurs sont présentes
if (!empty($_SESSION["errors"])) {
    header('Location: register-form.php');
    exit();
}

// Si tout est valide, création de l'utilisateur
$password = $_POST["password"];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];

// Création de l'utilisateur
$newUserId = $userController->createUser($firstname, $lastname, $email, $hashedPassword);

if ($newUserId === false) {
    $_SESSION["errors"]["general"] = "Erreur lors de la création de l'utilisateur.";
    header('Location: register-form.php');
    exit();
}

// Tout est correct, afficher un message de succès ou rediriger
$_SESSION["success"] = "Utilisateur créé avec succès !";
header('Location: register');
exit();