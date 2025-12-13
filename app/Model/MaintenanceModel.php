<?php
require_once "Db.php";

class MaintenanceModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id){
        $sql = "
            SELECT em.*, e.name AS equipment_name
            FROM equipment_maintenance em
            INNER JOIN equipment e ON em.equipment_id = e.id
            WHERE em.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll(){
        $sql = "
            SELECT em.*, e.name AS equipment_name
            FROM equipment_maintenance em
            INNER JOIN equipment e ON em.equipment_id = e.id
            ORDER BY em.scheduled_at ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByEquipment($equipmentId){
        $sql = "
            SELECT *
            FROM equipment_maintenance
            WHERE equipment_id = :equipment_id
            ORDER BY scheduled_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['equipment_id' => $equipmentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($data){
        $sql = "
            INSERT INTO equipment_maintenance
            (equipment_id, scheduled_at, description)
            VALUES (:equipment_id, :scheduled_at, :description)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'equipment_id' => $data['equipment_id'],
            'scheduled_at' => $data['scheduled_at'],
            'description'  => $data['description'] ?? null
        ]);

        return $this->db->lastInsertId();
    }
    public function update($id, $data){
        $sql = "
            UPDATE equipment_maintenance
            SET scheduled_at = :scheduled_at,
                description = :description
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'           => $id,
            'scheduled_at' => $data['scheduled_at'],
            'description'  => $data['description'] ?? null
        ]);
    }
    public function delete($id){
        $stmt = $this->db->prepare(
            "DELETE FROM equipment_maintenance WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }
}