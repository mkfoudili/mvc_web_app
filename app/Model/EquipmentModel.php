<?php
require_once "Db.php";

class EquipementModel {
    public function findById($id){}
    public function getAll(){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function getReservations($equipmentId){}
    public function addReservation($data){}
    public function cancelReservation($reservationId){}
    public function getMaintenanceSchedule($equipmentId){}
    public function addMaintenance($equipmentId, $data){}
    public function setState($equipmentId, $stateId){}
}