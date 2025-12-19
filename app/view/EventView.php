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
                            <a href="/event/joinForm?id=<?= $event['id'] ?>&return=/event/index">
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

    public function renderJoinForm(array $event, string $returnUrl): void
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
            <input type="hidden" name="return_url" value="<?= htmlspecialchars($returnUrl) ?>">
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
    
    public function renderMyEvents(array $events): void
    {
        ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Description</th>
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
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
    public function renderCards(array $events, int $currentPage, int $totalPages, string $baseurl, string $anchor): void {
        ?>
        <div style="display:flex; gap:20px; flex-wrap:wrap;">
            <?php foreach ($events as $event): ?>
                <div style="
                    border:1px solid #ccc;
                    padding:16px;
                    width:220px;
                ">
                    <h3><?= htmlspecialchars($event['name']) ?></h3>

                    <?php if (!empty($event['is_upcoming']) && $event['is_upcoming']): ?>
                        <a href="/event/joinForm?id=<?= $event['id'] ?>&return=/event/cards">
                        <button>Join</button>
                        </a>
                    <?php else: ?>
                        <button disabled>Closed</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:20px;">
            <?php if ($currentPage > 1): ?>
                <a href="<?= $baseurl ?><?= $currentPage - 1 . $anchor?>">Previous</a>
            <?php endif; ?>

            <span style="margin:0 10px;">
                Page <?= $currentPage ?> / <?= $totalPages ?>
            </span>

            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= $baseurl ?><?= $currentPage + 1 . $anchor?>">Next</a>
            <?php endif; ?>
        </div>
        <?php
    }
}