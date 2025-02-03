<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

define('BASE_PATH', __DIR__ . '/../../');

$dotenvPath = BASE_PATH . '.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv::createImmutable(BASE_PATH); // Charger le fichier .env
    $dotenv->load();
} else {
    die('.env introuvable. Assurez-vous qu\'il existe dans le répertoire racine.');
}

// Assurez-vous que BASE_URL est défini
$baseUrl = $_ENV['BASE_URL'] ?? 'http://localhost:8001'; // Utilisez une valeur par défaut si non défini
$baseUrl = rtrim($baseUrl, '/'); // On s'assure qu'il ne se termine pas par un "/"

// Exemples d'accès aux variables
$jwtSecret = $_ENV['JWT_SECRET'] ?? 'your_default_secret_here';

// Charger les routes
$router = require __DIR__ . '/../../src/routes/routes.php';

// Obtenir la méthode HTTP et l'URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Supprimer un potentiel préfixe dans l'URL
$basePrefix = ''; // Exemple : '/mon-projet' si nécessaire
if (!empty($basePrefix) && strpos($uri, $basePrefix) === 0) {
    $uri = substr($uri, strlen($basePrefix));
}

// Dispatcher les requêtes
$router->dispatch($method, $uri);