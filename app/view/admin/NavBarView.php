<?php
class NavbarView {
    public function renderIndex(): void {
        $isLoggedIn = !empty($_SESSION['admin_id']);
        ?>
        <img src="assets/logo/lab_logo.png" alt="Lab Logo">
        <?php if (!$isLoggedIn): ?>
        <nav>
            <a href="<?= base('admin/project/index') ?>">Projects</a>
            <a href="<?= base('admin/publication/index') ?>">Publications</a>
            <a href="<?= base('admin/event/index') ?>">Events</a>
            <a href="<?= base('admin/user/index') ?>">Users</a>
            <a href="<?= base('admin/team/index') ?>">Teams</a>
            <a href="<?= base('admin/member/index') ?>">Members</a>
            <a href="<?= base('admin/equipment/index') ?>">Equipments</a>
        </nav>
        <?php endif; ?>
        <?php if (!$isLoggedIn): ?>
            <a href="<?= base('admin/login/index') ?>">
                <button>Log In</button>
            </a>
        <?php else: ?>
            <form method="GET" action="<?= base('admin/logout') ?>" style="display:inline;">
                <button type="submit">Log Out</button>
            </form>
        <?php endif; ?>
        <?php
    }
}