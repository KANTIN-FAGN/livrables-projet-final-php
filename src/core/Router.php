<?php
// src/core/Router.php

namespace Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable|array $callback): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => "#^" . preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9_\-]+)', $path) . "$#",
            'callback' => $callback
        ];
    }
    public function dispatch(string $method, string $uri): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($route['path'], $uri, $matches)) {
                array_shift($matches); // Supprimer $match complet
                $callback = $route['callback'];

                // Vérifiez si le callback est un callable
                if (is_array($callback) && class_exists($callback[0]) && method_exists($callback[0], $callback[1])) {
                    $callback = [new $callback[0], $callback[1]];
                }

                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } else {
                    throw new \Exception('Route callback is not callable');
                }

                return;
            }
        }

        http_response_code(404);
        echo "404 - Route not found";
    }
}