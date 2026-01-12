<?php

Class TeamView{
    public function renderIndex(array $teams):void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Teams</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Teams</h1>
        <a href="<?= base('admin/team/create') ?>">
            <button>Add Team</button>
        </a>

        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Leader</th>
                    <th>Domain</th>
                    <th>Description</th>
                    <th>Members</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($teams)): ?>
                <tr><td colspan="6">No teams found</td></tr>
            <?php else: ?>
                <?php foreach ($teams as $team): ?>
                    <tr>
                        <td><?= htmlspecialchars($team['name']) ?></td>
                        <td>
                            <?= htmlspecialchars($team['leader_last_name'] ?? '') ?>
                            <?= htmlspecialchars($team['leader_first_name'] ?? '') ?>
                        </td>
                        <td><?= htmlspecialchars($team['domain'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($team['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($team['members_list'] ?? '-') ?></td>
                        <td>
                            <a href="<?= base('admin/team/edit?id=' . (int)$team['id']) ?>">
                                <button>Edit</button>
                            </a>
                            <a href="<?= base('admin/team/delete?id=' . (int)$team['id']) ?>"
                               onclick="return confirm('Are you sure you want to delete this team?');">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        </body>
        </html>
        <?php
    }

    public function renderAddForm(array $members, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Team</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Team</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/team/store') ?>">

            <label>Team Name</label><br>
            <input type="text" name="name" required><br><br>

            <label>Domain</label><br>
            <input type="text" name="domain"><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"></textarea><br><br>

            <label>Leader</label><br>
            <select name="leader_member_id" id="leaderSelect" required>
                <option value="">-- Select Leader --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['last_name'].' '.$m['first_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <hr>
            <h3>Members</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['last_name'].' '.$m['first_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMember()">Add</button>
            <div id="members"></div>

            <br>
            <button type="submit">Save Team</button>
            <a href="<?= base('admin/team/index') ?>"><button type="button">Cancel</button></a>
        </form>

        <script>
            let memberOrder = 1;

            function addMember() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;
                const text = select.options[select.selectedIndex].text;
                const container = document.getElementById('members');
                const div = document.createElement('div');
                div.innerHTML = `
                    ${text}
                    <input type="hidden" name="members[]" value="${select.value}">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                select.remove(select.selectedIndex);
            }

            document.getElementById('leaderSelect').addEventListener('change', function() {
                const leaderId = this.value;
                const memberSelect = document.getElementById('memberSelect');
                for (let i = 0; i < memberSelect.options.length; i++) {
                    if (memberSelect.options[i].value === leaderId) {
                        memberSelect.remove(i);
                        break;
                    }
                }
            });
        </script>

        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $team, array $members, array $teamMembers, string $error = null): void {
        $currentMemberIds = [];
        foreach ($teamMembers as $m) {
            if (isset($m['member_id'])) {
                $currentMemberIds[] = $m['member_id'];
            }
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Team</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Team</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/team/update') ?>">
            <input type="hidden" name="id" value="<?= (int)$team['id'] ?>">

            <label>Team Name</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($team['name']) ?>" required><br><br>

            <label>Domain</label><br>
            <input type="text" name="domain" value="<?= htmlspecialchars($team['domain'] ?? '') ?>"><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($team['description'] ?? '') ?></textarea><br><br>

            <label>Leader</label><br>
            <select name="leader_member_id" id="leaderSelect" required>
                <option value="">-- Select Leader --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $m['id'] == $team['leader_member_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['last_name'].' '.$m['first_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <hr>
            <h3>Members</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <?php if (!in_array($m['id'], $currentMemberIds) && $m['id'] != $team['leader_member_id']): ?>
                        <option value="<?= $m['id'] ?>">
                            <?= htmlspecialchars($m['last_name'].' '.$m['first_name']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMember()">Add</button>
            <div id="members">
                <?php foreach ($teamMembers as $tm): ?>
                    <div>
                        <?= htmlspecialchars($tm['last_name'].' '.$tm['first_name']) ?>
                        <input type="hidden" name="members[]" value="<?= $tm['member_id'] ?>">
                        <button type="button" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <br>
            <button type="submit">Update Team</button>
            <a href="<?= base('admin/team/index') ?>"><button type="button">Cancel</button></a>
        </form>

        <script>
            function addMember() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;
                const text = select.options[select.selectedIndex].text;
                const container = document.getElementById('members');
                const div = document.createElement('div');
                div.innerHTML = `
                    ${text}
                    <input type="hidden" name="members[]" value="${select.value}">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                select.remove(select.selectedIndex);
            }

            document.getElementById('leaderSelect').addEventListener('change', function() {
                const leaderId = this.value;
                const memberSelect = document.getElementById('memberSelect');
                for (let i = 0; i < memberSelect.options.length; i++) {
                    if (memberSelect.options[i].value === leaderId) {
                        memberSelect.remove(i);
                        break;
                    }
                }
            });
        </script>

        </body>
        </html>
        <?php
    }
}