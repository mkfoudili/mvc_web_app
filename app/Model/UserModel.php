<?php
require_once "Db.php";

class UserModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM users ORDER BY login ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSpecialties()
    {
        $sql = "SELECT * FROM specialties ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoles()
    {
        $sql = "SELECT * FROM roles ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByLogin($login)
    {
        $sql = "SELECT * FROM users WHERE login = :login";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['login' => $login]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO users (login, email, password_hash, role_id, permissions, specialty_id, status)
            VALUES (:login, :email, :password_hash, :role_id, :permissions, :specialty_id, :status)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'login'         => $data['login'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id'       => $data['role_id'] ?? null,
            'permissions'   => json_encode($data['permissions'] ?? []),
            'specialty_id'  => $data['specialty_id'] ?? null,
            'status'        => $data['status'] ?? 'active'
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE users
            SET login = :login,
                email = :email,
                password_hash = :password_hash,
                role_id = :role_id,
                permissions = :permissions,
                specialty_id = :specialty_id,
                status = :status
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        $passwordHash = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : $data['password_hash'];

        return $stmt->execute([
            'id'            => $id,
            'login'         => $data['login'],
            'email'         => $data['email'],
            'password_hash' => $passwordHash,
            'role_id'       => $data['role_id'] ?? null,
            'permissions'   => json_encode($data['permissions'] ?? []),
            'specialty_id'  => $data['specialty_id'] ?? null,
            'status'        => $data['status'] ?? 'active'
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function authenticate($login, $password)
    {
        $user = $this->findByLogin($login);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function getRole($userId)
    {
        $sql = "
            SELECT r.*
            FROM roles r
            INNER JOIN users u ON u.role_id = r.id
            WHERE u.id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setPermissions($userId, $permissions)
    {
        $sql = "UPDATE users SET permissions = :permissions WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id'     => $userId,
            'permissions' => json_encode($permissions)
        ]);
    }

    public function createRole(array $data)
    {
        $sql = "INSERT INTO roles (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $data['name']]);
        return $this->db->lastInsertId();
    }

    public function createSpecialty(array $data)
    {
        $sql = "INSERT INTO specialties (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $data['name']]);
        return $this->db->lastInsertId();
    }
}