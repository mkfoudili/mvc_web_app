<?php
require_once __DIR__ . '/../model/PublicationModel.php';

echo "=== PublicationModel Test Script ===\n\n";

$publicationModel = new PublicationModel();

try {
    echo "1) Creating publication...\n";
    $publicationData = [
        'title'               => 'Test Publication',
        'team_id'             => 1,
        'publication_type_id' => 1,
        'date_published'      => date('Y-m-d'),
        'doi'                 => '10.1234/testdoi',
        'url'                 => 'http://example.com/publication',
        'pdf_url'             => 'http://example.com/publication.pdf',
        'description'         => 'This is a test publication created by the script'
    ];
    $publicationId = $publicationModel->create($publicationData);
    echo "Inserted publication ID: $publicationId\n\n";

    echo "2) Find publication by ID...\n";
    $publication = $publicationModel->findById($publicationId);
    var_dump($publication);
    echo "\n";

    echo "3) Get all publications...\n";
    $allPublications = $publicationModel->getAll();
    echo "Total publications: " . count($allPublications) . "\n\n";

    echo "4) Updating publication...\n";
    $updateData = [
        'title'               => 'Test Publication Updated',
        'team_id'             => 1,
        'publication_type_id' => 1,
        'date_published'      => date('Y-m-d'),
        'doi'                 => '10.1234/testdoi-updated',
        'url'                 => 'http://example.com/publication-updated',
        'pdf_url'             => 'http://example.com/publication-updated.pdf',
        'description'         => 'Updated description for test publication'
    ];
    $updateResult = $publicationModel->update($publicationId, $updateData);
    echo "Update success: ";
    var_dump($updateResult);
    echo "\n";

    echo "5) Adding an author...\n";
    $authorData = [
        'member_id'   => 1,
        'author_name' => 'John Doe',
        'author_order'=> 1,
        'affiliation' => 'Test Lab'
    ];
    $addAuthorResult = $publicationModel->addAuthor($publicationId, $authorData);
    echo "Add author success: ";
    var_dump($addAuthorResult);
    echo "\n";

    echo "6) Get authors...\n";
    $authors = $publicationModel->getAuthors($publicationId);
    var_dump($authors);
    echo "\n";

    echo "7) Removing the author...\n";
    if (!empty($authors)) {
        $authorId = $authors[0]['id'];
        $removeAuthorResult = $publicationModel->removeAuthor($publicationId, $authorId);
        echo "Remove author success: ";
        var_dump($removeAuthorResult);
        echo "\n";
    }

    echo "8) Deleting publication...\n";
    $deleteResult = $publicationModel->delete($publicationId);
    echo "Delete success: ";
    var_dump($deleteResult);
    echo "\n";

    echo "=== TEST FINISHED ===\n";

} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
}
