<?php
require_once "Db.php";

class TeamModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "
            SELECT t.*, m.first_name AS leader_first_name, m.last_name AS leader_last_name
            FROM teams t
            LEFT JOIN members m ON t.leader_member_id = m.id
            WHERE t.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT t.*, m.first_name AS leader_first_name, m.last_name AS leader_last_name
            FROM teams t
            LEFT JOIN members m ON t.leader_member_id = m.id
            ORDER BY t.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO teams (name, leader_member_id, domain, description)
            VALUES (:name, :leader_member_id, :domain, :description)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'name'             => $data['name'],
            'leader_member_id' => $data['leader_member_id'] ?? null,
            'domain'           => $data['domain'] ?? null,
            'description'      => $data['description'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE teams
            SET name = :name,
                leader_member_id = :leader_member_id,
                domain = :domain,
                description = :description
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'               => $id,
            'name'             => $data['name'],
            'leader_member_id' => $data['leader_member_id'] ?? null,
            'domain'           => $data['domain'] ?? null,
            'description'      => $data['description'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM teams WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getMembers($teamId)
    {
        $sql = "
            SELECT tm.*, m.first_name, m.last_name, m.role_in_lab, m.photo_url
            FROM team_members tm
            INNER JOIN members m ON tm.member_id = m.id
            WHERE tm.team_id = :team_id
            ORDER BY tm.joined_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['team_id' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setLeader($teamId, $leaderId)
    {
        $sql = "
            UPDATE teams
            SET leader_member_id = :leader_id
            WHERE id = :team_id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'leader_id' => $leaderId,
            'team_id'   => $teamId
        ]);
    }

    public function getPublications($teamId)
    {
        $sql = "
            SELECT p.*
            FROM publications p
            WHERE p.team_id = :team_id
            ORDER BY p.date_published DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['team_id' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}