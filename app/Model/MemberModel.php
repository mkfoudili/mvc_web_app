<?php
require_once "Db.php";

class MemberModel {
    public function findById($id){}
    public function findByLogin($login){}
    public function getAll(){}
    public function getByTeam($teamId){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function assignToTeam($memberId, $teamId){}
    public function getPublications($memberId){}
    public function getProjects($memberId){}
}