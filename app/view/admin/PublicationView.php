<?php
class PublicationView {
    public function renderIndex(array $publications): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Admin - Publications</title>
        </head>
        <body>

        <h1>Publications</h1>
        <a href="/admin/publication/create">
            <button>Add Publication</button>
        </a>

        <?php if (empty($publications)): ?>
            <p>No publications.</p>
        <?php else: ?>
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
                            <a href="/admin/publication/edit?id=<?= $p['id'] ?>">
                                <button>Update</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

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
        </head>
        <body>

        <h1>Add Publication</h1>

        <?php if ($error): ?>
            <div style="color:#b00;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/admin/publication/store">

            <label>Title</label><br>
            <input type="text" name="title" required><br><br>

            <label>Type</label><br>
            <select name="publication_type_id" required>
                <?php foreach ($publicationTypes as $type): ?>
                    <option value="<?= $type['id'] ?>">
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Date</label><br>
            <input type="date" name="date_published"><br><br>

            <label>DOI</label><br>
            <input type="text" name="doi"><br><br>

            <label>Link</label><br>
            <input type="url" name="url"><br><br>

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

            <br><br>

            <input type="text" id="externalAuthor" placeholder="External author name">
            <button type="button" onclick="addExternalAuthor()">Add</button>

            <div class="author-list" id="authors"></div>

            <br>
            <button type="submit">Save Publication</button>
            <a href="/admin/publication/index"><button type="button">Cancel</button></a>
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
        </head>
        <body>

        <h1>Edit Publication</h1>

        <?php if ($error): ?>
            <div style="color:#b00;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/admin/publication/update" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($publication['id']) ?>">

            <label>Title</label><br>
            <input type="text" name="title" value="<?= htmlspecialchars($publication['title']) ?>" required><br><br>

            <label>Type</label><br>
            <select name="publication_type_id" required>
                <?php foreach ($publicationTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $type['id']==$publication['publication_type_id']?'selected':'' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Date</label><br>
            <input type="date" name="date_published" value="<?= htmlspecialchars($publication['date_published'] ?? '') ?>"><br><br>

            <label>DOI</label><br>
            <input type="text" name="doi" value="<?= htmlspecialchars($publication['doi'] ?? '') ?>"><br><br>

            <label>Link</label><br>
            <input type="url" name="url" value="<?= htmlspecialchars($publication['url'] ?? '') ?>"><br><br>

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

            <br><br>

            <input type="text" id="externalAuthor" placeholder="External author name">
            <button type="button" onclick="addExternalAuthor()">Add</button>

            <div class="author-list" id="authors"></div>

            <br>
            <button type="submit">Update Publication</button>
            <a href="/admin/publication/index"><button type="button">Cancel</button></a>
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

        </body>
        </html>
        <?php
    }
}