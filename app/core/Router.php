<?php

declare(strict_types=1);

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $route): void
    {
        if (!isset($this->routes[$route])) {
            $route = 'login';
        }

        [$controller, $method] = $this->routes[$route];
        $controller->$method();
    }
}
