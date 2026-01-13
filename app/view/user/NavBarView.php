<?php
class NavbarView {
    public function renderIndex(): void {
        $isLoggedIn = !empty($_SESSION['user_id']);
        ?>
        <img src="<?= base('assets/logo/lab_logo.png') ?>" alt="Lab Logo">
        <nav>
            <a href="<?= base('home') ?>">Home</a>
            <a href="<?= base('project/index') ?>">Projects</a>
            <a href="<?= base('publication/index') ?>">Publications</a>
            <a href="<?= base('event/index') ?>">Events</a>
            <a href="<?= base('team/index') ?>">Teams</a>
            <a href="<?= base('news/index') ?>">News</a>
            <?php if ($isLoggedIn): ?>
                <a href="<?= base('equipment/index') ?>">Equipment</a>
            <?php else: ?>
                <a href="<?= base('contact/index') ?>">Contact</a>
            <?php endif; ?>
        </nav>
        <?php if (!$isLoggedIn): ?>
            <a href="<?= base('login/index') ?>">
                <button>Log In</button>
            </a>
        <?php else: ?>
            <form method="GET" action="<?= base('login/logout') ?>" style="display:inline;">
                <button type="submit">Log Out</button>
            </form>
        <?php endif; ?>
        <?php
    }
}