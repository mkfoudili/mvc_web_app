<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$basePath   = rtrim(dirname($scriptName), '/');

session_start();
require_once __DIR__ . '/../app/core/Router.php';

define('BASE_PATH', $basePath === '/' ? '' : $basePath);
function base(string $path = ''): string {
    return BASE_PATH . '/' . ltrim($path, '/');
}
function redirect(string $path, array $params = []): void {
    $target = rtrim(BASE_PATH . '/' . ltrim($path, '/'), '/');

    if ($params) {
        $target .= '?' . http_build_query($params);
    }

    $current = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    
    if ($current === $target) {
        return;
    }
    header("Location: $target");
    exit;
}



// Get the URI without query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (BASE_PATH && strpos($uri, BASE_PATH) === 0) {
    $uri = substr($uri, strlen(BASE_PATH));
}

$router = new Router();
$router->dispatch($uri);