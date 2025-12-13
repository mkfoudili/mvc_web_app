<?php
require_once "Db.php";

class NewsModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "
            SELECT *
            FROM news
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT *
            FROM news
            ORDER BY published_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatest($limit)
    {
        $limit = (int) $limit;

        $sql = "
            SELECT *
            FROM news
            ORDER BY published_at DESC
            LIMIT {$limit}
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO news (photo_url, title, description, published_at)
            VALUES (:photo_url, :title, :description, :published_at)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'photo_url'   => $data['photo_url'] ?? null,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'published_at'=> $data['published_at'] ?? date('Y-m-d H:i:s')
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE news
            SET photo_url = :photo_url,
                title = :title,
                description = :description
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'          => $id,
            'photo_url'   => $data['photo_url'] ?? null,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}