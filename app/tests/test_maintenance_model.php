<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/MaintenanceModel.php';

echo "<pre>";
echo "=== MaintenanceModel Test Script ===\n\n";

$maintenanceModel = new MaintenanceModel();

$equipmentId = 1;

echo "1) Creating maintenance record...\n";
$maintenanceId = $maintenanceModel->create([
    'equipment_id' => $equipmentId,
    'scheduled_at' => date('Y-m-d H:i:s', strtotime('+3 days')),
    'description'  => 'Initial maintenance check'
]);

echo "Inserted maintenance ID: ";
var_dump($maintenanceId);

echo "\n2) Find maintenance by ID...\n";
$maintenance = $maintenanceModel->findById($maintenanceId);
var_dump($maintenance);

echo "\n3) Get all maintenance records...\n";
$allMaintenance = $maintenanceModel->getAll();
echo "Total maintenance records: " . count($allMaintenance) . "\n";

echo "\n4) Get maintenance by equipment...\n";
$byEquipment = $maintenanceModel->getByEquipment($equipmentId);
echo "Maintenance records for equipment $equipmentId: " . count($byEquipment) . "\n";

echo "\n5) Updating maintenance...\n";
$updated = $maintenanceModel->update($maintenanceId, [
    'scheduled_at' => date('Y-m-d H:i:s', strtotime('+5 days')),
    'description'  => 'Updated maintenance schedule'
]);
echo "Update success: ";
var_dump($updated);

echo "\n6) Deleting maintenance record...\n";
$deleted = $maintenanceModel->delete($maintenanceId);
var_dump($deleted);

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";