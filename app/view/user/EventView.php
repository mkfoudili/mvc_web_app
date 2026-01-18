<?php
require_once __DIR__ . '/../../helpers/components.php';
class EventView {
    public function renderIndex(array $events): void{
        $pageTitle = '<h1>Events</h1>';
        $eventsTableHtml = $this->renderEventsTable($events);
        $pageHtml = $pageTitle . $eventsTableHtml;

        layout('base', [
            'title'   => 'Events',
            'content' => $pageHtml
        ]);
    }

    public function renderEventsTable(array $events): string{
        $eventsTableHtml = '';
        if (empty($events)) {
            $eventsTableHtml = '<p>No projects found.</p>';
        }else {
            $headers = ['Name', 'Type', 'Date', 'Description', 'Action'];

            $rows = [];
            foreach ($events as $event) {
            $rows[] = [
                ['type' => 'text', 'value' => $event['name']],
                ['type' => 'text', 'value' => $event['event_type_name'] ?? '-'],
                ['type' => 'text', 'value' => $event['event_date'] ?? '-'],
                ['type' => 'text', 'value' => $event['description'] ?? '-'],
                $event['is_upcoming'] ?
                [ 'type' => 'button', 'label' => 'Join',
                'attrs' => [ 'onclick' => "window.location='" . e(base('event/joinForm?id=' . $event['id'] . '&return=/event/index')) . "'" ] ] : ['type' => 'text', 'value' => '-']
            ];
            }
            $eventsTableHtml = component('Table', [
                'headers' => $headers,
                'rows'    => $rows
                ]);
        }
        return $eventsTableHtml;
    }
    public function renderJoinForm(array $event, string $returnUrl, int $memberId): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Join Event</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Join Event</h1>

        <p><strong>Event:</strong> <?= htmlspecialchars($event['name']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date'] ?? '-') ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($event['description'] ?? '-') ?></p>

        <form method="post" action="<?= base('event/joinEvent') ?>">
            <div class="form-group">
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
            <input type="hidden" name="return_url" value="<?= htmlspecialchars($returnUrl) ?>">
            <?php if ($memberId): ?>
                <input type="hidden" name="member_id" value="<?= (int)$memberId ?>">
                <label>
                    Message:
                    
                    <textarea name="message" rows="4" cols="50" required></textarea>
                </label>
                <?php else: ?>
                    <input type="hidden" name="member_id" value="">
                    <label>
                        Your Name:
                        <input type="text" name="name" required>
                    </label>
                    
                    <label>
                        Your Email:
                        <input type="email" name="email" required>
                    </label>
                    
                    <label>
                        Message:
                        
                        <textarea name="message" rows="4" cols="50" required></textarea>
                    </label>
                <?php endif; ?>

            <button type="submit">Submit Request</button>
            <a href="<?= base('event') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
    
    public function renderMyEvents(array $events): string {
        if (empty($events)) {
            return '<p>No events found</p>';
        }

        $headers = ['Name', 'Type', 'Date', 'Description'];

        $rows = [];
        foreach ($events as $event) {
            $rows[] = [
                ['type' => 'text', 'value' => $event['name']],
                ['type' => 'text', 'value' => $event['event_type_name'] ?? '-'],
                ['type' => 'text', 'value' => $event['event_date'] ?? '-'],
                ['type' => 'text', 'value' => $event['description'] ?? '-']
            ];
        }

        return component('Table', [
            'headers' => $headers,
            'rows'    => $rows
        ]);
    }
    public function renderCards(array $events, int $currentPage, int $totalPages, string $baseurl, string $anchor): void {
        ?>
        <div class="card-grid">
            <?php foreach ($events as $event): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($event['name']) ?></h3>

                    <?php if (!empty($event['is_upcoming']) && $event['is_upcoming']): ?>
                        <a href="<?= base('event/joinForm?id=' . $event['id'] . '&return=/event/index') ?>">
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