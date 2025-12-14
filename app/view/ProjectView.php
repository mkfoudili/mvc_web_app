<?php

class ProjectView {
    public function renderIndex(array $projects): void
    {
        ?>

        <h1>Projects</h1>

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
                </tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
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
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php
    }
}