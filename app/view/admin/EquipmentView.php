<?php

Class EquipmentView {
    public function renderIndex(array $equipments, array $reservations, array $reports, array $maintenances):void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin - Equipments</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Equipments</h1>
        <a href="<?= base('admin/equipment/add') ?>">
            <button>Add Equipment</button>
        </a>
        <div class="table-wrapper">
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>State</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Action</th>
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
                            <a href="<?= base('admin/equipment/edit?id=.' . $equipment['id'] . '') ?>">
                                <button>Edit</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
            <?php $this->renderReservationsTable($reservations); ?>
            <?php $this->renderReportsTable($reports); ?>
            <?php $this->renderMaintenancesTable($maintenances); ?>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderAddForm(array $states, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Equipment</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add Equipment</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/equipment/store') ?>">

            <label>Name</label><br>
            <input type="text" name="name" required><br><br>

            <label>Type</label><br>
            <input type="text" name="type"><br><br>

            <label>State</label><br>
            <select name="state_id">
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"></textarea><br><br>

            <label>Location</label><br>
            <input type="text" name="location"><br><br>

            <button type="submit">Save Equipment</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $equipment, array $states, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Equipment</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Equipment</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/equipment/update') ?>">
            <input type="hidden" name="id" value="<?= (int)$equipment['id'] ?>">

            <label>Name</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required><br><br>

            <label>Type</label><br>
            <input type="text" name="type" value="<?= htmlspecialchars($equipment['type'] ?? '') ?>"><br><br>

            <label>State</label><br>
            <select name="state_id">
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $s['id']==$equipment['state_id']?'selected':'' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($equipment['description'] ?? '') ?></textarea><br><br>

            <label>Location</label><br>
            <input type="text" name="location" value="<?= htmlspecialchars($equipment['location'] ?? '') ?>"><br><br>

            <button type="submit">Update Equipment</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
        </form>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderReservationsTable(array $reservations): void {
        ?>
        <h2>Reservations</h2>
        <div class="table-wrapper">
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Equipment Name</th>
                    <th>Member Login</th>
                    <th>Reserved From</th>
                    <th>Reserved To</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($reservations)): ?>
                <tr><td colspan="7">No reservations found</td></tr>
            <?php else: ?>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                        <td><?= htmlspecialchars($r['member_login'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['reserved_from']) ?></td>
                        <td><?= htmlspecialchars($r['reserved_to']) ?></td>
                        <td><?= htmlspecialchars($r['purpose'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['status']) ?></td>
                        <td>
                            <?php if (strtotime($r['reserved_from']) > time()): ?>
                                <a href="<?= base('admin/equipment/editReservation?id=' . (int)$r['id']) ?>">
                                    <button>Update</button>
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
        </div>
        <?php
    }

    public function renderReportsTable(array $reports): void {
        ?>
        <h2>Breakdown Reports</h2>
        <div class="table-wrapper">
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Equipment Name</th>
                    <th>Description</th>
                    <th>Reported At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($reports)): ?>
                <tr><td colspan="4">No breakdown reports found</td></tr>
            <?php else: ?>
                <?php foreach ($reports as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                        <td><?= htmlspecialchars($r['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['created_at'] ?? '-') ?></td>
                        <td>
                            <a href="<?= base('admin/equipment/scheduleMaintenance?id=' . (int)$r['id']) ?>">
                                <button>Schedule Maintenance</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
        <?php
    }

    public function renderMaintenancesTable(array $maintenances): void {
        ?>
        <h2>Scheduled Maintenances</h2>
        <div class="table-wrapper">
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Equipment Name</th>
                    <th>Description</th>
                    <th>Scheduled At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($maintenances)): ?>
                <tr><td colspan="4">No scheduled maintenances found</td></tr>
            <?php else: ?>
                <?php foreach ($maintenances as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['equipment_name']) ?></td>
                        <td><?= htmlspecialchars($m['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($m['scheduled_at']) ?></td>
                        <td>
                            <a href="<?= base('admin/equipment/editMaintenance?id=' . (int)$m['id']) ?>">
                                <button>Edit</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
        <?php
    }

    public function renderEditMaintenanceForm(array $maintenance, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Maintenance</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Maintenance for <?= htmlspecialchars($maintenance['equipment_name']) ?></h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/equipment/updateMaintenance') ?>">
            <input type="hidden" name="id" value="<?= (int)$maintenance['id'] ?>">

            <label>Scheduled At</label><br>
            <input type="datetime-local" name="scheduled_at"
                value="<?= $maintenance['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($maintenance['scheduled_at'])) : '' ?>"><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($maintenance['description'] ?? '') ?></textarea><br><br>

            <button type="submit">Update Maintenance</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderScheduleMaintenanceForm(array $maintenance, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Schedule Maintenance</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
            <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Schedule Maintenance for <?= htmlspecialchars($maintenance['equipment_name']) ?></h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="<?= base('admin/equipment/saveScheduledMaintenance') ?>">
            <input type="hidden" name="id" value="<?= (int)$maintenance['id'] ?>">

            <label>Scheduled At</label><br>
            <input type="datetime-local" name="scheduled_at"
                value="<?= $maintenance['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($maintenance['scheduled_at'])) : '' ?>"><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"></textarea><br><br>

            <button type="submit">Save Maintenance</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}