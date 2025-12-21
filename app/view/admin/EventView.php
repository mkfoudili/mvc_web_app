<?php

Class EventView {
    public function renderIndex(array $events):void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin - Events</title>
        </head>
        <body>

        <h1>Events</h1>
        <a href="/admin/event/add">
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
                            <a href="/admin/event/edit?id=<?= $event['id'] ?>&return=/admin/event/index">
                                <button>Edit</button>
                            </a> 
                            <a href="/admin/event/cancel?id=<?= $event['id'] ?>&return=/admin/event/index">
                                <button>Request Cancellation</button>
                            </a>
                            <a href="/admin/event/delete?id=<?= $event['id'] ?>&return=/admin/event/index">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
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
        </head>
        <body>
        <h1>Add Event</h1>
        <?php if ($error): ?>
            <div style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/event/store">
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
            <a href="/admin/event/index"><button type="button">Cancel</button></a>
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
        </body>
        </html>
        <?php
    }
}