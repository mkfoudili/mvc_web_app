<?php
class NavbarView {
    public function renderIndex(): void {
        $isLoggedIn = !empty($_SESSION['admin_id']);
        ?>
        <div class="topbar">
            <div class="topbar-left">
                <button class="back-button" onclick="history.back()">
                    <img src="<?= base('assets/icons/back_arrow.svg') ?>" alt="Back">
                </button>
                <img src="<?= base('assets/logo/lab_logo.png') ?>" alt="Lab Logo" class="logo">
                <?php if ($isLoggedIn): ?>
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
            </div>

            <div class="topbar-right">
                <?php if (!$isLoggedIn): ?>
                    <a href="<?= base('admin/login/index') ?>"><button>Log In</button></a>
                <?php else: ?>
                    <form method="GET" action="<?= base('admin/login/logout') ?>" style="display:inline;">
                        <button type="submit">Log Out</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}