<?php

use Core\Router;

// Crée une instance de Router
$router = new Router();
$controller = new \App\Controllers\PageController();

// Définir les routes
$router->add('GET', '/register', function () use ($controller) {
    $controller->register();
});
$router->add('GET', '/login', function () use ($controller) {
    $controller->login();
});

// Par exemple : Route avec paramètre dynamique
$router->add('GET', '/user/{id}', function ($id) {
    echo "Utilisateur avec ID : $id";
});

return $router;