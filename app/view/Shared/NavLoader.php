<?php

class NavLoader
{
    public static function render(): void
    {
        $isAdmin = str_starts_with($_SERVER['REQUEST_URI'], '/admin');

        if ($isAdmin) {
            require_once __DIR__ . '/../admin/NavBarView.php';
            $navbar = new NavBarView();
        } else {
            require_once __DIR__ . '/../user/NavBarView.php';
            $navbar = new NavBarView();
        }

        echo '<header style="position:sticky;top:0;background:#fff;z-index:1000;border-bottom:1px solid #ccc;padding:10px;">';
        $navbar->renderIndex();
        echo '</header>';
    }
}
