<?php
require_once __DIR__ . '/../../helpers/components.php';

Class PublicationView {
    public function renderIndex(array $teams): void
    {
        $pageTitle = '<h1>Publications</h1>';
        $publicationsListHtml = $this->renderPublicationsList($teams);
        $pageHtml = $pageTitle . $publicationsListHtml;
        layout('base', [
            'title'   => 'Publications',
            'content' => $pageHtml
        ]);
    }
    public function renderPublicationsList(array $teams): string
    {
        $publicationsListHtml = '';
        if (empty($teams)){
            $publicationsListHtml = '<p>No publications found.</p>';
        }
        else {
            foreach ($teams as $team){
                $publicationsListHtml .='<h2>'.e($team['team_name']).'</h2>';
                if(empty($team['publications'])){
                    $publicationsListHtml .='<p>No publications.</p>';
                }else{
                    $thead = ['Title','Type','Authors','Date','DOI','Link'];
                    $rows = [];
                    foreach ($team['publications'] as $p) {
                        $rows[] = [ ['type' => 'text', 'value' => $p['title']],
                        ['type' => 'text', 'value' => $p['publication_type_id'] ?? '-'],
                        ['type' => 'text', 'value' => $p['authors'] ?? '-'],
                        ['type' => 'text', 'value' => $p['date_published'] ?? '-'],
                        ['type' => 'text', 'value' => $p['doi'] ?? '-'],
                        !empty($p['url']) ? ['type' => 'link', 'href' => $p['url'], 'label' => 'Link'] : ['type' => 'text', 'value' => '-'] ];
                    }
                    $publicationsListHtml .= component('Table',
                    [
                        'headers' => $thead,
                        'rows' => $rows
                    ]
                    );
                }
            }
        }
        return $publicationsListHtml;
    }

    public function addPublication(int $memberId, array $members, array $publicationTypes): void{   
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Publication</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Publication</h1>

        <form method="post" action="<?= base('publication/store') ?>">
            <div class="form-group">
            <input type="hidden" name="member_id" value="<?= $memberId ?>">

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

            

            <!-- Add external author -->
            <input type="text" id="externalAuthor" placeholder="External author name">
            <button type="button" onclick="addExternalAuthor()">Add</button>

            <div class="author-list" id="authors"></div>

            
            <button type="submit">Save Publication</button>
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
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php   
    }
}