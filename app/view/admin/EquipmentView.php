<?php
require_once __DIR__ . '/../../helpers/components.php';
Class EquipmentView {
    public function renderIndex(array $equipments, array $reservations, array $reports, array $maintenances): void {
        $pageTitle = '<h1>Equipments</h1>';
        $addEquipmentButtonHtml = $this->renderAddEquipmentButton();
        $equipmentsTableHtml   = $this->renderEquipmentsTable($equipments);
        $reservationsTableHtml = $this->renderReservationsTable($reservations);
        $reportsTableHtml      = $this->renderReportsTable($reports);
        $maintenancesTableHtml = $this->renderMaintenancesTable($maintenances);

        $pageHtml = $pageTitle
            . $addEquipmentButtonHtml
            . $equipmentsTableHtml
            . $reservationsTableHtml
            . $reportsTableHtml
            . $maintenancesTableHtml;

        layout('base', [
            'title'   => 'Admin - Equipments',
            'content' => $pageHtml
        ]);
    }

    private function renderAddEquipmentButton():string{
        return component('Button',['type' => 'link',
                                'label' => 'Add Equipment',
                                'href' => base('admin/equipment/add')]);
    }

    private function renderEquipmentsTable(array $equipments): string {
        if (empty($equipments)) {
            return '<p>No equipment found.</p>';
        }

        $headers = ['Name', 'Type', 'State', 'Description', 'Location', 'Action'];

        $rows = [];
        foreach ($equipments as $equipment) {
            $rows[] = [
                ['type' => 'text', 'value' => $equipment['name']],
                ['type' => 'text', 'value' => $equipment['type'] ?? '-'],
                ['type' => 'text', 'value' => $equipment['state_name'] ?? '-'],
                ['type' => 'text', 'value' => $equipment['description'] ?? '-'],
                ['type' => 'text', 'value' => $equipment['location'] ?? '-'],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/equipment/edit?id=' . $equipment['id'])) . '">
                            <button>Edit</button>
                         </a>'
                ]
            ];
        }

        return component('Table', ['headers' => $headers, 'rows' => $rows]);
    }

    private function renderReservationsTable(array $reservations): string {
        $html = '<h2>Reservations</h2>';

        if (empty($reservations)) {
            return $html . '<p>No reservations found.</p>';
        }

        $headers = ['Equipment Name', 'Member Login', 'Reserved From', 'Reserved To', 'Purpose', 'Status', 'Action'];

        $rows = [];
        foreach ($reservations as $r) {
            $rows[] = [
                ['type' => 'text', 'value' => $r['equipment_name']],
                ['type' => 'text', 'value' => $r['member_login'] ?? '-'],
                ['type' => 'text', 'value' => $r['reserved_from']],
                ['type' => 'text', 'value' => $r['reserved_to']],
                ['type' => 'text', 'value' => $r['purpose'] ?? '-'],
                ['type' => 'text', 'value' => $r['status']],
                (strtotime($r['reserved_from']) > time())
                    ? [
                        'type' => 'raw',
                        'html' =>
                            '<a href="' . e(base('admin/equipment/editReservation?id=' . (int)$r['id'])) . '">
                                <button>Update</button>
                             </a>'
                    ]
                    : ['type' => 'text', 'value' => '-']
            ];
        }

        return $html . component('Table', ['headers' => $headers, 'rows' => $rows]);
    }

    private function renderReportsTable(array $reports): string {
        $html = '<h2>Breakdown Reports</h2>';

        if (empty($reports)) {
            return $html . '<p>No breakdown reports found.</p>';
        }

        $headers = ['Equipment Name', 'Description', 'Reported At', 'Action'];

        $rows = [];
        foreach ($reports as $r) {
            $rows[] = [
                ['type' => 'text', 'value' => $r['equipment_name']],
                ['type' => 'text', 'value' => $r['description'] ?? '-'],
                ['type' => 'text', 'value' => $r['created_at'] ?? '-'],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/equipment/scheduleMaintenance?id=' . (int)$r['id'])) . '">
                            <button>Schedule Maintenance</button>
                         </a>'
                ]
            ];
        }

        return $html . component('Table', ['headers' => $headers, 'rows' => $rows]);
    }

    private function renderMaintenancesTable(array $maintenances): string {
        $html = '<h2>Scheduled Maintenances</h2>';

        if (empty($maintenances)) {
            return $html . '<p>No scheduled maintenances found.</p>';
        }

        $headers = ['Equipment Name', 'Description', 'Scheduled At', 'Action'];

        $rows = [];
        foreach ($maintenances as $m) {
            $rows[] = [
                ['type' => 'text', 'value' => $m['equipment_name']],
                ['type' => 'text', 'value' => $m['description'] ?? '-'],
                ['type' => 'text', 'value' => $m['scheduled_at']],
                [
                    'type' => 'raw',
                    'html' =>
                        '<a href="' . e(base('admin/equipment/editMaintenance?id=' . (int)$m['id'])) . '">
                            <button>Edit</button>
                         </a>'
                ]
            ];
        }

        return $html . component('Table', ['headers' => $headers, 'rows' => $rows]);
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
            <div class="form-group">

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Type</label>
            <input type="text" name="type">

            <label>State</label>
            <select name="state_id">
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Description</label>
            <textarea name="description" rows="4" cols="50"></textarea>

            <label>Location</label>
            <input type="text" name="location">

            <button type="submit">Save Equipment</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
            </div>
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
            <div class="form-group">
            <input type="hidden" name="id" value="<?= (int)$equipment['id'] ?>">

            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required>

            <label>Type</label>
            <input type="text" name="type" value="<?= htmlspecialchars($equipment['type'] ?? '') ?>">

            <label>State</label>
            <select name="state_id">
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $s['id']==$equipment['state_id']?'selected':'' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Description</label>
            <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($equipment['description'] ?? '') ?></textarea>

            <label>Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($equipment['location'] ?? '') ?>">

            <button type="submit">Update Equipment</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
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
            <div class="form-group">
            <input type="hidden" name="id" value="<?= (int)$maintenance['id'] ?>">

            <label>Scheduled At</label>
            <input type="datetime-local" name="scheduled_at"
                value="<?= $maintenance['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($maintenance['scheduled_at'])) : '' ?>">

            <label>Description</label>
            <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($maintenance['description'] ?? '') ?></textarea>

            <button type="submit">Update Maintenance</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
            </div>
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
            <div class="form-group">
            <input type="hidden" name="id" value="<?= (int)$maintenance['id'] ?>">

            <label>Scheduled At</label>
            <input type="datetime-local" name="scheduled_at"
                value="<?= $maintenance['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($maintenance['scheduled_at'])) : '' ?>">

            <label>Description</label>
            <textarea name="description" rows="4" cols="50"></textarea>

            <button type="submit">Save Maintenance</button>
            <a href="<?= base('admin/equipment/index') ?>"><button type="button">Cancel</button></a>
            </div>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}