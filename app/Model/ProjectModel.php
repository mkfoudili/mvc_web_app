<?php
require_once "Db.php";

class ProjectModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "
            SELECT p.*, m.first_name AS leader_first_name, m.last_name AS leader_last_name
            FROM projects p
            LEFT JOIN members m ON p.leader_member_id = m.id
            WHERE p.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT p.*, m.first_name AS leader_first_name, m.last_name AS leader_last_name
            FROM projects p
            LEFT JOIN members m ON p.leader_member_id = m.id
            ORDER BY p.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO projects
            (title, leader_member_id, theme, funding_type_id, project_page_url, poster_url, description)
            VALUES (:title, :leader_member_id, :theme, :funding_type_id, :project_page_url, :poster_url, :description)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'title'             => $data['title'],
            'leader_member_id'  => $data['leader_member_id'] ?? null,
            'theme'             => $data['theme'] ?? null,
            'funding_type_id'   => $data['funding_type_id'] ?? null,
            'project_page_url'  => $data['project_page_url'] ?? null,
            'poster_url'        => $data['poster_url'] ?? null,
            'description'       => $data['description'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE projects
            SET title = :title,
                leader_member_id = :leader_member_id,
                theme = :theme,
                funding_type_id = :funding_type_id,
                project_page_url = :project_page_url,
                poster_url = :poster_url,
                description = :description
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'                => $id,
            'title'             => $data['title'],
            'leader_member_id'  => $data['leader_member_id'] ?? null,
            'theme'             => $data['theme'] ?? null,
            'funding_type_id'   => $data['funding_type_id'] ?? null,
            'project_page_url'  => $data['project_page_url'] ?? null,
            'poster_url'        => $data['poster_url'] ?? null,
            'description'       => $data['description'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function getMembers($projectId)
    {
        $sql = "
            SELECT pm.*, m.first_name, m.last_name, m.role_in_lab, m.photo_url
            FROM project_members pm
            LEFT JOIN members m ON pm.member_id = m.id
            WHERE pm.project_id = :project_id
            ORDER BY pm.joined_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMember($projectId, $memberId)
    {
        $sql = "
            INSERT INTO project_members (project_id, member_id, role_in_project)
            VALUES (:project_id, :member_id, 'participant')
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'project_id' => $projectId,
            'member_id'  => $memberId
        ]);
    }

    public function removeMember($projectId, $memberId)
    {
        $sql = "
            DELETE FROM project_members
            WHERE project_id = :project_id
              AND member_id = :member_id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'project_id' => $projectId,
            'member_id'  => $memberId
        ]);
    }

    public function getPartners($projectId)
    {
        $sql = "
            SELECT *
            FROM project_partners
            WHERE project_id = :project_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPartner($projectId, $partnerData)
    {
        $sql = "
            INSERT INTO project_partners (project_id, name, contact_info, role_description)
            VALUES (:project_id, :name, :contact_info, :role_description)
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'project_id'       => $projectId,
            'name'             => $partnerData['name'],
            'contact_info'     => $partnerData['contact_info'] ?? null,
            'role_description' => $partnerData['role_description'] ?? null
        ]);
    }

    public function removePartner($partnerId)
    {
        $stmt = $this->db->prepare("DELETE FROM project_partners WHERE id = :id");
        return $stmt->execute(['id' => $partnerId]);
    }
}