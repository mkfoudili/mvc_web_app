<?php
require_once "Db.php";

class ReservationModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "
            SELECT r.*, 
                   e.name AS equipment_name,
                   m.first_name,
                   m.last_name
            FROM equipment_reservations r
            INNER JOIN equipment e ON r.equipment_id = e.id
            LEFT JOIN members m ON r.member_id = m.id
            WHERE r.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT r.*, e.name AS equipment_name
            FROM equipment_reservations r
            INNER JOIN equipment e ON r.equipment_id = e.id
            ORDER BY r.reserved_from DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEquipment($equipmentId)
    {
        $sql = "
            SELECT *
            FROM equipment_reservations
            WHERE equipment_id = :equipment_id
            ORDER BY reserved_from DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['equipment_id' => $equipmentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByMember($memberId)
    {
    $sql = "
        SELECT r.*, 
               e.name AS equipment_name,
               e.type AS equipment_type,
               e.description AS equipment_description,
               e.location AS equipment_location,
               s.name AS equipment_state
        FROM equipment_reservations r
        INNER JOIN equipment e ON r.equipment_id = e.id
        LEFT JOIN equipment_states s ON e.state_id = s.id
        WHERE r.member_id = :member_id
        ORDER BY r.reserved_from DESC
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['member_id' => $memberId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO equipment_reservations
            (equipment_id, member_id, reserved_from, reserved_to, purpose, status)
            VALUES (:equipment_id, :member_id, :from, :to, :purpose, 'confirmed')
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'equipment_id' => $data['equipment_id'],
            'member_id'    => $data['member_id'] ?? null,
            'from'         => $data['reserved_from'],
            'to'           => $data['reserved_to'],
            'purpose'      => $data['purpose'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function cancel($id)
    {
        $sql = "
            UPDATE equipment_reservations
            SET status = 'cancelled'
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}