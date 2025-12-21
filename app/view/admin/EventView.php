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
}