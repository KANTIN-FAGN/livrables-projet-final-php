<?php

session_start(); // Assurez-vous que les sessions sont bien démarrées

require_once dirname(__DIR__, 4) . '/../vendor/autoload.php';

use App\controllers\AuthController;

$hasEmail = isset($_POST["email"]) && !empty($_POST["email"]);
$hasPassword = isset($_POST["password"]) && !empty($_POST["password"]);

// Vérifications basiques avant d'envoyer les données au contrôleur
if (!$hasEmail) {
    $_SESSION["errors"]["email"] = "Il faut saisir une adresse email.";
}
if (!$hasPassword) {
    $_SESSION["errors"]["password"] = "Il faut saisir un mot de passe.";
}

if (!$hasEmail || !$hasPassword) {
    header('Location: ../login/login.php', true, 400);
    exit;
}

// Si tout va bien, déléguer au contrôleur AuthController
try {
    $authController = new AuthController();
    $authController->login(); // Appelle directement la méthode `login()`
} catch (Exception $e) {
    // Gérer les erreurs éventuelles en les enregistrant dans la session
    $_SESSION["errors"]["exception"] = $e->getMessage();
    header('Location: ../login/login.php', true, 500);
    exit;
}