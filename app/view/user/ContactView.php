<?php
class ContactView {
    public function renderForm(): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Contact the Lab</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Contact the Lab</h1>

        <form method="post" action="<?= base('contact/send') ?>">
            <div class="form-group">
            <div>
                <label>Your Email:</label>
                <input type="email" name="email" required>
            </div>

            <div>
                <label>Subject:</label>
                <input type="text" name="subject" required>
            </div>

            <div>
                <label>Message:</label>
                <textarea name="message" rows="6" cols="50" required></textarea>
            </div>

            
            <button type="submit">Send</button>
            </div>
        </form>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderSuccess(): void {
        ?>
        <p>Your message has been sent successfully.</p>
        <?php
    }

    public function renderError(string $error): void {
        ?>
        <p>Error: <?= htmlspecialchars($error) ?></p>
        <?php
    }
}