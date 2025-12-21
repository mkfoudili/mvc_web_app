<?php
require_once "Db.php";

class AdminModel {

    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, login FROM admins WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function authenticate($login,$password) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE login = :login");
        $stmt->execute(['login' => $login]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            return [
                'id'    => $admin['id'],
                'login' => $admin['login']
            ];
        }
        return null;
    }
}