<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$basePath   = rtrim(dirname($scriptName), '/');

define('BASE_PATH', $basePath === '/' ? '' : $basePath);
function base(string $path = ''): string {
    return BASE_PATH . '/' . ltrim($path, '/');
}

session_start();
require_once __DIR__ . '/../app/core/Router.php';

// Get the URI without query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (BASE_PATH && strpos($uri, BASE_PATH) === 0) {
    $uri = substr($uri, strlen(BASE_PATH));
}

$router = new Router();
$router->dispatch($uri);