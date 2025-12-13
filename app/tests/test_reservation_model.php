<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Model/ReservationModel.php';

echo "<pre>";
echo "=== ReservationtionModel Test Script ===\n\n";

$model = new ReservationtionModel();

$equipmentId = 1;
$memberId    = 1;

echo "1) Creating reservation...\n";
$reservationId = $model->create([
    'equipment_id'   => $equipmentId,
    'member_id'      => $memberId,
    'reserved_from'  => date('Y-m-d H:i:s'),
    'reserved_to'    => date('Y-m-d H:i:s', strtotime('+2 days')),
    'purpose'        => 'Testing reservation script'
]);

echo "Inserted reservation ID: ";
var_dump($reservationId);

echo "\n2) Find reservation by ID...\n";
$reservation = $model->findById($reservationId);
var_dump($reservation);

echo "\n3) Get all reservations...\n";
$allReservations = $model->getAll();
echo "Total reservations: " . count($allReservations) . "\n";

echo "\n4) Get reservations by equipment...\n";
$byEquipment = $model->getByEquipment($equipmentId);
echo "Reservations for equipment $equipmentId: " . count($byEquipment) . "\n";

echo "\n5) Get reservations by member...\n";
$byMember = $model->getByMember($memberId);
echo "Reservations for member $memberId: " . count($byMember) . "\n";

echo "\n6) Cancel reservation...\n";
$cancelled = $model->cancel($reservationId);
var_dump($cancelled);

echo "\n7) Verify cancelled reservation...\n";
$cancelledReservation = $model->findById($reservationId);
var_dump($cancelledReservation['status']);

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";
