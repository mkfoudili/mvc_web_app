<?php
class PublicationView {
    public function renderIndex(array $publications): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Admin - Publications</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Publications</h1>
        <a href="<?= base('admin/publication/create') ?>">
            <button>Add Publication</button>
        </a>

        <?php if (empty($publications)): ?>
            <p>No publications.</p>
        <?php else: ?>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($publications as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['publication_type_id'] ?? '-') ?></td>
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
                        <td>
                            <a href="<?= base('admin/publication/edit?id=' . $p['id']) ?>">
                                <button>Update</button>
                            </a>
                            <a href="<?= base('admin/publication/delete?id=' . $p['id']) ?>" onclick="return confirm('Are you sure you want to delete this publication?');">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function addPublication(array $members, array $publicationTypes, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Publication (Admin)</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Publication</h1>

        <?php if ($error): ?>
            <div style="color:#b00;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base('admin/publication/store') ?>">
            <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>

            <label>Type</label>
            <select name="publication_type_id" required>
                <?php foreach ($publicationTypes as $type): ?>
                    <option value="<?= $type['id'] ?>">
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Date</label>
            <input type="date" name="date_published">

            <label>DOI</label>
            <input type="text" name="doi">

            <label>Link</label>
            <input type="url" name="url">

            <hr>

            <h3>Authors</h3>

            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMemberAuthor()">Add</button>

            

            <input type="text" id="externalAuthor" placeholder="External author name">
            <button type="button" onclick="addExternalAuthor()">Add</button>

            <div class="author-list" id="authors"></div>

            
            <button type="submit">Save Publication</button>
            <a href="<?= base('admin/publication/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>

        <script>
            let authorOrder = 1;

            function addMemberAuthor() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;
                const text = select.options[select.selectedIndex].text;
                addAuthorInput({ member_id: select.value, author_name: '', label: text });
                select.remove(select.selectedIndex);
                select.value = '';
            }

            function addExternalAuthor() {
                const name = document.getElementById('externalAuthor').value.trim();
                if (!name) return;
                addAuthorInput({ author_name: name, label: name });
                document.getElementById('externalAuthor').value = '';
            }

            function addAuthorInput(author) {
                const container = document.getElementById('authors');
                const div = document.createElement('div');
                div.className = 'author-item';
                div.innerHTML = `
                    ${author.label}
                    <input type="hidden" name="authors[${authorOrder}][member_id]" value="${author.member_id || ''}">
                    <input type="hidden" name="authors[${authorOrder}][author_name]" value="${author.author_name || ''}">
                    <input type="hidden" name="authors[${authorOrder}][author_order]" value="${authorOrder}">
                    <button type="button" onclick="removeAuthor(this, '${author.member_id || ''}', '${author.label}')">Remove</button>
                `;
                container.appendChild(div);
                authorOrder++;
            }

            function removeAuthor(button, memberId, label) {
                const div = button.parentElement;
                div.remove();
                if (memberId) {
                    const select = document.getElementById('memberSelect');
                    const option = document.createElement('option');
                    option.value = memberId;
                    option.text = label;
                    select.appendChild(option);
                }
            }
        </script>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $publication, array $members, array $publicationTypes, array $authors, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Publication</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Publication</h1>

        <?php if ($error): ?>
            <div style="color:#b00;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base('admin/publication/update') ?>" enctype="multipart/form-data">
            <div class="form-group">
            <input type="hidden" name="id" value="<?= htmlspecialchars($publication['id']) ?>">

            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($publication['title']) ?>" required>

            <label>Type</label>
            <select name="publication_type_id" required>
                <?php foreach ($publicationTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $type['id']==$publication['publication_type_id']?'selected':'' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Date</label>
            <input type="date" name="date_published" value="<?= htmlspecialchars($publication['date_published'] ?? '') ?>">

            <label>DOI</label>
            <input type="text" name="doi" value="<?= htmlspecialchars($publication['doi'] ?? '') ?>">

            <label>Link</label>
            <input type="url" name="url" value="<?= htmlspecialchars($publication['url'] ?? '') ?>">

            <hr>

            <h3>Authors</h3>

            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMemberAuthor()">Add</button>

            

            <input type="text" id="externalAuthor" placeholder="External author name">
            <button type="button" onclick="addExternalAuthor()">Add</button>

            <div class="author-list" id="authors"></div>

            
            <button type="submit">Update Publication</button>
            <a href="<?= base('admin/publication/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>

        <script>
            let authorOrder = 1;

            function addMemberAuthor() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;

                const text = select.options[select.selectedIndex].text;

                addAuthorInput({
                    member_id: select.value,
                    author_name: '',
                    label: text
                });
                select.remove(select.selectedIndex);
                select.value = '';
            }

            function addExternalAuthor() {
                const name = document.getElementById('externalAuthor').value.trim();
                if (!name) return;

                addAuthorInput({
                    author_name: name,
                    label: name
                });

                document.getElementById('externalAuthor').value = '';
            }

            function addAuthorInput(author) {
                const container = document.getElementById('authors');
                const div = document.createElement('div');
                div.className = 'author-item';

                div.innerHTML = `
                    ${author.label}
                    <input type="hidden" name="authors[${authorOrder}][member_id]" value="${author.member_id || ''}">
                    <input type="hidden" name="authors[${authorOrder}][author_name]" value="${author.author_name || ''}">
                    <input type="hidden" name="authors[${authorOrder}][author_order]" value="${authorOrder}">
                    <button type="button" onclick="removeAuthor(this, '${author.member_id || ''}', '${author.label}')">Remove</button>
                `;

                container.appendChild(div);
                authorOrder++;
            }

            function removeAuthor(button, memberId, label) {
                const div = button.parentElement;
                div.remove();

                if (memberId) {
                    const select = document.getElementById('memberSelect');
                    const option = document.createElement('option');
                    option.value = memberId;
                    option.text = label;
                    select.appendChild(option);
                }
            }

            <?php foreach ($authors as $a): ?>
                addAuthorInput({
                    member_id: "<?= $a['member_id'] ?? '' ?>",
                    author_name: "<?= htmlspecialchars($a['author_name'] ?? '') ?>",
                    label: "<?= htmlspecialchars($a['display_name'] ?? $a['author_name']) ?>"
                });
            <?php endforeach; ?>
        </script>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}