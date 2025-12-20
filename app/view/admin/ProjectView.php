<?php

Class ProjectView{
    public function renderIndex($projects){
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Projects</title>
        </head>
        <body>
        <h1>Projects</h1>
        <button disabled="disabled">Add Project</button>
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Leader</th>
                    <th>Theme</th>
                    <th>Funding</th>
                    <th>Project Page</th>
                    <th>Poster</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td>
                        <a href="/admin/project/show?id=<?= (int)$p['id'] ?>">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                    </td>
                    <td>
                        <?= htmlspecialchars($p['leader_first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars($p['leader_last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td><?= htmlspecialchars($p['theme'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($p['funding_type_id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if (!empty($p['project_page_url'])): ?>
                            <a href="<?= htmlspecialchars($p['project_page_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Link</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($p['poster_url'])): ?>
                            <a href="<?= htmlspecialchars($p['poster_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Poster</a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <button disabled="disabled">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </body>
        </html>
        <?php
    }

    public function renderShow(array $project, array $members, array $partners): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?= htmlspecialchars($project['title']) ?></title>
        </head>
        <body>

        <h1><?= htmlspecialchars($project['title']) ?></h1>

        <p><strong>Leader:</strong>
            <?= htmlspecialchars($project['leader_first_name'] ?? '') ?>
            <?= htmlspecialchars($project['leader_last_name'] ?? '') ?>
        </p>

        <p><strong>Theme:</strong> <?= htmlspecialchars($project['theme'] ?? '-') ?></p>
        <p><strong>Funding:</strong> <?= htmlspecialchars($project['funding_type_id'] ?? '-') ?></p>

        <p><strong>Project Page:</strong>
            <?php if (!empty($project['project_page_url'])): ?>
                <a href="<?= htmlspecialchars($project['project_page_url']) ?>" target="_blank">Link</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </p>

        <p><strong>Poster:</strong>
            <?php if (!empty($project['poster_url'])): ?>
                <a href="<?= htmlspecialchars($project['poster_url']) ?>" target="_blank">Poster</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </p>

        <p><strong>Description:</strong><br>
            <?= nl2br(htmlspecialchars($project['description'] ?? '-')) ?>
        </p>

        <hr>

        <h3>Project Members</h3>
        <?php if (empty($members)): ?>
            <p>-</p>
        <?php else: ?>
            <ul>
                <?php foreach ($members as $m): ?>
                    <li>
                        <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?>
                        <?php if (!empty($m['role_in_project'])): ?>
                            (<?= htmlspecialchars($m['role_in_project']) ?>)
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h3>Project Partners</h3>
        <?php if (empty($partners)): ?>
            <p>-</p>
        <?php else: ?>
            <ul>
                <?php foreach ($partners as $p): ?>
                    <li><?= htmlspecialchars($p['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        </body>
        </html>
        <?php
    }
}