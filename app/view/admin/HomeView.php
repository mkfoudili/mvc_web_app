<?php

Class HomeView{
    public function renderIndex():void{
        ?>
        <!Doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Home</title>
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Admin Home</h1>
            <p>Welcome to the admin dashboard</p>
        </body>
        </html>
        <?php
    }   
}