<?php

class EquipmentView{
    public function renderIndex(array $equipments): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Equipments</title>
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Equipments</h1>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>State</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Reservation</th>
                    <th>Breakdown</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($equipments)): ?>
                <tr>
                    <td colspan="7">No equipment found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($equipments as $equipment): ?>
                    <tr>
                        <td><?= htmlspecialchars($equipment['name']) ?></td>
                        <td><?= htmlspecialchars($equipment['type'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($equipment['state_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($equipment['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($equipment['location'] ?? '-') ?></td>
                        <td>
                            <a href="<?= base('equipment/addReservation?id=' . $equipment['id']) ?>">
                                <button>Add reservation</button>
                            </a>
                        </td>
                        <td>
                            <a href="<?= base('equipment/reportBreakdown?id=' . $equipment['id']) ?>">
                                <button>Report breakdown</button>
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

    public function renderAddReservation(array $equipment): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Add reservation</title>
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add reservation</h1>

        <p>
            Equipment: <strong><?= htmlspecialchars($equipment['name']) ?></strong>
        </p>

        <form method="post">
            <label>
                From:
                <input
                    type="datetime-local"
                    name="reserved_from"
                    value="<?= date('Y-m-d\TH:i') ?>"
                    required
                >
            </label>
            <br><br>

            <label>
                To:
                <input
                    type="datetime-local"
                    name="reserved_to"
                    required
                >
            </label>
            <br><br>

            <label>
                Purpose (optional):
                <br>
                <textarea name="purpose" rows="3" cols="40"></textarea>
            </label>
            <br><br>

            <button type="submit">Save reservation</button>
            <a href="<?= base('equipment') ?>">Cancel</a>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderReportBreakdown(array $equipment): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Report Breakdown</title>
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Report Breakdown</h1>

        <p>
            Equipment: <strong><?= htmlspecialchars($equipment['name']) ?></strong>
        </p>

        <form method="post">
            <label>
                Description of the breakdown:
                <br>
                <textarea name="description" rows="4" cols="50" required></textarea>
            </label>
            <br><br>

            <button type="submit">Report</button>
            <a href="<?= base('equipment') ?>">Cancel</a>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderReservations(array $reservations): void
    {
        ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Equipment</th>
                    <th>Type</th>
                    <th>State</th>
                    <th>Location</th>
                    <th>Reserved From</th>
                    <th>Reserved To</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>َAction</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="8">No reservations found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                        <td><?= htmlspecialchars($r['equipment_type'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['equipment_state'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['equipment_location'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['reserved_from']) ?></td>
                        <td><?= htmlspecialchars($r['reserved_to']) ?></td>
                        <td><?= htmlspecialchars($r['purpose'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['status'] ?? '-') ?></td>
                        <td>
                        <?php if ($r['status'] === 'confirmed'): ?>
                            <form method="post" action="<?= base('equipment/cancel') ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit">Cancel</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
}