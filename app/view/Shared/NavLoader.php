<?php

class NavLoader
{
    public static function render(): void
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $basePath   = rtrim(dirname($scriptName), '/') === '/' ? '' : rtrim(dirname($scriptName), '/');

        $uri = $_SERVER['REQUEST_URI'];
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');

        $isAdmin = str_starts_with($uri, '/admin');

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
