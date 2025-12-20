<?php

Class UserView {
    public function renderIndex($users) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Admin - Users</title>
        </head>
        <body>
            <h1>Users</h1>
            <button disabled>Add User</button>
            <br><br>
            <?php $this->renderUserList($users); ?>
        </body>
        </html>
        <?php
    }

    public function renderUserList($users) {
        ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Permissions</th>
                    <th>Specialty ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6">No users found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['login']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?= htmlspecialchars($user['permissions'] ?? '{}') ?>
                        </td>
                        <td><?= htmlspecialchars($user['specialty_id'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($user['status'] ?? '-') ?></td>
                        <td>
                            <button disabled>Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
}