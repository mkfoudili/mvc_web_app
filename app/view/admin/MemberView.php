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
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1>Members</h1>
            <a href="<?= base('admin/member/addForm') ?>">
                <button>Add Member</button>
            </a>
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
                            <a href="<?= base('admin/member/edit?id=' . $member['id']) ?>">
                                <button>Update</button>
                            </a>
                            <a href="<?= base('admin/member/delete?id=' . $member['id']) ?>" onclick="return confirm('Are you sure you want to delete this member?');">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
    public function renderEditForm(array $member, array $specialties, array $teams): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Edit Member</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Member</h1>

        <form method="post" action="<?= base('admin/member/update') ?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">
            <input type="hidden" name="login" value="<?= htmlspecialchars($member['login']) ?>">

            <div>
                <label>First Name:</label><br>
                <input type="text" name="first_name"
                       value="<?= htmlspecialchars($member['first_name']) ?>" required>
            </div>

            <div>
                <label>Last Name:</label><br>
                <input type="text" name="last_name"
                       value="<?= htmlspecialchars($member['last_name']) ?>" required>
            </div>

            <div>
                <label>Profile Photo:</label><br>
                <input type="file" name="photo" accept="image/*">
                <?php if (!empty($member['photo_url'])): ?>
                    <br>
                    <img src="<?= htmlspecialchars($member['photo_url']) ?>" alt="Current photo" width="120">
                    <br>
                    <button type="submit" name="delete_photo" value="1">Delete Photo</button>
                <?php endif; ?>
            </div>

            <div>
                <label>Role:</label><br>
                <input type="text" name="role_in_lab"
                       value="<?= htmlspecialchars($member['role_in_lab'] ?? '') ?>">
            </div>

            <div>
                <label>Specialty:</label><br>
                <select name="specialty_id">
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= ($member['specialty_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label>Or add new specialty:</label><br>
                <input type="text" name="new_specialty" placeholder="New specialty name">
            </div>

            <div>
                <label>Team:</label><br>
                <select name="team_id">
                    <option value="">-- Select Team --</option>
                    <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= ($member['team_id'] == $t['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Website:</label><br>
                <input type="url" name="website"
                       value="<?= htmlspecialchars($member['website'] ?? '') ?>">
            </div>

            <div>
                <label>Bio:</label><br>
                <textarea name="bio" rows="5" cols="50"><?= htmlspecialchars($member['bio'] ?? '') ?></textarea>
            </div>

            <br>
            <button type="submit">Save Changes</button>
            <a href="<?= base('admin/member/index') ?>"><button type="button">Cancel</button></a>
        </form>
        </body>
        </html>
        <?php
    }

    public function renderAddForm(array $specialties, array $teams, array $users, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Add Member</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Member</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base('admin/member/create') ?>" enctype="multipart/form-data">
            <div>
                <label>First Name:</label><br>
                <input type="text" name="first_name" required>
            </div>

            <div>
                <label>Last Name:</label><br>
                <input type="text" name="last_name" required>
            </div>

            <div>
                <label>Login (choose from users):</label><br>
                <select name="user_id" required>
                    <option value="">-- Select User --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['login']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Profile Photo:</label><br>
                <input type="file" name="photo" accept="image/*">
            </div>

            <div>
                <label>Role:</label><br>
                <input type="text" name="role_in_lab">
            </div>

            <div>
                <label>Specialty:</label><br>
                <select name="specialty_id">
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label>Or add new specialty:</label><br>
                <input type="text" name="new_specialty" placeholder="New specialty name">
            </div>

            <div>
                <label>Team:</label><br>
                <select name="team_id">
                    <option value="">-- Select Team --</option>
                    <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Website:</label><br>
                <input type="url" name="website">
            </div>

            <div>
                <label>Bio:</label><br>
                <textarea name="bio" rows="5" cols="50"></textarea>
            </div>

            <br>
            <button type="submit">Create Member</button>
            <a href="<?= base('admin/member/index') ?>"><button type="button">Cancel</button></a>
        </form>
        </body>
        </html>
        <?php
    }
}