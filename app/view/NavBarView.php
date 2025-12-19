<?php
class NavbarView {
    public function renderIndex(): void {
        ?>
        <img src="assets/logo/lab_logo.png" alt="Lab Logo">
        <nav>
            <a href="/home">Home</a>
            <a href="/project/index">Projects</a>
            <a href="/publication/index">Publications</a>
            <a href="/event/index">Events</a>
            <a href="/team/index">Teams</a>
            <a href="/news/index">News</a>
            <a href="/contact/index">Contact</a>
        </nav>
        <a href="/login/index">
            <button>Log In</button>
        </a>
        <?php
    }
    public function renderMember(): void {
        ?>
        <img src="assets/logo/lab_logo.png" alt="Lab Logo">
        <nav>
            <a href="/home">Home</a>
            <a href="/project/index">Projects</a>
            <a href="/publication/index">Publications</a>
            <a href="/event/index">Events</a>
            <a href="/team/index">Teams</a>
            <a href="/news/index">News</a>
            <a href="/equipment/index">Equipment</a>
        </nav>
        <?php
    }
}