<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/NewsModel.php';

echo "<pre>";
echo "=== NewsModel Test Script ===\n\n";

$model = new NewsModel();

echo "1) Creating news...\n";
$newsId = $model->create([
    'photo_url'   => 'https://example.com/photo.jpg',
    'title'       => 'Test News',
    'description' => 'This is a test news item',
    'published_at'=> date('Y-m-d H:i:s')
]);

echo "Inserted news ID: ";
var_dump($newsId);

echo "\n2) Find news by ID...\n";
$news = $model->findById($newsId);
var_dump($news);

echo "\n3) Get all news...\n";
$allNews = $model->getAll();
echo "Total news count: " . count($allNews) . "\n";

echo "\n4) Get latest news (limit 1)...\n";
$latest = $model->getLatest(1);
var_dump($latest);

echo "\n5) Updating news...\n";
$updated = $model->update($newsId, [
    'photo_url'   => 'https://example.com/updated_photo.jpg',
    'title'       => 'Updated Test News',
    'description' => 'Updated description for test news'
]);
echo "Update success: ";
var_dump($updated);

echo "\n6) Deleting news...\n";
$deleted = $model->delete($newsId);
var_dump($deleted);

echo "\n=== TEST FINISHED ===\n";
echo "</pre>";
