<?php
require_once __DIR__ . '/../Model/UserModel.php';

echo "=== UserModel Test Script ===\n\n";

$userModel = new UserModel();

try {
    echo "1) Creating user...\n";
    $userData = [
        'login'       => 'testuser',
        'email'       => 'testuser@example.com',
        'password'    => 'password123',
        'role_id'     => null,
        'permissions' => ['read', 'write'],
        'specialty_id'=> null,
        'status'      => 'active'
    ];
    $userId = $userModel->create($userData);
    echo "Inserted user ID: $userId\n\n";

    echo "2) Find user by ID...\n";
    $user = $userModel->findById($userId);
    var_dump($user);
    echo "\n";

    echo "3) Find user by login...\n";
    $userByLogin = $userModel->findByLogin('testuser');
    var_dump($userByLogin);
    echo "\n";

    echo "4) Get all users...\n";
    $allUsers = $userModel->getAll();
    echo "Total users: " . count($allUsers) . "\n\n";

    echo "5) Updating user...\n";
    $updateData = [
        'login'       => 'testuser_updated',
        'email'       => 'updated@example.com',
        'password'    => 'newpassword123',
        'permissions' => ['read', 'write', 'delete'],
        'status'      => 'inactive'
    ];
    $updateResult = $userModel->update($userId, $updateData);
    echo "Update success: ";
    var_dump($updateResult);
    echo "\n";

    echo "6) Authenticating user...\n";
    $authResult = $userModel->authenticate('testuser_updated', 'newpassword123');
    if ($authResult) {
        echo "Authentication successful.\n";
        var_dump($authResult);
    } else {
        echo "Authentication failed.\n";
    }
    echo "\n";

    echo "7) Updating permissions...\n";
    $userModel->setPermissions($userId, ['read']);
    $updatedUser = $userModel->findById($userId);
    var_dump($updatedUser['permissions']);
    echo "\n";

    echo "8) Deleting user...\n";
    $deleteResult = $userModel->delete($userId);
    echo "Delete success: ";
    var_dump($deleteResult);
    echo "\n";

    echo "=== TEST FINISHED ===\n";

} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
}
