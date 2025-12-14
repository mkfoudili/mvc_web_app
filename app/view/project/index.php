<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Projects</title>
</head>
<body>
    <h1>Projects</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Title</th>
                <th>Leader</th>
                <th>Members</th>
                <th>Partners</th>
                <th>Theme</th>
                <th>Funding Type</th>
                <th>Project Page URL</th>
                <th>Poster URL</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['title']) ?></td>
                        <td><?= htmlspecialchars($project['leader_first_name'] . ' ' . $project['leader_last_name']) ?></td>
                        <td><?= htmlspecialchars($project['members_list']) ?></td>
                        <td><?= htmlspecialchars($project['partners_list']) ?></td>
                        <td><?= htmlspecialchars($project['theme']) ?></td>
                        <td><?= htmlspecialchars($project['funding_type_id']) ?></td>
                        <td>
                            <?php if (!empty($project['project_page_url'])): ?>
                                <a href="<?= htmlspecialchars($project['project_page_url']) ?>" target="_blank">Link</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($project['poster_url'])): ?>
                                <a href="<?= htmlspecialchars($project['poster_url']) ?>" target="_blank">Poster</a>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($project['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">No projects found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>