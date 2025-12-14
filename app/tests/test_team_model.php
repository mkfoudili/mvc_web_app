<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../model/TeamModel.php';

echo "<pre>";
echo "=== TeamModel Test Script ===\n\n";

$model = new TeamModel();

$leaderId = 1;

$teamId = null;

try {
    echo "1) Creating team...\n";
    $teamId = $model->create([
        'name'             => 'Test Team',
        'leader_member_id' => $leaderId,
        'domain'           => 'Research',
        'description'      => 'Team created for testing'
    ]);

    echo "Inserted team ID: ";
    var_dump($teamId);

    echo "\n2) Find team by ID...\n";
    $team = $model->findById($teamId);
    var_dump($team);

    echo "\n3) Get all teams...\n";
    $allTeams = $model->getAll();
    echo "Total teams: " . count($allTeams) . "\n";

    echo "\n4) Updating team...\n";
    $updated = $model->update($teamId, [
        'name'             => 'Test Team',
        'leader_member_id' => $leaderId,
        'domain'           => 'Updated Research',
        'description'      => 'Updated description for testing'
    ]);
    echo "Update success: ";
    var_dump($updated);

    echo "\n5) Get members of team...\n";
    $members = $model->getMembers($teamId);
    echo "Number of members in team: " . count($members) . "\n";

    echo "\n6) Set leader of team...\n";
    $setLeader = $model->setLeader($teamId, $leaderId);
    var_dump($setLeader);

    echo "\n7) Get publications of team...\n";
    $publications = $model->getPublications($teamId);
    echo "Number of publications: " . count($publications) . "\n";

} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
} finally {
    if ($teamId) {
        echo "\n8) Deleting team...\n";
        $deleted = $model->delete($teamId);
        var_dump($deleted);
    }
}

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";