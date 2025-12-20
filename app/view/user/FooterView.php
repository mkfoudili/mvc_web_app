<?php
class FooterView {
    public function render(): void {
        ?>
        <footer>
            <p>&copy; <?= date('Y') ?> University Research Lab</p>
            <p>
                <a href="https://www.lab-website.example" target="_blank">Official Website</a> |
                <a href="https://www.facebook.com/labpage" target="_blank">Facebook</a> |
                <a href="https://twitter.com/labpage" target="_blank">Twitter</a> |
                <a href="https://www.linkedin.com/company/labpage" target="_blank">LinkedIn</a> |
                <a href="https://www.youtube.com/channel/labchannel" target="_blank">YouTube</a>
            </p>
            <p>
                <small>
                    For inquiries, please <a href="/contact/index">contact the lab</a>.
                </small>
            </p>
        </footer>
        <?php
    }
}