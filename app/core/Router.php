<?php

class Router
{
    public function dispatch($uri)
    {
        $uri = trim($uri, '/');

        $parts = explode('/', $uri);

        $controllerName = $parts[0] ?? 'equipment';
        $actionName     = $parts[1] ?? 'index';

        if ($controllerName === '') {
            $controllerName = 'equipment';
        }

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile  = __DIR__ . '/../controller/' . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            die("Controller '$controllerClass' not found");
        }

        require_once $controllerFile;

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            die("Action '$actionName' not found");
        }

        $controller->$actionName();
    }
}