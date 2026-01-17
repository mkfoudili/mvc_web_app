<?php
require_once __DIR__ . '/../../helpers/components.php';
Class UserView {
    public function renderIndex(array $users): void {
        $pageTitle = '<h1>Users</h1>';
        $usersTableHtml = $this->renderUserTable($users);
        $pageHtml = $pageTitle . '
            <a href="' . e(base('admin/user/addForm')) . '">
                <button>Add User</button>
            </a>' . $usersTableHtml;

        layout('base', [
            'title'   => 'Admin - Users',
            'content' => $pageHtml
        ]);
    }

    private function renderUserTable(array $users): string {
        if (empty($users)) {
            return '<p>No users found.</p>';
        }

        $headers = ['Login', 'Email', 'Permissions', 'Specialty ID', 'Status', 'Action'];

        $rows = [];
        foreach ($users as $user) {
            $rows[] = [
                ['type' => 'text', 'value' => $user['login']],
                ['type' => 'text', 'value' => $user['email']],
                ['type' => 'text', 'value' => $user['permissions'] ?? '{}'],
                ['type' => 'text', 'value' => $user['specialty_id'] ?? '-'],
                ['type' => 'text', 'value' => $user['status'] ?? '-'],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/user/edit?id=' . $user['id'])) . '">
                            <button>Update</button>
                         </a>
                         <a href="' . e(base('admin/user/toggleStatus?id=' . $user['id'])) . '">
                            <button>' . ($user['status'] === 'active' ? 'Suspend' : 'Activate') . '</button>
                         </a>
                         <a href="' . e(base('admin/user/delete?id=' . $user['id'])) . '" 
                            onclick="return confirm(\'Are you sure you want to delete this user?\');">
                            <button>Delete</button>
                         </a>'
                ]
            ];
        }

        return component('Table', [
            'headers' => $headers,
            'rows'    => $rows
        ]);
    }

    public function renderAddForm(array $roles, array $specialties, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Add User</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Add User</h1>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="<?= base('admin/user/create') ?>">
                <div class="form-group">
                <label>
                    Login:
                    <input type="text" name="login" required>
                </label>
                

                <label>
                    Email:
                    <input type="email" name="email" required>
                </label>
                

                <label>
                    Password:
                    <input type="password" name="password" required>
                </label>
                

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
                

                <button type="submit">Create User</button>
                <a href="<?= base('admin/user/index') ?>"><button type="button">Cancel</button></a>
                </div>
            </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
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
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Edit User</h1>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base('admin/user/update') ?>">
                <div class="form-group">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="status" value="active">

                <label>Login:
                    <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>
                </label>

                <label>Email:
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </label>

                <label>Password (leave blank to keep current):
                    <input type="password" name="password">
                </label>

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
                </label>

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
                </label>

                <button type="submit">Update User</button>
                <a href="<?= base('admin/user/index') ?>"><button type="button">Cancel</button></a>
                </div>
            </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}