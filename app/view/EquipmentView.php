<?php

class EquipmentView{
    public function renderIndex(array $equipments): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Equipments</title>
        </head>
        <body>

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
                            <a href="/equipment/addReservation?id=<?= $equipment['id'] ?>">
                                <button>Add reservation</button>
                            </a>
                        </td>
                        <td>
                            <a href="/equipment/reportBreakdown?id=<?= $equipment['id'] ?>">
                                <button>Report breakdown</button>
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

    public function renderAddReservation(array $equipment): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Add reservation</title>
        </head>
        <body>

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
            <a href="/equipment">Cancel</a>
        </form>

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
        </head>
        <body>

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
            <a href="/equipment">Cancel</a>
        </form>

        </body>
        </html>
        <?php
    }
}