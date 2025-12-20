<?php
require_once __DIR__ . '/../../model/PublicationModel.php';
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../view/admin/PublicationView.php';

class PublicationController {
    private $model;

    public function __construct() {
        $this->model = new PublicationModel();
    }

    public function index(): void {
        $publications = $this->model->getAll();

        foreach ($publications as &$pub) {
            $authors = $this->model->getAuthors($pub['id']);
            $authorNames = [];
            foreach ($authors as $a) {
                $authorNames[] = $a['display_name'];
            }
            $pub['authors'] = implode(', ', $authorNames);
        }

        $view = new PublicationView();
        $view->renderIndex($publications);
    }

    public function create(): void {
        $memberModel = new MemberModel();
        $members = $memberModel->getAll();
        $types = $this->model->getTypes();

        $view = new PublicationView();
        $view->addPublication($members, $types);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $publication = $_POST;

        if (empty($publication['date_published'])) {
            $publication['date_published'] = date('Y-m-d');
        }

        $publicationId = $this->model->create($publication);

        $authors = $_POST['authors'] ?? [];
        foreach ($authors as $a) {
            $this->model->addAuthor($publicationId, $a);
        }

        header('Location: /admin/publication/index');
        exit;
    }

    public function edit(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /admin/publication/index");
            exit;
        }

        $publication = $this->model->findById($id);
        if (!$publication) {
            header("Location: /admin/publication/index");
            exit;
        }

        $authors = $this->model->getAuthors($id);
        $memberModel = new MemberModel();
        $members = $memberModel->getAll();
        $types = $this->model->getTypes();

        $authorMemberIds = array_filter(array_column($authors, 'member_id'));
        $filteredMembers = [];
        foreach ($members as $m) {
            if (!in_array($m['id'], $authorMemberIds)) {
                $filteredMembers[] = $m;
            }
        }
        $members = $filteredMembers;        
        $view = new PublicationView();
        $view->renderEditForm($publication, $members, $types, $authors);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int)$_POST['id'];
        $publication = $_POST;

        if (empty($publication['date_published'])) {
            $publication['date_published'] = date('Y-m-d');
        }

        $this->model->update($id, $publication);

        $this->model->deleteAuthors($id);
        $authors = $_POST['authors'] ?? [];
        foreach ($authors as $a) {
            $this->model->addAuthor($id, $a);
        }

        header("Location: /admin/publication/index");
        exit;
    }
    public function delete(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Publication id required";
            return;
        }

        $this->model->delete($id);

        header("Location: /admin/publication/index");
        exit;
    }
}