<?php

Class LoginView{
    public function renderIndex():void{
        ?>
        <!Doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Login</title>
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <h1>Login</h2>
            <form method="POST" action="<?= base('admin/login/login') ?>">
                <label for="username">Login:</label>
                <input type="text" id="login" name="login" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <button type="submit">Log In</button>
            </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderSuccess(array $admin): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Login Success</title>
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Welcome, <?= htmlspecialchars($admin['login']) ?>!</h1>
            <p>You are now logged in.</p>
            <a href="<?= base('admin/home/index') ?>">Go to Home</a> |
            <a href="<?= base('admin/login/logout') ?>">Log out</a>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderError(string $message): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Login Error</title>
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Login Failed</h1>
            <p><?= htmlspecialchars($message) ?></p>
            <a href="<?= base('admin/login/index') ?>">Try again</a>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
    public function renderLoggedIn(array $admin): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Logged In</title>
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Welcome, <?= htmlspecialchars($admin['login']) ?>!</h1>
            <p>You are already logged in.</p>
            <form method="GET" action="<?= base('admin/login/logout') ?>">
                <button type="submit">Log Out</button>
            </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}