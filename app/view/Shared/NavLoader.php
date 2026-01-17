<?php

class NavLoader
{
    public static function render(): void
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $basePath   = rtrim(dirname($scriptName), '/') === '/' ? '' : rtrim(dirname($scriptName), '/');

        $uri = $_SERVER['REQUEST_URI'];
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');

        $isAdmin = strpos($uri, '/admin') === 0;

        if ($isAdmin) {
            require_once __DIR__ . '/../admin/NavBarView.php';
            $navbar = new NavBarView();
        } else {
            require_once __DIR__ . '/../user/NavBarView.php';
            $navbar = new NavBarView();
        }

        echo '<header>';
        $navbar->renderIndex();
        echo '</header>';
    }
}
