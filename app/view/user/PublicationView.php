<?php

Class PublicationView {
    public function renderIndex(array $teams): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Publications</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Publications</h1>

        <?php foreach ($teams as $team): ?>
            <h2><?= htmlspecialchars($team['team_name']) ?></h2>

            <?php if (empty($team['publications'])): ?>
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
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($team['publications'] as $p): ?>
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
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        <?php endforeach; ?>

        </body>
        </html>
        <?php
    }

    public function addPublication(int $memberId, array $members, array $publicationTypes): void{   
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Publication</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Publication</h1>

        <form method="post" action="<?= base('publication/store') ?>">

            <input type="hidden" name="member_id" value="<?= $memberId ?>">

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

            <!-- Add member author -->
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <?php if ($m['id'] != $memberId): ?>
                        <option value="<?= $m['id'] ?>">
                            <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addMemberAuthor()">Add</button>

            <br><br>

            <!-- Add external author -->
            <input type="text" id="externalAuthor" placeholder="External author name">
            <button type="button" onclick="addExternalAuthor()">Add</button>

            <div class="author-list" id="authors"></div>

            <br>
            <button type="submit">Save Publication</button>
            
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
                    <input type="hidden" name="authors[${authorOrder}][member_id]" value="${author.member_id}">
                    <input type="hidden" name="authors[${authorOrder}][author_name]" value="${author.author_name}">
                    <input type="hidden" name="authors[${authorOrder}][author_order]" value="${authorOrder}">
                `;

                container.appendChild(div);
                authorOrder++;
            }

            addAuthorInput({
                member_id: "<?= $memberId ?>",
                author_name: "",
                label: "You"
            });
        </script>

        </body>
        </html>
        <?php   
    }
}