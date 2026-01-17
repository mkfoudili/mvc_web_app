<?php
require_once __DIR__ . '/../../helpers/components.php';
Class ProjectView{
        public function renderIndex(array $projects): void {
        $pageTitle = '<h1>Projects</h1>';
        $projectsTableHtml = $this->renderProjectsTable($projects);
        $pageHtml = $pageTitle . '
            <a href="' . e(base('admin/project/create')) . '">
                <button>Add Project</button>
            </a>' . $projectsTableHtml;

        layout('base', [
            'title'   => 'Admin - Projects',
            'content' => $pageHtml
        ]);
    }

    private function renderProjectsTable(array $projects): string {
        if (empty($projects)) {
            return '<p>No projects found.</p>';
        }

        $headers = [
            'Title',
            'Leader',
            'Theme',
            'Funding',
            'Project Page',
            'Poster',
            'Description',
            'Action'
        ];

        $rows = [];
        foreach ($projects as $p) {
            $rows[] = [
                [
                    'type'  => 'link',
                    'href'  => base('admin/project/show?id=' . (int)$p['id']),
                    'label' => $p['title']
                ],
                [
                    'type'  => 'text',
                    'value' => $p['leader_first_name'] ?? '' . ' ' . $p['leader_last_name'] ?? ''
                ],
                ['type' => 'text', 'value' => $p['theme'] ?? '-'],
                ['type' => 'text', 'value' => $p['funding_type_name'] ?? '-'],
                !empty($p['project_page_url'])
                    ? ['type' => 'link', 'href' => $p['project_page_url'], 'label' => 'Link']
                    : ['type' => 'text', 'value' => '-'],
                !empty($p['poster_url'])
                    ? ['type' => 'link', 'href' => $p['poster_url'], 'label' => 'Poster']
                    : ['type' => 'text', 'value' => '-'],
                ['type' => 'text', 'value' => $p['description'] ?? '-'],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/project/edit?id=' . $p['id'])) . '">
                            <button>Edit</button>
                         </a>
                         <a href="' . e(base('admin/project/delete?id=' . (int)$p['id'])) . '" 
                            onclick="return confirm(\'Are you sure you want to delete this project?\');">
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

    public function renderShow(array $project, array $members, array $partners): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?= htmlspecialchars($project['title']) ?></title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <div class="container stack">
        <h1><?= htmlspecialchars($project['title']) ?></h1>

        <div class="section-block-list">
            <div class="section-block">
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

            <p><strong>Description:</strong>
                <?= nl2br(htmlspecialchars($project['description'] ?? '-')) ?>
            </p>
            </div>

            <div class="section-block">
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
            </div>

            <div class="section-block">
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
            </div>
        </div>
        </div>

        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
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
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Project</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/project/store') ?>">
            <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>

            <label>Leader</label>
            <select name="leader_member_id" required>
                <option value="">-- Select Leader --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Theme</label>
            <input type="text" name="theme">

            <label>Funding</label>
            <select name="funding_type_id">
                <option value="">-- Select Funding --</option>
                <?php foreach ($fundingTypes as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Project Page URL</label>
            <input type="url" name="project_page_url">

            <label>Poster URL</label>
            <input type="url" name="poster_url">

            <label>Description</label>
            <textarea name="description" rows="5" cols="50"></textarea>

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

            
            <button type="submit">Save Project</button>
            <a href="<?= base('admin/project/index') ?>"><button type="button">Cancel</button></a>
            </div>
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
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $project, array $members, array $fundingTypes, array $currentMembers, array $currentPartners, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Project</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Project</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/project/update') ?>">
            <div class="form-group">
            <input type="hidden" name="id" value="<?= (int)$project['id'] ?>">

            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>

            <label>Leader</label>
            <select name="leader_member_id" required>
                <option value="">-- Select Leader --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $m['id']==$project['leader_member_id']?'selected':'' ?>>
                        <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Theme</label>
            <input type="text" name="theme" value="<?= htmlspecialchars($project['theme'] ?? '') ?>">

            <label>Funding</label>
            <select name="funding_type_id">
                <option value="">-- Select Funding --</option>
                <?php foreach ($fundingTypes as $f): ?>
                    <option value="<?= $f['id'] ?>" <?= $f['id']==$project['funding_type_id']?'selected':'' ?>>
                        <?= htmlspecialchars($f['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Project Page URL</label>
            <input type="url" name="project_page_url" value="<?= htmlspecialchars($project['project_page_url'] ?? '') ?>">

            <label>Poster URL</label>
            <input type="url" name="poster_url" value="<?= htmlspecialchars($project['poster_url'] ?? '') ?>">

            <label>Description</label>
            <textarea name="description" rows="5" cols="50"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>

            <hr>
            <h3>Members</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <?php if (!in_array($m['id'], array_column($currentMembers, 'member_id'))): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMember()">Add</button>
            <div id="members"></div>

            <hr>
            <h3>Partners</h3>
            <input type="text" id="partnerName" placeholder="Partner name">
            <button type="button" onclick="addPartner()">Add</button>
            <div id="partners"></div>

            
            <button type="submit">Update Project</button>
            <a href="<?= base('admin/project/index') ?>"><button type="button">Cancel</button></a>
            </div>
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

            <?php foreach ($currentMembers as $m): ?>
                addMember();
                document.getElementById('members').lastChild.querySelector('input[name*="[member_id]"]').value = "<?= $m['member_id'] ?>";
                document.getElementById('members').lastChild.querySelector('input[name*="[role_in_project]"]').value = "<?= htmlspecialchars($m['role_in_project'] ?? '') ?>";
                document.getElementById('members').lastChild.firstChild.textContent = "<?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>";
            <?php endforeach; ?>

            <?php foreach ($currentPartners as $p): ?>
                const container = document.getElementById('partners');
                const div = document.createElement('div');
                div.innerHTML = `
                    <?= htmlspecialchars($p['name']) ?>
                    <input type="hidden" name="partners[${partnerOrder}][name]" value="<?= htmlspecialchars($p['name']) ?>">
                    <input type="text" name="partners[${partnerOrder}][role_description]" value="<?= htmlspecialchars($p['role_description'] ?? '') ?>">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                partnerOrder++;
            <?php endforeach; ?>
        </script>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}