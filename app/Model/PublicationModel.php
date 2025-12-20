<?php
require_once "Db.php";

class PublicationModel {
    private $db;

    public function __construct() {
        $this->db = DB::conn();
    }
    public function findById($id)
    {
        $sql = "SELECT * FROM publications WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "
            SELECT *
            FROM publications
            ORDER BY date_published DESC, created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTypes()
    {
        $sql = "SELECT * FROM publication_types ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByTeam($teamId)
    {
        $sql = "
            SELECT *
            FROM publications
            WHERE team_id = :team_id
            ORDER BY date_published DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['team_id' => $teamId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByMember($memberId)
    {
        $sql = "
            SELECT p.*
            FROM publications p
            INNER JOIN publication_authors pa ON pa.publication_id = p.id
            WHERE pa.member_id = :member_id
            ORDER BY p.date_published DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['member_id' => $memberId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "
            INSERT INTO publications
            (title, team_id, publication_type_id, date_published, doi, url, pdf_url, description)
            VALUES (:title, :team_id, :publication_type_id, :date_published, :doi, :url, :pdf_url, :description)
        ";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'title'               => $data['title'] ?? null,
            'team_id'             => $data['team_id'] ?? null,
            'publication_type_id' => $data['publication_type_id'] ?? null,
            'date_published'      => $data['date_published'] ?? null,
            'doi'                 => $data['doi'] ?? null,
            'url'                 => $data['url'] ?? null,
            'pdf_url'             => $data['pdf_url'] ?? null,
            'description'         => $data['description'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE publications
            SET title = :title,
                team_id = :team_id,
                publication_type_id = :publication_type_id,
                date_published = :date_published,
                doi = :doi,
                url = :url,
                pdf_url = :pdf_url,
                description = :description
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'                  => $id,
            'title'               => $data['title'],
            'team_id'             => $data['team_id'] ?? null,
            'publication_type_id' => $data['publication_type_id'] ?? null,
            'date_published'      => $data['date_published'] ?? null,
            'doi'                 => $data['doi'] ?? null,
            'url'                 => $data['url'] ?? null,
            'pdf_url'             => $data['pdf_url'] ?? null,
            'description'         => $data['description'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM publications WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function addAuthor($publicationId, $author)
    {
        // $author = [
        //   'member_id' => null | int,
        //   'author_name' => string,
        //   'author_order' => int,
        //   'affiliation' => string|null
        // ]

        $sql = "
            INSERT INTO publication_authors
            (publication_id, member_id, author_name, author_order, affiliation)
            VALUES (:publication_id, :member_id, :author_name, :author_order, :affiliation)
        ";
        $stmt = $this->db->prepare($sql);

        $memberId = !empty($author['member_id']) ? (int)$author['member_id'] : null;
        return $stmt->execute([
            'publication_id' => $publicationId,
            'member_id'      => $memberId,
            'author_name'    => $author['author_name'] ?? '',
            'author_order'   => $author['author_order'] ?? 0,
            'affiliation'    => $author['affiliation'] ?? null
        ]);
    }

    public function removeAuthor($publicationId, $authorId)
    {
        $sql = "
            DELETE FROM publication_authors
            WHERE publication_id = :publication_id
              AND id = :author_id
        ";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'publication_id' => $publicationId,
            'author_id'      => $authorId
        ]);
    }

    public function getAuthors($publicationId)
    {
        $sql = "
        SELECT
            pa.id,
            pa.author_order,
            pa.affiliation,
            pa.member_id,
            COALESCE(
                CONCAT(m.first_name, ' ', m.last_name),
                pa.author_name
            ) AS display_name
        FROM publication_authors pa
        LEFT JOIN members m ON pa.member_id = m.id
        WHERE pa.publication_id = :publication_id
        ORDER BY pa.author_order ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['publication_id' => $publicationId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}