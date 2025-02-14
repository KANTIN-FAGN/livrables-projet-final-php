<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1'); // Facultatif : enregistre les erreurs dans un fichier
error_log(BASE_PATH . 'logs/php_errors.log'); // Définit un fichier pour les logs si nécessaire

// Vérifie si une session est déjà démarrée, sinon démarre une session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Core\Services\JwtService;

// Définir BASE_PATH si elle n'existe pas déjà
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 4) . '/'); // On remonte jusqu'à la racine du projet
}

// Charger les variables d'environnement si elles ne sont pas déjà définies
if (!isset($_ENV['BASE_URL'])) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

// Récupération du jeton JWT
$jwt = $_COOKIE['access_token'] ?? null;

// Vérification du jeton JWT et récupération des données utilisateur
$userData = null;
if ($jwt) {
    $userData = JwtService::verifyToken($jwt);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token de 32 caractères
}
$csrfToken = $_SESSION['csrf_token'];


// Validation de la connexion : un utilisateur est considéré connecté si le jeton est valide
$isConnected = $userData !== null;