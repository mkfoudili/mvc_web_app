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
            <a href="/admin/user/addForm">
                <button>Add User</button>
            </a>
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
                            <a href="/admin/user/edit?id=<?= $user['id'] ?>">
                                <button>Update</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

    public function renderAddForm(array $roles, array $specialties, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Add User</title>
        </head>
        <body>
            <h1>Add User</h1>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="/admin/user/create">
                <label>
                    Login:
                    <input type="text" name="login" required>
                </label>
                <br><br>

                <label>
                    Email:
                    <input type="email" name="email" required>
                </label>
                <br><br>

                <label>
                    Password:
                    <input type="password" name="password" required>
                </label>
                <br><br>

                <input type="hidden" name="status" value="active">

                <label>
                    Role:
                    <select name="role_id">
                        <option value="">-- Select Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>">
                                <?= htmlspecialchars($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    or add new:
                    <input type="text" name="new_role">
                </label>
                <br><br>

                <label>
                    Specialty:
                    <select name="specialty_id">
                        <option value="">-- Select Specialty --</option>
                        <?php foreach ($specialties as $spec): ?>
                            <option value="<?= $spec['id'] ?>">
                                <?= htmlspecialchars($spec['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    or add new:
                    <input type="text" name="new_specialty">
                </label>
                <br><br>

                <button type="submit">Create User</button>
                <a href="/admin/user/index"><button type="button">Cancel</button></a>
            </form>
        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $user, array $roles, array $specialties, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Edit User</title>
        </head>
        <body>
            <h1>Edit User</h1>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="/admin/user/update">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="status" value="active">

                <label>Login:
                    <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>
                </label><br><br>

                <label>Email:
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </label><br><br>

                <label>Password (leave blank to keep current):
                    <input type="password" name="password">
                </label><br><br>

                <label>Role:
                    <select name="role_id">
                        <option value="">-- Select Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $role['id']==$user['role_id']?'selected':'' ?>>
                                <?= htmlspecialchars($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    or add new: <input type="text" name="new_role">
                </label><br><br>

                <label>Specialty:
                    <select name="specialty_id">
                        <option value="">-- Select Specialty --</option>
                        <?php foreach ($specialties as $spec): ?>
                            <option value="<?= $spec['id'] ?>" <?= $spec['id']==$user['specialty_id']?'selected':'' ?>>
                                <?= htmlspecialchars($spec['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    or add new: <input type="text" name="new_specialty">
                </label><br><br>

                <button type="submit">Update User</button>
                <a href="/admin/user/index"><button type="button">Cancel</button></a>
            </form>
        </body>
        </html>
        <?php
    }
}