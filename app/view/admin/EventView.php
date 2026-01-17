<?php
require_once __DIR__ . '/../../helpers/components.php';
Class EventView {
    public function renderIndex(array $events): void {
        $pageTitle = '<h1>Events</h1>';
        $eventsTableHtml = $this->renderEventsTable($events);
        $requestsTableHtml = $this->renderRequests($events);

        $pageHtml = $pageTitle . '
            <a href="' . e(base('admin/event/add')) . '">
                <button>Add Event</button>
            </a>' . $eventsTableHtml .  $requestsTableHtml;

        layout('base', [
            'title'   => 'Admin - Events',
            'content' => $pageHtml
        ]);

        if (!empty($_SESSION['alert'])) {
            echo '<script>alert("' . e($_SESSION['alert']) . '");</script>';
            unset($_SESSION['alert']);
        }
    }

    private function renderEventsTable(array $events): string {
        if (empty($events)) {
            return '<p>No events found.</p>';
        }

        $headers = ['Name', 'Type', 'Date', 'Description', 'Participants', 'Action'];

        $rows = [];
        foreach ($events as $event) {
            $participantsHtml = '';
            if (!empty($event['participants'])) {
                $participantsHtml .= '<ul>';
                foreach ($event['participants'] as $p) {
                    $participantsHtml .= '<li>' . e($p['last_name'] . ' ' . $p['first_name']) . '</li>';
                }
                $participantsHtml .= '</ul>';
            } else {
                $participantsHtml = 'No participants';
            }

            $rows[] = [
                ['type' => 'text', 'value' => $event['name']],
                ['type' => 'text', 'value' => $event['event_type_name'] ?? '-'],
                ['type' => 'text', 'value' => $event['event_date'] ?? '-'],
                ['type' => 'text', 'value' => $event['description'] ?? '-'],
                ['type' => 'raw',  'html'  => $participantsHtml],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/event/edit?id=' . $event['id'] . '&return=/admin/event/index')) . '">
                            <button>Edit</button>
                         </a>
                         <a href="' . e(base('admin/event/cancel?id=' . $event['id'] . '&return=/admin/event/index')) . '">
                            <button>Request Cancellation</button>
                         </a>
                         <a href="' . e(base('admin/event/delete?id=' . $event['id'] . '&return=/admin/event/index')) . '">
                            <button>Delete</button>
                         </a>'
                ]
            ];
        }

        return component('Table',
        ['headers' => $headers,
                'rows' => $rows
                ]);
    }

    public function renderRequests(array $events): string {
        $requestsTableHtml = '<h2>Participation Requests</h2>';
        if (empty($events)) {
            $requestsTableHtml .= '<p>No participation requests</p>';
        }else{
            $headers = ['Name', 'Email', 'Message', 'Submitted At', 'Action'];
            $rows = [];
            foreach ($events as $event) {
                $requests = $event['requests'] ?? [];
                foreach ($requests as $req) {
                    $rows[] = [
                        ['type' => 'text', 'value' => $req['display_name']],
                        ['type' => 'text', 'value' => $req['email'] ?? '-'],
                        ['type' => 'text', 'value' => $req['message'] ?? '-'],
                        ['type' => 'text', 'value' => $req['submitted_at']],
                        [
                            'type' => 'raw',
                            'html' =>
                                '<a href="' . e(base('admin/event/acceptRequest?id=' . $req['id'])) . '">
                                    <button>Accept</button>
                                </a>'
                        ]
                    ];
                }
            }
            $requestsTableHtml .= component('Table',
                    ['headers' => $headers,
                    'rows' => $rows]);
        }
        return $requestsTableHtml;
    }


    public function renderAddForm(array $eventTypes,array $members,string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Add Event</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Event</h1>
        <?php if ($error): ?>
            <div style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base('admin/event/store') ?>">
            <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="event_type_id">Type:</label>
            <select id="event_type_id" name="event_type_id" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= $type['id'] ?>">
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="event_date">Date:</label>
            <input type="datetime-local" id="event_date" name="event_date">

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>

            <label for="link">Link:</label>
            <input type="url" id="link" name="link">

            <h3>Participants</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['last_name'].' '.$m['first_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addParticipant()">Add</button>
            <div id="participants"></div>

            <button type="submit">Save Event</button>
            <a href="<?= base('admin/event/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>
        <script>
            function addParticipant() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;
                const text = select.options[select.selectedIndex].text;
                const container = document.getElementById('participants');
                const div = document.createElement('div');
                div.innerHTML = `
                    ${text}
                    <input type="hidden" name="participants[]" value="${select.value}">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                select.remove(select.selectedIndex);
            }
        </script>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $event, array $eventTypes, array $members, array $participants, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Event</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Event</h1>
        <?php if ($error): ?>
            <div style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base('admin/event/update') ?>">
            <div class="form-group">
            <input type="hidden" name="id" value="<?= (int)$event['id'] ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($event['name']) ?>" required>

            <label for="event_type_id">Type:</label>
            <select id="event_type_id" name="event_type_id" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $type['id'] == $event['event_type_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="event_date">Date:</label>
            <input type="datetime-local" id="event_date" name="event_date"
                value="<?= $event['event_date'] ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : '' ?>">

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>

            <label for="link">Link:</label>
            <input type="url" id="link" name="link" value="<?= htmlspecialchars($event['link'] ?? '') ?>">

            <hr>
            <h3>Participants</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <?php
                    $already = array_column($participants, 'member_id');
                    if (in_array($m['id'], $already)) continue;
                    ?>
                    <option value="<?= $m['id'] ?>">
                        <?= htmlspecialchars($m['last_name'].' '.$m['first_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="addParticipant()">Add</button>
            <div id="participants">
                <?php foreach ($participants as $p): ?>
                    <div>
                        <?= htmlspecialchars($p['last_name'].' '.$p['first_name']) ?>
                        <input type="hidden" name="participants[]" value="<?= $p['member_id'] ?>">
                        <button type="button" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            
            <button type="submit">Update Event</button>
            <a href="<?= base('admin/event/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>

        <script>
            function addParticipant() {
                const select = document.getElementById('memberSelect');
                if (!select.value) return;
                const text = select.options[select.selectedIndex].text;
                const container = document.getElementById('participants');
                const div = document.createElement('div');
                div.innerHTML = `
                    ${text}
                    <input type="hidden" name="participants[]" value="${select.value}">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                container.appendChild(div);
                select.remove(select.selectedIndex);
            }
        </script>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

}