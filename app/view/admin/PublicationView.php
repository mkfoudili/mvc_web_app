<?php
require_once __DIR__ . '/../../helpers/components.php';
class PublicationView {
    public function renderIndex(array $publications): void {
        $pageTitle = '<h1>Publications</h1>';
        $publicationsTableHtml = $this->renderPublicationsTable($publications);
        $addPublicationButtonHtml = $this-> renderAddPublicationButton() ;
        $pageHtml = $pageTitle . $addPublicationButtonHtml . $publicationsTableHtml;

        layout('base', [
            'title'   => 'Admin - Publications',
            'content' => $pageHtml
        ]);
    }
    private function renderAddPublicationButton():string{
        return component('Button',['type' => 'link',
                                'label' => 'Add Publication',
                                'href' => base('admin/publication/create')]);
    }

    private function renderPublicationsTable(array $publications): string {
        if (empty($publications)) {
            return '<p>No publications.</p>';
        }

        $headers = ['Title', 'Type', 'Authors', 'Date', 'DOI', 'Link', 'Action'];

        $rows = [];
        foreach ($publications as $p) {
            $rows[] = [
                ['type' => 'text', 'value' => $p['title']],
                ['type' => 'text', 'value' => $p['publication_type_name'] ?? '-'],
                ['type' => 'text', 'value' => $p['authors'] ?? '-'],
                ['type' => 'text', 'value' => $p['date_published'] ?? '-'],
                ['type' => 'text', 'value' => $p['doi'] ?? '-'],
                !empty($p['url'])
                    ? ['type' => 'link', 'href' => $p['url'], 'label' => 'Link']
                    : ['type' => 'text', 'value' => '-'],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/publication/edit?id=' . $p['id'])) . '">
                            <button>Update</button>
                         </a>
                         <a href="' . e(base('admin/publication/delete?id=' . $p['id'])) . '" 
                            onclick="return confirm(\'Are you sure you want to delete this publication?\');">
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