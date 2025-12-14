<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../model/EquipmentModel.php';

echo "<pre>";
echo "=== EquipementModel Test Script ===\n\n";

$model = new EquipementModel();


echo "1) Creating equipment...\n";

$newEquipmentId = $model->create([
    'name'        => 'Test Microscope',
    'type'        => 'Optical',
    'state_id'    => 1,
    'description' => 'Test equipment created by script',
    'location'    => 'Lab A'
]);

echo "Inserted equipment ID: ";
var_dump($newEquipmentId);


echo "\n2) Find equipment by ID...\n";

$equipment = $model->findById($newEquipmentId);
var_dump($equipment);


echo "\n3) Get all equipment...\n";

$allEquipment = $model->getAll();
echo "Total equipment count: " . count($allEquipment) . "\n";


echo "\n4) Updating equipment...\n";

$updated = $model->update($newEquipmentId, [
    'name'        => 'Updated Microscope',
    'type'        => 'Digital',
    'state_id'    => 2,
    'description' => 'Updated by test script',
    'location'    => 'Lab B'
]);

echo "Update success: ";
var_dump($updated);


echo "\n5) Adding reservation...\n";

$reservationAdded = $model->addReservation([
    'equipment_id'  => $newEquipmentId,
    'member_id'     => 1,
    'reserved_from' => date('Y-m-d'),
    'reserved_to'   => date('Y-m-d', strtotime('+2 days')),
    'purpose'       => 'Testing reservations'
]);

echo "Reservation added: ";
var_dump($reservationAdded);


echo "\n6) Getting reservations...\n";

$reservations = $model->getReservations($newEquipmentId);
var_dump($reservations);


if (!empty($reservations)) {
    echo "\n7) Cancelling first reservation...\n";
    $cancelled = $model->cancelReservation($reservations[0]['id']);
    var_dump($cancelled);
}


echo "\n8) Adding maintenance...\n";

$maintenanceAdded = $model->addMaintenance($newEquipmentId, [
    'scheduled_at' => date('Y-m-d', strtotime('+7 days')),
    'description'  => 'Routine maintenance'
]);

echo "Maintenance added: ";
var_dump($maintenanceAdded);


echo "\n9) Getting maintenance schedule...\n";

$maintenance = $model->getMaintenanceSchedule($newEquipmentId);
var_dump($maintenance);


echo "\n10) Setting equipment state...\n";

$stateUpdated = $model->setState($newEquipmentId, 3); // ensure state exists
var_dump($stateUpdated);


echo "\n11) Deleting equipment...\n";

$deleted = $model->delete($newEquipmentId);
var_dump($deleted);

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";
