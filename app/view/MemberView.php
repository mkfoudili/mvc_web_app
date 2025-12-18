<?php

class MemberView {
    public function renderIndex(array $member, array $publications): void{
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

        <h2>Publications</h2>
        <?php $this->renderPublications($publications); ?>
        </body>
        </html>
        <?php
    }

    public function renderPublications(array $publications): void{
        ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Authors</th>
                    <th>Date</th>
                    <th>DOI</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($publications)): ?>
                <tr>
                    <td colspan="6">No publications</td>
                </tr>
            <?php else: ?>
                <?php foreach ($publications as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['publication_type_id'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['authors'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['date_published'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['doi'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($p['url'])): ?>
                                <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank">Link</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
}