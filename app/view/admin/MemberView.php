<?php
require_once __DIR__ . '/../../helpers/components.php';
Class MemberView{
    public function renderIndex(array $members): void
    {
        $pageTitle = '<h1>Members</h1>';
        $membersTableHtml = $this->renderMembersTable($members);
        $addMemberButton = '<a href="' . base('admin/member/addForm') . '">
                            <button>Add Member</button>
                            </a>';
        $pageHtml = $pageTitle . $addMemberButton . $membersTableHtml;

        layout('base', [
            'title'   => 'Admin - Members',
            'content' => $pageHtml
        ]);
    }
    public function renderMembersTable(array $members): string {
        $membersListHtml = '';
        if (empty($members)) {
            $membersListHtml = '<p>No members found.</p>';
        } else {
        $headers = ['Name', 'Login', 'Specialty', 'Role', 'Action'];

        $rows = [];
        foreach ($members as $member) {
            $rows[] = [
                [
                    'type'  => 'link',
                    'href'  => base('admin/member/show?id=' . $member['id']),
                    'label' => $member['last_name'] . ' ' . $member['first_name']
                ],
                ['type' => 'text', 'value' => $member['login']],
                ['type' => 'text', 'value' => $member['specialty_name'] ?? '-'],
                ['type' => 'text', 'value' => $member['role_in_lab'] ?? '-'],
                ['type' => 'raw','html' =>
                '<a href="' . e(base('admin/member/edit?id=' . $member['id'])) . '">
                    <button>Update</button>
                 </a>
                 <a href="' . e(base('admin/member/delete?id=' . $member['id'])) . '" 
                    onclick="return confirm(\'Are you sure you want to delete this member?\');">
                    <button>Delete</button>
                 </a>']
            ];
        }

        $membersListHtml = component('Table', [
            'headers' => $headers,
            'rows'    => $rows
        ]);
        }
        return $membersListHtml;
    }

    public function renderMembersList(array $members):void{
        ?>
        <div class="table-wrapper">
        <table border="1" cellpadding="5" cellspacing="0" class="sortable-table">
            <thead>
                <tr>
                    <th>Name</th>
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
                        <td>
                            <a href="<?= base('admin/member/show?id=' . $member['id']) ?>">
                                <?= htmlspecialchars($member['last_name'] . ' ' . $member['first_name']) ?>
                            </a>
                        </td>
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
        </div>
        <?php
    }
    public function renderShow(array $member, array $publications, array $projects): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
            <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
            <h1><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></h1>

            <p>Email: <?= htmlspecialchars($member['email'] ?? '-') ?></p>
            <p>Specialty: <?= htmlspecialchars($member['specialty_name'] ?? '-') ?></p>
            <p>Team: <?= htmlspecialchars($member['team_name'] ?? '-') ?></p>

            <h2>Publications</h2>
            <?php $this->renderPublications($publications); ?>

            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderPublications(array $publications): void{
        ?>
        <div class="table-wrapper">
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
            <?php if (empty($publications)): ?>
                <tr>
                    <td colspan="6">No publications</td>
                </tr>
            <?php else: ?>
                <?php foreach ($publications as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($pub['publication_type_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
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
            <?php endif; ?>
            </tbody>
        </table>
        </div>
        <?php
    }

    public function renderEditForm(array $member, array $specialties, array $teams): void {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Edit Member</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Member</h1>

        <form method="post" action="<?= base('admin/member/update') ?>" enctype="multipart/form-data">
            <div class="form-group">
            <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">
            <input type="hidden" name="login" value="<?= htmlspecialchars($member['login']) ?>">

            <div>
                <label>First Name:</label>
                <input type="text" name="first_name"
                       value="<?= htmlspecialchars($member['first_name']) ?>" required>
            </div>

            <div>
                <label>Last Name:</label>
                <input type="text" name="last_name"
                       value="<?= htmlspecialchars($member['last_name']) ?>" required>
            </div>

            <div>
                <label>Profile Photo:</label>
                <input type="file" name="photo" accept="image/*">
                <?php if (!empty($member['photo_url'])): ?>
                    
                    <img src="<?= htmlspecialchars($member['photo_url']) ?>" alt="Current photo" width="120">
                    
                    <button type="submit" name="delete_photo" value="1">Delete Photo</button>
                <?php endif; ?>
            </div>

            <div>
                <label>Role:</label>
                <input type="text" name="role_in_lab"
                       value="<?= htmlspecialchars($member['role_in_lab'] ?? '') ?>">
            </div>

            <div>
                <label>Specialty:</label>
                <select name="specialty_id">
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= ($member['specialty_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label>Or add new specialty:</label>
                <input type="text" name="new_specialty" placeholder="New specialty name">
            </div>

            <div>
                <label>Team:</label>
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
                <label>Website:</label>
                <input type="url" name="website"
                       value="<?= htmlspecialchars($member['website'] ?? '') ?>">
            </div>

            <div>
                <label>Bio:</label>
                <textarea name="bio" rows="5" cols="50"><?= htmlspecialchars($member['bio'] ?? '') ?></textarea>
            </div>

            
            <button type="submit">Save Changes</button>
            <a href="<?= base('admin/member/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
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
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Member</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base('admin/member/create') ?>" enctype="multipart/form-data">
            <div class="form-group">
            <div>
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>

            <div>
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>

            <div>
                <label>Login (choose from users):</label>
                <select name="user_id" required>
                    <option value="">-- Select User --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['login']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Profile Photo:</label>
                <input type="file" name="photo" accept="image/*">
            </div>

            <div>
                <label>Role:</label>
                <input type="text" name="role_in_lab">
            </div>

            <div>
                <label>Specialty:</label>
                <select name="specialty_id" required>
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label>Or add new specialty:</label>
                <input type="text" name="new_specialty" placeholder="New specialty name">
            </div>

            <div>
                <label>Team:</label>
                <select name="team_id" required>
                    <option value="">-- Select Team --</option>
                    <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Website:</label>
                <input type="url" name="website">
            </div>

            <div>
                <label>Bio:</label>
                <textarea name="bio" rows="5" cols="50"></textarea>
            </div>

            
            <button type="submit">Create Member</button>
            <a href="<?= base('admin/member/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        <script>
                function validateSpecialty() {
                    const select = document.getElementById('specialty_id');
                    const input  = document.getElementById('new_specialty');
                    if (!select.value && !input.value.trim()) {
                        alert("Please select a specialty or enter a new one.");
                        return false;
                    }
                    return true;
                }
            </script>
        </body>
        </html>
        <?php
    }
}