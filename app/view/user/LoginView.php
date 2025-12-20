<?php

Class LoginView{
    public function renderIndex():void{
        ?>
        <!Doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login</title>
        </head>
        <body>
            <h1>Login</h2>
            <form method="POST" action="/login/login">
                <label for="username">Login:</label>
                <input type="text" id="login" name="login" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <button type="submit">Log In</button>
            </form>
        </body>
        </html>
        <?php
    }

    public function renderSuccess(array $user): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Login Success</title></head>
        <body>
            <h1>Welcome, <?= htmlspecialchars($user['login']) ?>!</h1>
            <p>You are now logged in.</p>
            <a href="/home/index">Go to Home</a> |
            <a href="/login/logout">Log out</a>
        </body>
        </html>
        <?php
    }

    public function renderError(string $message): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Login Error</title></head>
        <body>
            <h1>Login Failed</h1>
            <p><?= htmlspecialchars($message) ?></p>
            <a href="/login/index">Try again</a>
        </body>
        </html>
        <?php
    }
    public function renderLoggedIn(array $user): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Logged In</title></head>
        <body>
            <h1>Welcome, <?= htmlspecialchars($user['login']) ?>!</h1>
            <p>You are already logged in.</p>
            <form method="GET" action="/login/logout">
                <button type="submit">Log Out</button>
            </form>
        </body>
        </html>
        <?php
    }
}