<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/MemberModel.php';

echo "<pre>";
echo "=== MemberModel Test Script ===\n\n";

$model = new MemberModel();

$memberId = null;

$teamId = 1;

try {
    echo "1) Creating member...\n";
    $memberId = $model->create([
        'photo_url'    => 'http://example.com/photo.jpg',
        'last_name'    => 'Doe',
        'first_name'   => 'John',
        'login'        => 'test_member',
        'user_id'      => 1,
        'website'      => 'http://example.com',
        'specialty_id' => 1,
        'role_in_lab'  => 'Researcher',
        'team_id'      => $teamId,
        'bio'          => 'Created for testing purposes'
    ]);
    echo "Inserted member ID: ";
    var_dump($memberId);

    echo "\n2) Find member by ID...\n";
    $member = $model->findById($memberId);
    var_dump($member);

    echo "\n3) Find member by login...\n";
    $memberLogin = $model->findByLogin('test_member');
    var_dump($memberLogin);

    echo "\n4) Get all members...\n";
    $allMembers = $model->getAll();
    echo "Total members: " . count($allMembers) . "\n";

    echo "\n5) Updating member...\n";
    $updated = $model->update($memberId, [
        'photo_url'    => 'http://example.com/photo_updated.jpg',
        'last_name'    => 'Doe',
        'first_name'   => 'Johnny',
        'login'        => 'test_member',
        'user_id'      => 123,
        'website'      => 'http://example.com/updated',
        'specialty_id' => 2,
        'role_in_lab'  => 'Senior Researcher',
        'team_id'      => $teamId,
        'bio'          => 'Updated bio for testing'
    ]);
    var_dump($updated);

    echo "\n6) Assign member to team...\n";
    $assigned = $model->assignToTeam($memberId, $teamId);
    var_dump($assigned);

    echo "\n7) Get member projects...\n";
    $projects = $model->getProjects($memberId);
    echo "Projects count: " . count($projects) . "\n";

    echo "\n8) Get member publications...\n";
    $publications = $model->getPublications($memberId);
    echo "Publications count: " . count($publications) . "\n";

} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
} finally {
    if ($memberId) {
        echo "\n9) Deleting member...\n";
        $deleted = $model->delete($memberId);
        var_dump($deleted);
    }
}

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";
