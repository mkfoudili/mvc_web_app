<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../model/ProjectModel.php';

echo "<pre>";
echo "=== ProjectModel Test Script ===\n\n";

$model = new ProjectModel();

$leaderId = 1;

$projectId = null;

try {

    echo "1) Creating project...\n";
    $projectId = $model->create([
        'title'            => 'Test Project',
        'leader_member_id' => $leaderId,
        'theme'            => 'AI Research',
        'funding_type_id'  => 1,
        'project_page_url' => 'http://example.com/project',
        'poster_url'       => 'http://example.com/poster.jpg',
        'description'      => 'Project created for testing'
    ]);
    echo "Inserted project ID: ";
    var_dump($projectId);

    echo "\n2) Find project by ID...\n";
    $project = $model->findById($projectId);
    var_dump($project);

    echo "\n3) Get all projects...\n";
    $allProjects = $model->getAll();
    echo "Total projects: " . count($allProjects) . "\n";

    echo "\n4) Updating project...\n";
    $updated = $model->update($projectId, [
        'title'            => 'Test Project',
        'leader_member_id' => $leaderId,
        'theme'            => 'Updated Theme',
        'funding_type_id'  => 2,
        'project_page_url' => 'http://example.com/updated',
        'poster_url'       => 'http://example.com/updated_poster.jpg',
        'description'      => 'Updated description for testing'
    ]);
    echo "Update success: ";
    var_dump($updated);

    echo "\n5) Get members...\n";
    $members = $model->getMembers($projectId);
    echo "Number of members: " . count($members) . "\n";

    echo "\n6) Add member...\n";
    $addMember = $model->addMember($projectId, $leaderId);
    var_dump($addMember);

    echo "\n7) Remove member...\n";
    $removeMember = $model->removeMember($projectId, $leaderId);
    var_dump($removeMember);

    echo "\n8) Get partners...\n";
    $partners = $model->getPartners($projectId);
    echo "Number of partners: " . count($partners) . "\n";

    echo "\n9) Add partner...\n";
    $addPartner = $model->addPartner($projectId, [
        'name' => 'Test Partner',
        'contact_info' => 'partner@example.com',
        'role_description' => 'Supporting AI Research'
    ]);
    var_dump($addPartner);

    echo "\n10) Remove partner...\n";
    $partners = $model->getPartners($projectId);
    if (count($partners) > 0) {
        $partnerId = $partners[0]['id'];
        $removePartner = $model->removePartner($partnerId);
        var_dump($removePartner);
    }

} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
} finally {
    if ($projectId) {
        echo "\n11) Deleting project...\n";
        $deleted = $model->delete($projectId);
        var_dump($deleted);
    }
}

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";