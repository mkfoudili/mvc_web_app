<?php
class PublicationView {
    public function renderIndex(array $publications): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Admin - Publications</title>
        </head>
        <body>

        <h1>Publications</h1>
        <button disabled="disabled">Add Publication</button>

        <?php if (empty($publications)): ?>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
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
                        <td>
                            <button disabled="disabled">Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        </body>
        </html>
        <?php
    }
}