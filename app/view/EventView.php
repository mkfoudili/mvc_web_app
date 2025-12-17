<?php

class EventView {
    public function renderIndex(array $events): void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Events</title>
        </head>
        <body>

        <h1>Events</h1>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($events)): ?>
                <tr>
                    <td colspan="7">No events found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['name']) ?></td>
                        <td><?= htmlspecialchars($event['event_type_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($event['event_date'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($event['description'] ?? '-') ?></td>
                        <td>
                            <?php if ($event['is_upcoming']): ?>
                            <a href="/event/joinForm?id=<?= $event['id'] ?>">
                                <button>Join</button>
                            </a> 
                            <?php else: ?>
                                -
                            <?php endif; ?>
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

    public function renderJoinForm(array $event): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Join Event</title>
        </head>
        <body>
        <h1>Join Event</h1>

        <p><strong>Event:</strong> <?= htmlspecialchars($event['name']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date'] ?? '-') ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($event['description'] ?? '-') ?></p>

        <form method="post" action="/event/joinEvent">
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

            <!-- ONLY DISPLAYS TO EXTERNAL MEMBERS NOT MEMBERS -->
            <input type="hidden" name="member_id" value="">

            <label>
                Your Name :
                <input type="text" name="name" required>
            </label>
            <br><br>

            <label>
                Your Email :
                <input type="email" name="email" required>
            </label>
            <br><br>

            <label>
                Message:
                <br>
                <textarea name="message" rows="4" cols="50" required></textarea>
            </label>
            <br><br>

            <button type="submit">Submit Request</button>
            <a href="/event"><button type="button">Cancel</button></a>
        </form>

        </body>
        </html>
        <?php
    }
}