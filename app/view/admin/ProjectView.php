<?php

Class ProjectView{
    public function renderIndex($projects){
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Projects</title>
        </head>
        <body>
        <h1>Projects</h1>
        <a href="/admin/project/create">
            <button>Add Project</button>
        </a>
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Leader</th>
                    <th>Theme</th>
                    <th>Funding</th>
                    <th>Project Page</th>
                    <th>Poster</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td>
                        <a href="/admin/project/show?id=<?= (int)$p['id'] ?>">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                    </td>
                    <td>
                        <?= htmlspecialchars($p['leader_first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars($p['leader_last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td><?= htmlspecialchars($p['theme'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($p['funding_type_id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if (!empty($p['project_page_url'])): ?>
                            <a href="<?= htmlspecialchars($p['project_page_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Link</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($p['poster_url'])): ?>
                            <a href="<?= htmlspecialchars($p['poster_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Poster</a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <button disabled="disabled">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </body>
        </html>
        <?php
    }

    public function renderShow(array $project, array $members, array $partners): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?= htmlspecialchars($project['title']) ?></title>
        </head>
        <body>

        <h1><?= htmlspecialchars($project['title']) ?></h1>

        <p><strong>Leader:</strong>
            <?= htmlspecialchars($project['leader_first_name'] ?? '') ?>
            <?= htmlspecialchars($project['leader_last_name'] ?? '') ?>
        </p>

        <p><strong>Theme:</strong> <?= htmlspecialchars($project['theme'] ?? '-') ?></p>
        <p><strong>Funding:</strong> <?= htmlspecialchars($project['funding_type_id'] ?? '-') ?></p>

        <p><strong>Project Page:</strong>
            <?php if (!empty($project['project_page_url'])): ?>
                <a href="<?= htmlspecialchars($project['project_page_url']) ?>" target="_blank">Link</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </p>

        <p><strong>Poster:</strong>
            <?php if (!empty($project['poster_url'])): ?>
                <a href="<?= htmlspecialchars($project['poster_url']) ?>" target="_blank">Poster</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </p>

        <p><strong>Description:</strong><br>
            <?= nl2br(htmlspecialchars($project['description'] ?? '-')) ?>
        </p>

        <hr>

        <h3>Project Members</h3>
        <?php if (empty($members)): ?>
            <p>-</p>
        <?php else: ?>
            <ul>
                <?php foreach ($members as $m): ?>
                    <li>
                        <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?>
                        <?php if (!empty($m['role_in_project'])): ?>
                            (<?= htmlspecialchars($m['role_in_project']) ?>)
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h3>Project Partners</h3>
        <?php if (empty($partners)): ?>
            <p>-</p>
        <?php else: ?>
            <ul>
                <?php foreach ($partners as $p): ?>
                    <li><?= htmlspecialchars($p['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        </body>
        </html>
        <?php
    }

    public function renderAddForm(array $members, array $fundingTypes, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Project</title>
        </head>
        <body>

        <h1>Add Project</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="/admin/project/store">

            <label>Title</label><br>
            <input type="text" name="title" required><br><br>

            <label>Leader</label><br>
            <select name="leader_member_id" required>
                <option value="">-- Select Leader --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Theme</label><br>
            <input type="text" name="theme"><br><br>

            <label>Funding</label><br>
            <select name="funding_type_id">
                <option value="">-- Select Funding --</option>
                <?php foreach ($fundingTypes as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Project Page URL</label><br>
            <input type="url" name="project_page_url"><br><br>

            <label>Poster URL</label><br>
            <input type="url" name="poster_url"><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="5" cols="50"></textarea><br><br>

            <hr>
            <h3>Members</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMember()">Add</button>
            <div id="members"></div>

            <hr>
            <h3>Partners</h3>
            <input type="text" id="partnerName" placeholder="Partner name">
            <button type="button" onclick="addPartner()">Add</button>
            <div id="partners"></div>

            <br>
            <button type="submit">Save Project</button>
            <a href="/admin/project/index"><button type="button">Cancel</button></a>
        </form>

        <script>
            let memberOrder = 1;
            let partnerOrder = 1;

            function addMember() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;
                const text = select.options[select.selectedIndex].text;
                const container = document.getElementById('members');
                const div = document.createElement('div');
                div.innerHTML = `
                    ${text}
                    <input type="hidden" name="members[${memberOrder}][member_id]" value="${select.value}">
                    <input type="text" name="members[${memberOrder}][role_in_project]" placeholder="Role">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                memberOrder++;
                select.remove(select.selectedIndex);
            }

            function addPartner() {
                const name = document.getElementById('partnerName').value.trim();
                if (!name) return;
                const container = document.getElementById('partners');
                const div = document.createElement('div');
                div.innerHTML = `
                    ${name}
                    <input type="hidden" name="partners[${partnerOrder}][name]" value="${name}">
                    <input type="text" name="partners[${partnerOrder}][role_description]" placeholder="Role">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                partnerOrder++;
                document.getElementById('partnerName').value = '';
            }
        </script>

        </body>
        </html>
        <?php
    }
}