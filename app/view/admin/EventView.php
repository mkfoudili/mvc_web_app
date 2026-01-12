<?php

Class EventView {
    public function renderIndex(array $events):void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin - Events</title>
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Events</h1>
        <a href="<?= base('admin/event/add') ?>">
            <button>Add Event</button>
        </a>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Participants</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($events)): ?>
                <tr>
                    <td colspan="6">No events found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['name']) ?></td>
                        <td><?= htmlspecialchars($event['event_type_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($event['event_date'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($event['description'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($event['participants'])): ?>
                                <ul>
                                    <?php foreach ($event['participants'] as $p): ?>
                                        <li><?= htmlspecialchars($p['last_name'].' '.$p['first_name']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                No participants
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base('admin/event/edit?id=' . $event['id'] . '&return=/admin/event/index') ?>">
                                <button>Edit</button>
                            </a> 
                            <a href="<?= base('admin/event/cancel?id=' . $event['id'] . '&return=/admin/event/index') ?>">
                                <button>Request Cancellation</button>
                            </a>
                            <a href="<?= base('admin/event/delete?id=' . $event['id'] . '&return=/admin/event/index') ?>">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderAddForm(array $eventTypes,array $members,string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Add Event</title>
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Event</h1>
        <?php if ($error): ?>
            <div style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base('admin/event/store') ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <select id="event_type_id" name="event_type_id" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= $type['id'] ?>">
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="event_date">Date:</label><br>
            <input type="datetime-local" id="event_date" name="event_date"><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>

            <label for="link">Link:</label><br>
            <input type="url" id="link" name="link"><br><br>

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
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Event</h1>
        <?php if ($error): ?>
            <div style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base('admin/event/update') ?>">
            <input type="hidden" name="id" value="<?= (int)$event['id'] ?>">

            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($event['name']) ?>" required><br><br>

            <label for="event_type_id">Type:</label><br>
            <select id="event_type_id" name="event_type_id" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $type['id'] == $event['event_type_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="event_date">Date:</label><br>
            <input type="datetime-local" id="event_date" name="event_date"
                value="<?= $event['event_date'] ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : '' ?>"><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?= htmlspecialchars($event['description'] ?? '') ?></textarea><br><br>

            <label for="link">Link:</label><br>
            <input type="url" id="link" name="link" value="<?= htmlspecialchars($event['link'] ?? '') ?>"><br><br>

            <hr>
            <h3>Participants</h3>
            <select id="memberSelect">
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                    <?php
                    $already = array_column($participants, 'member_id');
                    if (in_array($m['id'], $already)) continue; // skip already selected
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

            <br>
            <button type="submit">Update Event</button>
            <a href="<?= base('admin/event/index') ?>"><button type="button">Cancel</button></a>
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