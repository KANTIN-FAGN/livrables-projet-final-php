<?php

use Core\Router;
use Middlewares\OwnerMiddleware;
use Middlewares\IsAdminMiddleware;

// Crée une instance de Router
$router = new Router();
$controller = new \App\Controllers\PageController();
$authController = new \App\Controllers\AuthController();

// Définir les routes
$router->add('GET', '/', function () use ($controller) {
    $controller->home();
});

$router->add('GET', '/profile', function () use ($controller) {
    $controller->profile();
}, [OwnerMiddleware::class]);
$router->add('POST', '/profile/edit', function () use ($controller) {
    $controller->editProfile();
}, [OwnerMiddleware::class]);

$router->add('POST', '/create/post', function () use ($controller) {
    $controller->createPost();
}, [OwnerMiddleware::class]);
$router->add('GET', '/profile/edit-post/{id}', function ($id) use ($controller) {
    $controller->editPost($id);
}, [OwnerMiddleware::class]);
$router->add('POST', '/profile/edit-post-service', function () use ($controller) {
    $controller->editPostService();
}, [OwnerMiddleware::class]);
$router->add('GET' , '/profile/delete-post/{id}', function ($id) use ($controller) {
    $controller->deletePost($id);
}, [OwnerMiddleware::class]);

$router->add('GET', '/register', function () use ($controller) {
    $controller->register();
});
$router->add('POST', '/register-controller', function () use ($controller) {
    $controller->registerService();
});

$router->add('GET', '/login', function () use ($controller) {
    $controller->login();
});
$router->add('POST', '/login-controller', function () use ($controller) {
    $controller->loginService();
});

$router->add('GET', '/logout', function () use ($authController) {
    $authController->logout();
});

$router->add('GET', '/dashboard', function () use ($controller) {
    $controller->dashboard();
}, [IsAdminMiddleware::class]);

// Par exemple : Route avec paramètre dynamique
$router->add('GET', '/user/{id}', function ($id) {
    echo "Utilisateur avec ID : $id";
});

return $router;