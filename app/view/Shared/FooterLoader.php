<?php
require_once __DIR__ . '/../user/FooterView.php';

class FooterLoader
{
    public static function render(): void
    {
        $footer = new FooterView();
        $footer->renderIndex();
    }
}