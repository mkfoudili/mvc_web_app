<?php
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../model/TeamModel.php';
require __DIR__ . '/../../view/admin/MemberView.php';


Class MemberController{
    private $model;
    public function __construct() {
        $this->model = new MemberModel();
    }

    public function index(){
        $members = $this->model->getAll();

        foreach ($members as &$member) {
            if (!empty($member['specialty_id'])) {
                $specialty = $this->model->getSpecialtyById($member['id']);
                $member['specialty_name'] = $specialty['name'] ?? null;
            }
        }        
        $view = new MemberView();
        $view->renderIndex($members);
    }
    public function delete(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /admin/member/index");
            exit;
        }
        $member = $this->model->findById($id);
        if (!$member) {
            header("Location: /admin/member/index");
            exit;
        }
        $this->model->delete($id);
        header("Location: /admin/member/index");
        exit;
    }
    public function edit(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /admin/member/index");
            exit;
        }

        $member = $this->model->findById($id);
        if (!$member) {
            header("Location: /admin/member/index");
            exit;
        }

        $specialties = $this->model->getSpecialties();
        $teamModel = new TeamModel();
        $teams = $teamModel->getAll();

        $view = new MemberView();
        $view->renderEditForm($member, $specialties, $teams);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int) $_POST['id'];
        $member = $this->model->findById($id);

        $data = $_POST;

        if (!empty($_POST['new_specialty'])) {
            $newId = $this->model->addSpeciality($_POST['new_specialty']);
            $data['specialty_id'] = $newId;
        }

        if (!empty($_POST['delete_photo'])) {
            $data['photo_url'] = "/assets/members/default.jpg";
        } elseif (!empty($_FILES['photo']['tmp_name'])) {
            $login = $_POST['login'];
            $targetDir = __DIR__ . "/../../../public/assets/members/";
            $targetPath = $targetDir . $login . ".png";
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                $data['photo_url'] = "/assets/members/" . $login . ".png";
            }
        } else {
            $data['photo_url'] = $member['photo_url'] ?? "/assets/members/default.jpg";
        }

        $this->model->update($id, $data);

        header("Location: /admin/member/index");
        exit;
    }
}