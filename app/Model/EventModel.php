<?php
require_once "Db.php";

class EventModel {
    public function findById($id){}
    public function getAll(){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function getParticipants($eventId){}
    public function addParticipant($eventId, $memberId){}
    public function removeParticipant($eventId, $memberId){}
}