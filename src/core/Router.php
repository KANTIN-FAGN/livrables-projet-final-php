<?php

namespace Core;

class Router
{
    private $routes = [];
    private $middlewares = [];

    public function add($method, $path, $callback, $middlewares = [])
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch($method, $path)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->match($route['path'], $path, $params)) {
                // Exécute tous les middlewares attachés à cette route
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $middlewareInstance->handle($params);
                }

                // Exécute la route si les middlewares sont passés
                call_user_func_array($route['callback'], $params);
                return;
            }
        }

        // Si aucune route ne correspond, affiche une erreur 404
        http_response_code(404);
        echo "Page non trouvée";
    }

    private function match($routePath, $currentPath, &$params)
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([a-zA-Z0-9_\-]+)', $routePath);
        $pattern = "#^$pattern$#";

        if (preg_match($pattern, $currentPath, $matches)) {
            array_shift($matches); // Supprime la correspondance complète
            $params = $matches;
            return true;
        }

        return false;
    }
}