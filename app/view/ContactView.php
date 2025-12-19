<?php
class ContactView {
    public function renderForm(): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Contact the Lab</title>
        </head>
        <body>

        <h1>Contact the Lab</h1>

        <form method="post" action="/contact/send">
            <div>
                <label>Your Email:</label><br>
                <input type="email" name="email" required>
            </div>

            <div>
                <label>Subject:</label><br>
                <input type="text" name="subject" required>
            </div>

            <div>
                <label>Message:</label><br>
                <textarea name="message" rows="6" cols="50" required></textarea>
            </div>

            <br>
            <button type="submit">Send</button>
        </form>

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