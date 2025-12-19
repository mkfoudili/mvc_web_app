<?php
require_once "Db.php";

class EventModel {
    private $db;

    public function __construct()
    {
        $this->db = DB::conn();
    }
    public function findById($id){
        $sql = "
            SELECT e.*, et.name AS event_type_name
            FROM events e
            LEFT JOIN event_types et ON e.event_type_id = et.id
            WHERE e.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll(){
        $sql = "
            SELECT e.*, et.name AS event_type_name
            FROM events e
            LEFT JOIN event_types et ON e.event_type_id = et.id
            ORDER BY e.event_date DESC, e.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($data){
        $sql = "
            INSERT INTO events
            (name, event_type_id, event_date, description, link, participation_requests, participation_requests_json)
            VALUES (:name, :event_type_id, :event_date, :description, :link, :participation_requests, :participation_requests_json)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'name'                        => $data['name'],
            'event_type_id'               => $data['event_type_id'] ?? null,
            'event_date'                  => $data['event_date'] ?? null,
            'description'                 => $data['description'] ?? null,
            'link'                        => $data['link'] ?? null,
            'participation_requests'      => $data['participation_requests'] ?? null,
            'participation_requests_json' => isset($data['participation_requests_json'])
                ? json_encode($data['participation_requests_json'])
                : null
        ]);

        return $this->db->lastInsertId();
    }
    public function update($id, $data){
        $sql = "
            UPDATE events
            SET name = :name,
                event_type_id = :event_type_id,
                event_date = :event_date,
                description = :description,
                link = :link,
                participation_requests = :participation_requests,
                participation_requests_json = :participation_requests_json
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'                          => $id,
            'name'                        => $data['name'],
            'event_type_id'               => $data['event_type_id'] ?? null,
            'event_date'                  => $data['event_date'] ?? null,
            'description'                 => $data['description'] ?? null,
            'link'                        => $data['link'] ?? null,
            'participation_requests'      => $data['participation_requests'] ?? null,
            'participation_requests_json' => isset($data['participation_requests_json'])
                ? json_encode($data['participation_requests_json'])
                : null
        ]);
    }
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    public function getParticipants($eventId){
        $sql = "
            SELECT ep.member_id,
                   ep.role,
                   ep.registered_at,
                   m.first_name,
                   m.last_name,
                   m.photo_url,
                   m.role_in_lab
            FROM event_participants ep
            LEFT JOIN members m ON ep.member_id = m.id
            WHERE ep.event_id = :event_id
            ORDER BY ep.registered_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['event_id' => $eventId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addParticipant($eventId, $memberId){
        $sql = "
            INSERT INTO event_participants (event_id, member_id, role)
            VALUES (:event_id, :member_id, 'participant')
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'event_id'  => $eventId,
            'member_id' => $memberId
        ]);
    }
    public function removeParticipant($eventId, $memberId){
        $sql = "
            DELETE FROM event_participants
            WHERE event_id = :event_id
              AND member_id = :member_id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'event_id'  => $eventId,
            'member_id' => $memberId
        ]);
    }

    public function getRequests($eventId)
    {
        $sql = "
            SELECT er.*, 
                COALESCE(CONCAT(m.first_name, ' ', m.last_name), er.name) AS display_name
            FROM event_requests er
            LEFT JOIN members m ON er.member_id = m.id
            WHERE er.event_id = :event_id
            ORDER BY er.submitted_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function submitRequest($eventId, $data)
    {
        $sql = "
            INSERT INTO event_requests (event_id, member_id, name, email, message)
            VALUES (:event_id, :member_id, :name, :email, :message)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'event_id'  => $eventId,
            'member_id' => $data['member_id'] ?? null,
            'name'      => $data['name'] ?? null,
            'email'     => $data['email'] ?? null,
            'message'   => $data['message'] ?? null
        ]);
    }

    public function getUpcomingEventsByMember($memberId)
    {
        $sql = "
            SELECT e.*, et.name AS event_type_name
            FROM events e
            INNER JOIN event_participants ep ON e.id = ep.event_id
            LEFT JOIN event_types et ON e.event_type_id = et.id
            WHERE ep.member_id = :member_id
            AND e.event_date IS NOT NULL
            AND e.event_date >= NOW()
            ORDER BY e.event_date ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['member_id' => $memberId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}