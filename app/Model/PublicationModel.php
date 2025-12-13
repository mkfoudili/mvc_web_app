<?php
require_once "Db.php";

class PublicationModel {
    public function findById($id){}
    public function getAll(){}
    public function getByTeam($teamId){}
    public function getByMember($memberId){}
    public function create($data){}
    public function update($id, $data){}
    public function delete($id){}
    public function addAuthor($publicationId, $memberId || $externalName){}
    public function removeAuthor($publicationId, $authorId){}
    public function getAuthors($publicationId){}
}