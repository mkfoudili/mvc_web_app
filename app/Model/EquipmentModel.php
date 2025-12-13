<?php
require_once "Db.php";

class EquipementModel {

    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }

    public function findById($id){
        $sql = "
            SELECT e.*, s.name AS state_name
            FROM equipment e
            LEFT JOIN equipment_states s ON e.state_id = s.id
            WHERE e.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll(){
        $sql = "
            SELECT e.*, s.name AS state_name
            FROM equipment e
            LEFT JOIN equipment_states s ON e.state_id = s.id
            ORDER BY e.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($data){
        $sql = "
            INSERT INTO equipment (name, type, state_id, description, location)
            VALUES (:name, :type, :state_id, :description, :location)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name'        => $data['name'],
            'type'        => $data['type'] ?? null,
            'state_id'    => $data['state_id'] ?? null,
            'description' => $data['description'] ?? null,
            'location'    => $data['location'] ?? null
        ]);

        return $this->db->lastInsertId();
    }
    public function update($id, $data){
        $sql = "
            UPDATE equipment
            SET name = :name,
                type = :type,
                state_id = :state_id,
                description = :description,
                location = :location
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'type'        => $data['type'] ?? null,
            'state_id'    => $data['state_id'] ?? null,
            'description' => $data['description'] ?? null,
            'location'    => $data['location'] ?? null
        ]);
    }
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM equipment WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    public function getReservations($equipmentId){
        $sql = "
            SELECT r.*, m.first_name, m.last_name
            FROM equipment_reservations r
            LEFT JOIN members m ON r.member_id = m.id
            WHERE r.equipment_id = :equipment_id
            ORDER BY r.reserved_from DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['equipment_id' => $equipmentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addReservation($data){
        $sql = "
            INSERT INTO equipment_reservations
            (equipment_id, member_id, reserved_from, reserved_to, purpose, status)
            VALUES (:equipment_id, :member_id, :from, :to, :purpose, 'confirmed')
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'equipment_id' => $data['equipment_id'],
            'member_id'    => $data['member_id'] ?? null,
            'from'         => $data['reserved_from'],
            'to'           => $data['reserved_to'],
            'purpose'      => $data['purpose'] ?? null
        ]);
    }
    public function cancelReservation($reservationId){
        $sql = "
            UPDATE equipment_reservations
            SET status = 'cancelled'
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $reservationId]);
    }
    public function getMaintenanceSchedule($equipmentId){
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
    public function addMaintenance($equipmentId, $data){
        $sql = "
            INSERT INTO equipment_maintenance (equipment_id, scheduled_at, description)
            VALUES (:equipment_id, :scheduled_at, :description)
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'equipment_id' => $equipmentId,
            'scheduled_at' => $data['scheduled_at'],
            'description'  => $data['description'] ?? null
        ]);
    }
    public function setState($equipmentId, $stateId){
        $sql = "
            UPDATE equipment
            SET state_id = :state_id
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'       => $equipmentId,
            'state_id' => $stateId
        ]);
    }
}