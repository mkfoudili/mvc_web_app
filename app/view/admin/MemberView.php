<?php

Class MemberView{
    public function renderIndex(array $members):void{
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Admin - Members</title>
        </head>
        <body>
            <h1>Members</h1>
            <button disabled>Add Member</button>
            <br><br>
            <?php $this->renderMembersList($members); ?>
        </body>
        </html>
        <?php
    }

    public function renderMembersList(array $members):void{
        ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Login</th>
                    <th>Specialty</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($members)): ?>
                <tr><td colspan="6">No members found</td></tr>
            <?php else: ?>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member['last_name']) ?></td>
                        <td><?= htmlspecialchars($member['first_name']) ?></td>
                        <td><?= htmlspecialchars($member['login']) ?></td>
                        <td>
                            <?php
                            echo htmlspecialchars($member['specialty_name'] ?? '-');
                            ?>
                        </td>
                        <td><?= htmlspecialchars($member['role_in_lab'] ?? '-') ?></td>
                        <td>
                            <button disabled>Update</button>
                            <button disabled>Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
}