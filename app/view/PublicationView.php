<?php

Class PublicationView {
    public function renderIndex(array $teams): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Publications</title>
        </head>
        <body>

        <h1>Publications</h1>

        <?php foreach ($teams as $team): ?>
            <h2><?= htmlspecialchars($team['team_name']) ?></h2>

            <?php if (empty($team['publications'])): ?>
                <p>No publications.</p>
            <?php else: ?>
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
                    <?php foreach ($team['publications'] as $p): ?>
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
                    </tbody>
                </table>
            <?php endif; ?>

        <?php endforeach; ?>

        </body>
        </html>
        <?php
    }
}