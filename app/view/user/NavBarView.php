<?php
class NavbarView {
    public function renderIndex(): void {
        $isLoggedIn = !empty($_SESSION['user_id']);
        ?>
        <div class="topbar">
            <div class="topbar-left">
                <button class="back-button" onclick="history.back()">
                <img src="<?= base('assets/icons/back_arrow.svg') ?>" alt="Back">
                </button>
                <img src="<?= base('assets/logo/lab_logo.png') ?>" alt="Lab Logo" class="logo">
                <nav>
                <a href="<?= base('home') ?>">Home</a>
                <a href="<?= base('project/index') ?>">Projects</a>
                <a href="<?= base('publication/index') ?>">Publications</a>
                <a href="<?= base('event/index') ?>">Events</a>
                <a href="<?= base('team/index') ?>">Teams</a>
                <a href="<?= base('news/index') ?>">News</a>
                <a href="<?= base('member/list') ?>">Members</a>
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base('equipment/index') ?>">Equipment</a>
                    <a href="<?= base('member?id=' . $_SESSION['member_id']) ?>">My Profile</a>
                <?php else: ?>
                    <a href="<?= base('contact/index') ?>">Contact</a>
                <?php endif; ?>
                </nav>
            </div>

            <div class="topbar-right">
                <?php if (!$isLoggedIn): ?>
                <a href="<?= base('login/index') ?>"><button>Log In</button></a>
                <?php else: ?>
                <form method="GET" action="<?= base('login/logout') ?>" style="display:inline;">
                    <button type="submit">Log Out</button>
                </form>
                <?php endif; ?>
            </div>
            </div>
        <?php
    }
}