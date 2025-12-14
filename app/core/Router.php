<?php

class Router
{
    public function dispatch(string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');

        $parts = $uri === '' ? [] : explode('/', $uri);

        $controllerName = $parts[0] ?? 'home';
        $actionName     = $parts[1] ?? 'index';

        if ($controllerName === '') {
            $controllerName = 'home';
        }

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile  = __DIR__ . '/../controller/' . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            die("Controller '$controllerClass' not found");
        }

        require_once $controllerFile;

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            die("Action '$actionName' not found");
        }

        echo "Routing to $controllerClass::$actionName<br>";
        $controller->$actionName();
    }
}