<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Model/EventModel.php';

echo "<pre>";
echo "=== EventModel Test Script ===\n\n";

$model = new EventModel();


echo "1) Creating event...\n";

$newEventId = $model->create([
    'name' => 'Test Event',
    'event_type_id' => 1,
    'event_date' => date('Y-m-d', strtotime('+7 days')),
    'description' => 'Event created by test script',
    'link' => 'https://example.com',
    'participation_requests' => 0,
    'participation_requests_json' => ['invited' => ['Test User']]
]);

echo "Inserted event ID: ";
var_dump($newEventId);

echo "\n2) Find event by ID...\n";
$event = $model->findById($newEventId);
var_dump($event);

echo "\n3) Get all events...\n";
$allEvents = $model->getAll();
echo "Total events count: " . count($allEvents) . "\n";

echo "\n4) Updating event...\n";
$updated = $model->update($newEventId, [
    'name' => 'Updated Test Event',
    'event_type_id' => 1,
    'event_date' => date('Y-m-d', strtotime('+10 days')),
    'description' => 'Updated description',
    'link' => 'https://example.org',
    'participation_requests' => 5,
    'participation_requests_json' => ['invited' => ['Test User','Another User']]
]);
echo "Update success: ";
var_dump($updated);

echo "\n5) Adding participant...\n";
$participantAdded = $model->addParticipant($newEventId, 1);
var_dump($participantAdded);

echo "\n6) Get participants...\n";
$participants = $model->getParticipants($newEventId);
var_dump($participants);

echo "\n7) Remove participant...\n";
$removed = $model->removeParticipant($newEventId, 1);
var_dump($removed);

echo "\n8) Deleting event...\n";
$deleted = $model->delete($newEventId);
var_dump($deleted);

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";