<?php
require_once "Db.php";

class UserModel {
    public function findById($id){}
    public function getAll(){}
    public function findByLogin($login){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function authenticate($login, $password){}
    public function getRole($userId){}
    public function setPermissions($userId, $permissions){}
}