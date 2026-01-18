<?php
require_once __DIR__ . '/../../helpers/components.php';
class EquipmentView{
    public function renderIndex(array $equipments): void
    {
        $pageTitle = '<h1>Equipments</h1>';
        $equipmentsTableHtml = $this->renderEquipmentsTable($equipments);
        $pageHtml = $pageTitle . $equipmentsTableHtml;

        layout('base', [
            'title'   => 'Equipments',
            'content' => $pageHtml
        ]);
    }
    private function renderEquipmentsTable(array $equipments): string
    {
        $equipmentsTableHtml = '';
        if (empty($equipments)) {
            $equipmentsTableHtml = '<p>No equipment found.</p>';
        } else {
            $headers = ['Name','Type','State','Description','Location','Reservation','Breakdown'];

            $rows = [];
            foreach ($equipments as $equipment) {
                $rows[] = [
                    ['type' => 'text', 'value' => $equipment['name']],
                    ['type' => 'text', 'value' => $equipment['type'] ?? '-'],
                    ['type' => 'text', 'value' => $equipment['state_name'] ?? '-'],
                    ['type' => 'text', 'value' => $equipment['description'] ?? '-'],
                    ['type' => 'text', 'value' => $equipment['location'] ?? '-'],
                    [
                        'type'  => 'button',
                        'label' => 'Add reservation',
                        'attrs' => [
                            'onclick' => "window.location='" 
                                . e(base('equipment/addReservation?id=' . $equipment['id'])) 
                                . "'"
                        ]
                    ],
                    [
                        'type'  => 'button',
                        'label' => 'Report breakdown',
                        'attrs' => [
                            'onclick' => "window.location='" 
                                . e(base('equipment/reportBreakdown?id=' . $equipment['id'])) 
                                . "'"
                        ]
                    ]
                ];
            }
            $equipmentsTableHtml = component('Table', [
                'headers' => $headers,
                'rows'    => $rows
            ]);
        }
        return $equipmentsTableHtml;
    }
    public function renderAddReservation(array $equipment): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Add reservation</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Add reservation</h1>

        <p>
            Equipment: <strong><?= htmlspecialchars($equipment['name']) ?></strong>
        </p>

        <form method="post">
            <div class="form-group">
            <label>
                From:
                <input
                    type="datetime-local"
                    name="reserved_from"
                    value="<?= date('Y-m-d\TH:i') ?>"
                    required
                >
            </label>
            

            <label>
                To:
                <input
                    type="datetime-local"
                    name="reserved_to"
                    required
                >
            </label>
            

            <label>
                Purpose (optional):
                
                <textarea name="purpose" rows="3" cols="40"></textarea>
            </label>
            

            <button type="submit">Save reservation</button>
            <a href="<?= base('equipment') ?>">Cancel</a>
            </div>
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
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Report Breakdown</h1>

        <p>
            Equipment: <strong><?= htmlspecialchars($equipment['name']) ?></strong>
        </p>

        <form method="post">
            <div class="form-group">
            <label>
                Description of the breakdown:
                
                <textarea name="description" rows="4" cols="50" required></textarea>
            </label>
            

            <button type="submit">Report</button>
            <a href="<?= base('equipment') ?>">Cancel</a>
            </div>
        </form>
            <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderReservations(array $reservations): string {
        if (empty($reservations)) {
            return '<p>No reservations found</p>';
        }

        $headers = [
            'Equipment',
            'Type',
            'State',
            'Location',
            'Reserved From',
            'Reserved To',
            'Purpose',
            'Status',
            'Action'
        ];

        $rows = [];
        foreach ($reservations as $r) {
            $rows[] = [
                ['type' => 'text', 'value' => $r['equipment_name']],
                ['type' => 'text', 'value' => $r['equipment_type'] ?? '-'],
                ['type' => 'text', 'value' => $r['equipment_state'] ?? '-'],
                ['type' => 'text', 'value' => $r['equipment_location'] ?? '-'],
                ['type' => 'text', 'value' => $r['reserved_from']],
                ['type' => 'text', 'value' => $r['reserved_to']],
                ['type' => 'text', 'value' => $r['purpose'] ?? '-'],
                ['type' => 'text', 'value' => $r['status'] ?? '-'],
                ($r['status'] === 'confirmed')
                    ? [
                        'type' => 'raw',
                        'html' =>
                            '<form method="post" action="' . e(base('equipment/cancel')) . '" style="display:inline;">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="' . (int)$r['id'] . '">
                                    <button type="submit">Cancel</button>
                                </div>
                            </form>'
                    ]
                    : ['type' => 'text', 'value' => '-']
            ];
        }

        return component('Table', [
            'headers' => $headers,
            'rows'    => $rows
        ]);
    }

}