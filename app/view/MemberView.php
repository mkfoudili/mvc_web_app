<?php

class MemberView {
    public function renderIndex(array $member): void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>
                <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
            </title>
        </head>
        <body>
            <body>

        <h1><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></h1>

        <?php if (!empty($member['photo_url'])): ?>
            <img src="<?= htmlspecialchars($member['photo_url']) ?>" alt="Member photo">
        <?php endif; ?>

        <div class="block">
            <span class="label">Login:</span>
            <?= htmlspecialchars($member['login'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Role:</span>
            <?= htmlspecialchars($member['role_in_lab'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Specialty:</span>
            <?= htmlspecialchars($member['specialty_id'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Team:</span>
            <?= htmlspecialchars($member['team_id'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Website:</span>
            <?php if (!empty($member['website'])): ?>
                <a href="<?= htmlspecialchars($member['website']) ?>" target="_blank">
                    <?= htmlspecialchars($member['website']) ?>
                </a>
            <?php else: ?>
                -
            <?php endif; ?>
        </div>

        <div class="block">
            <span class="label">Bio:</span><br>
            <?= nl2br(htmlspecialchars($member['bio'] ?? '-')) ?>
        </div>

        <p>
            <a href="/members">← Back to members</a>
        </p>

        </body>
        </html>
        <?php
    }
}