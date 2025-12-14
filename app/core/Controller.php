<?php

class Controller
{
    protected function render($viewPath, $data = [])
    {
        extract($data);
        require __DIR__ . '/../view/' . $viewPath . '.php';
    }
}