<?php
class NavbarView {
    public function renderIndex(): void {
        session_start();
        $isLoggedIn = !empty($_SESSION['user_id']);
        ?>
        <img src="assets/logo/lab_logo.png" alt="Lab Logo">
        <nav>
            <a href="/home">Home</a>
            <a href="/project/index">Projects</a>
            <a href="/publication/index">Publications</a>
            <a href="/event/index">Events</a>
            <a href="/team/index">Teams</a>
            <a href="/news/index">News</a>
            <?php if ($isLoggedIn): ?>
                <a href="/equipment/index">Equipment</a>
            <?php else: ?>
                <a href="/contact/index">Contact</a>
            <?php endif; ?>
        </nav>
        <?php if (!$isLoggedIn): ?>
            <a href="/login/index">
                <button>Log In</button>
            </a>
        <?php else: ?>
            <form method="GET" action="/login/logout" style="display:inline;">
                <button type="submit">Log Out</button>
            </form>
        <?php endif; ?>
        <?php
    }
}