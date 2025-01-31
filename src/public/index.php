<?php

require_once __DIR__ . '/../../vendor/autoload.php';

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

