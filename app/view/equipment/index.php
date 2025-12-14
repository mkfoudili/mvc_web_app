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