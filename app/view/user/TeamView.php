<?php

Class TeamView{

public function renderIndex(array $teams): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Teams</title>
        </head>
        <body>

        <h1>Teams</h1>

        <?php if (empty($teams)): ?>
            <p>No teams found.</p>
        <?php else: ?>
            <?php foreach ($teams as $team): ?>
                <div class="team">
                    <h2><?= htmlspecialchars($team['name']) ?></h2>
                    <p><strong>Leader:</strong>
                        <?= htmlspecialchars($team['leader_first_name'] ?? '-') ?>
                        <?= htmlspecialchars($team['leader_last_name'] ?? '-') ?>
                    </p>
                    <p><strong>Domain:</strong> <?= htmlspecialchars($team['domain'] ?? '-') ?></p>
                    <p><strong>Description:</strong><br>
                        <?= nl2br(htmlspecialchars($team['description'] ?? '-')) ?>
                    </p>
                    <h3>Members</h3>
                    <?php if (empty($team['members'])): ?>
                        <p>No members assigned.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($team['members'] as $member): ?>
                                <li>
                                    <a href="/member/index?id=<?= (int)$member['id'] ?>">
                                        <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                                    </a>
                                    <?php if (!empty($member['role_in_team'])): ?>
                                        (<?= htmlspecialchars($member['role_in_team']) ?>)
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        </body>
        </html>
        <?php
    }
    public function renderTeams(array $teams) : void{
        ?>
        <?php
        if (empty($teams)): ?>
            <p>No teams found.</p>
        <?php else: ?>
            <?php foreach ($teams as $team): ?>
                <div class="team">
                    <h2><?= htmlspecialchars($team['name']) ?></h2>
                    <p><strong>Leader:</strong>
                        <?= htmlspecialchars($team['leader_first_name'] ?? '-') ?>
                        <?= htmlspecialchars($team['leader_last_name'] ?? '-') ?>
                    </p>
                    <p><strong>Domain:</strong> <?= htmlspecialchars($team['domain'] ?? '-') ?></p>
                    <p><strong>Description:</strong><br>
                        <?= nl2br(htmlspecialchars($team['description'] ?? '-')) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        </body>
        </html>
        <?php
    }
}