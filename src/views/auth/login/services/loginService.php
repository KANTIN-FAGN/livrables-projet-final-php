<?php

include_once BASE_PATH . 'src/includes/bootstrap.php';

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
    header('Location: /login', true, 400);
    exit;
}

// Si tout va bien, déléguer au contrôleur AuthController
try {
    $authController = new AuthController();
    $authController->login();
} catch (Exception $e) {
    $_SESSION["errors"]["exception"] = $e->getMessage();
    header('Location: /login', true, 500);
    exit;
}