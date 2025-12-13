<?php
require_once "Db.php";

class TeamModel {
    public function findById($id){}
    public function getAll(){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function getMembers($teamId){}
    public function setLeader($teamId, $leaderId){}
    public function getPublications($teamId){}
}