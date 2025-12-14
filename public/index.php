<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/Router.php';

// Get the URI without query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router = new Router();
$router->dispatch($uri);