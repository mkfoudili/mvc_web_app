<?php

class Router
{
    public function dispatch(string $uri): void
    {
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        $parts = $uri === '' ? [] : explode('/', $uri);

        $namespace = 'user';
        $controllerName = $parts[0] ?? 'home';
        $actionName     = $parts[1] ?? 'index';

        if ($controllerName === 'admin') {
            $namespace = 'admin';
            $controllerName = $parts[1] ?? 'dashboard';
            $actionName     = $parts[2] ?? 'index';
        }

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile  = __DIR__ . "/../controller/$namespace/" . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            die("Controller '$controllerClass' not found in $namespace");
        }

        require_once $controllerFile;
        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            die("Action '$actionName' not found in $controllerClass");
        }

        $controller->$actionName();
    }
}