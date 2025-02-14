<?php
// Activer les rapports d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Échec de la validation CSRF.');
}
unset($_SESSION['csrf_token']);


// Inclusion des dépendances nécessaires
require_once '../includes/bootstrap.php';

use App\Controllers\SkillController;

$skillsController = new SkillController();

try {
    $name = $_POST['name'] ?? null;

    if (empty($name)) {
        throw new InvalidArgumentException("Nom manquant pour la creation du skills");
    }

    $skillsController->createSkill($name);
    header('Location: /dashboard', true, 303);
} catch (Exception $e) {
    http_response_code(500);
    echo "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    exit();
}