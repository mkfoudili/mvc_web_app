<?php

class ProjectView {
    public function renderIndex(array $projects): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Projects</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Projects</h1>

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
                </tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td>
                        <a href="<?= base('project/show?id=' . (int)$p['id']) ?>">
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
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
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

        <p><strong>Description:</strong><br>
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

    public function renderCards(array $projects, int $currentPage, int $totalPages, string $baseurl, string $anchor): void{
        ?>
        <div class="card-grid">            
            <?php foreach ($projects as $p): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($p['title']) ?></h3>

                    <a href="<?= base('project/show?id=' . (int)$p['id']) ?>">
                        <button>View details</button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:20px;">
            <?php if ($currentPage > 1): ?>
                <a href="<?= $baseurl ?><?= $currentPage - 1 . $anchor ?>">Previous</a>
            <?php endif; ?>

            <span style="margin:0 10px;">
                Page <?= $currentPage ?> / <?= $totalPages ?>
            </span>

            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= $baseurl ?><?= $currentPage + 1 . $anchor ?>">Next</a>
            <?php endif; ?>
        </div>
        <?php
    }
    public function renderCreateForm(array $members, array $fundingTypes, $currentMemberId): void
    {
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

        <form method="post" action="<?= base('project/store') ?>">
            <input type="hidden" name="current_member_id" value="<?= htmlspecialchars($currentMemberId) ?>">

            <div>
                <label>Title:</label><br>
                <input type="text" name="title" required>
            </div>

            <div>
                <label>Leader:</label><br>
                <select name="leader_member_id" required>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= ($m['id'] == $currentMemberId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Theme:</label><br>
                <input type="text" name="theme">
            </div>

            <div>
                <label>Funding Type:</label><br>
                <select name="funding_type_id" required>
                    <?php foreach ($fundingTypes as $ft): ?>
                        <option value="<?= $ft['id'] ?>"><?= htmlspecialchars($ft['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Project Page URL:</label><br>
                <input type="url" name="project_page_url">
            </div>

            <div>
                <label>Poster URL:</label><br>
                <input type="url" name="poster_url">
            </div>

            <div>
                <label>Description:</label><br>
                <textarea name="description"></textarea>
            </div>

            <hr>

            <h3>Project Members</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addProjectMember()">Add</button>

            <div id="projectMembers"></div>

            <hr>

            <h3>Project Partners</h3>
            <input type="text" id="partnerName" placeholder="Partner name">
            <input type="text" id="partnerContact" placeholder="Contact info">
            <input type="text" id="partnerRole" placeholder="Role description">
            <button type="button" onclick="addProjectPartner()">Add</button>

            <div id="projectPartners"></div>

            <br>
            <button type="submit">Save Project</button>
            <a href="<?= base('member/index?id=' . htmlspecialchars($currentMemberId)) ?>">
                <button type="button">Cancel</button>
            </a>
        </form>

        <script>
            let memberOrder = 1;
            let partnerOrder = 1;

            function addProjectMember() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;

                const text = select.options[select.selectedIndex].text;

                const container = document.getElementById('projectMembers');
                const div = document.createElement('div');
                div.className = 'member-item';
                div.innerHTML = `
                    ${text}
                    <input type="hidden" name="members[${memberOrder}][member_id]" value="${select.value}">
                    <input type="hidden" name="members[${memberOrder}][role_in_project]" value="participant">
                `;
                container.appendChild(div);

                memberOrder++;
                select.remove(select.selectedIndex);
                select.value = '';
            }

            function addProjectPartner() {
                const name = document.getElementById('partnerName').value.trim();
                const contact = document.getElementById('partnerContact').value.trim();
                const role = document.getElementById('partnerRole').value.trim();
                if (!name) return;

                const container = document.getElementById('projectPartners');
                const div = document.createElement('div');
                div.className = 'partner-item';
                div.innerHTML = `
                    ${name} (${contact}, ${role})
                    <input type="hidden" name="partners[${partnerOrder}][name]" value="${name}">
                    <input type="hidden" name="partners[${partnerOrder}][contact_info]" value="${contact}">
                    <input type="hidden" name="partners[${partnerOrder}][role_description]" value="${role}">
                `;
                container.appendChild(div);

                partnerOrder++;
                document.getElementById('partnerName').value = '';
                document.getElementById('partnerContact').value = '';
                document.getElementById('partnerRole').value = '';
            }
        </script>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

}