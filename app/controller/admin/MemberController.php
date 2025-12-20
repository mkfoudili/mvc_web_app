<?php
require_once __DIR__ . '/../../model/MemberModel.php';
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
}