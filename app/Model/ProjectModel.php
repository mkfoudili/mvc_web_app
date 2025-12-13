<?php
require_once "Db.php";

class ProjectModel {
    public function findById($id){}
    public function getAll(){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function getMembers($projectId){}
    public function addMember($projectId, $memberId){}
    public function removeMember($projectId, $memberId){}
    public function getPartners($projectId){}
    public function addPartner($projectId, $partnerData){}
    public function removePartner($partnerId){}
}