<?php
require_once "Db.php";

class MemberModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "
            SELECT *
            FROM members
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByLogin($login)
    {
        $sql = "
            SELECT *
            FROM members
            WHERE login = :login
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['login' => $login]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT *
            FROM members
            ORDER BY last_name ASC, first_name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSpecialties()
    {
        $sql = "
            SELECT *
            FROM specialties
            ORDER BY name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSpecialtyById($memberId)
    {
        $sql = "
            SELECT *
            FROM specialties, members
            WHERE members.id = :member_id
              AND members.specialty_id = specialties.id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['member_id' => $memberId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function getByTeam($teamId)
    {
        $sql = "
            SELECT *
            FROM members
            WHERE team_id = :team_id
            ORDER BY last_name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['team_id' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO members
            (photo_url, last_name, first_name, login, user_id, website, specialty_id, role_in_lab, team_id, bio)
            VALUES (:photo_url, :last_name, :first_name, :login, :user_id, :website, :specialty_id, :role_in_lab, :team_id, :bio)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'photo_url'     => $data['photo_url'] ?? null,
            'last_name'     => $data['last_name'],
            'first_name'    => $data['first_name'],
            'login'         => $data['login'],
            'user_id'       => $data['user_id'] ?? null,
            'website'       => $data['website'] ?? null,
            'specialty_id'  => $data['specialty_id'] ?? null,
            'role_in_lab'   => $data['role_in_lab'] ?? null,
            'team_id'       => $data['team_id'] ?? null,
            'bio'           => $data['bio'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function addSpeciality(string $name)
    {
        $sql = "INSERT INTO specialties (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE members
            SET photo_url = :photo_url,
                last_name = :last_name,
                first_name = :first_name,
                login = :login,
                user_id = :user_id,
                website = :website,
                specialty_id = :specialty_id,
                role_in_lab = :role_in_lab,
                team_id = :team_id,
                bio = :bio
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'            => $id,
            'photo_url'     => $data['photo_url'] ?? null,
            'last_name'     => $data['last_name'],
            'first_name'    => $data['first_name'],
            'login'         => $data['login'],
            'user_id'       => $data['user_id'] ?? null,
            'website'       => $data['website'] ?? null,
            'specialty_id'  => $data['specialty_id'] ?? null,
            'role_in_lab'   => $data['role_in_lab'] ?? null,
            'team_id'       => $data['team_id'] ?? null,
            'bio'           => $data['bio'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM members WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function assignToTeam($memberId, $teamId)
    {
        $sql = "
            UPDATE members
            SET team_id = :team_id
            WHERE id = :member_id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'member_id' => $memberId,
            'team_id'   => $teamId
        ]);
    }

    public function getPublications($memberId)
    {
        $sql = "
            SELECT p.*, pa.author_order, pa.affiliation
            FROM publications p
            INNER JOIN publication_authors pa ON p.id = pa.publication_id
            WHERE pa.member_id = :member_id
            ORDER BY p.date_published DESC, pa.author_order ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['member_id' => $memberId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjects($memberId)
    {
        $sql = "
            SELECT pr.*, pm.role_in_project
            FROM projects pr
            INNER JOIN project_members pm ON pr.id = pm.project_id
            WHERE pm.member_id = :member_id
            ORDER BY pr.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['member_id' => $memberId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}