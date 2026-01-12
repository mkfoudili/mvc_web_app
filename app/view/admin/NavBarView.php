<?php
class NavbarView {
    public function renderIndex(): void {
        $isLoggedIn = !empty($_SESSION['admin_id']);
        ?>
        <img src="assets/logo/lab_logo.png" alt="Lab Logo">
        <?php if (!$isLoggedIn): ?>
        <nav>
            <a href="/admin/project/index">Projects</a>
            <a href="/admin/publication/index">Publications</a>
            <a href="/admin/event/index">Events</a>
            <a href="/admin/user/index">Users</a>
            <a href="/admin/team/index">Teams</a>
            <a href="/admin/member/index">Members</a>
            <a href="/admin/equipment/index">Equipments</a>
        </nav>
        <?php endif; ?>
        <?php if (!$isLoggedIn): ?>
            <a href="/admin/login/index">
                <button>Log In</button>
            </a>
        <?php else: ?>
            <form method="GET" action="/admin/logout" style="display:inline;">
                <button type="submit">Log Out</button>
            </form>
        <?php endif; ?>
        <?php
    }
}