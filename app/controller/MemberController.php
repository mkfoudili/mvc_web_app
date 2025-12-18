<?php
require_once __DIR__ . '/../model/MemberModel.php';
require_once __DIR__ . '/../view/MemberView.php';


Class MemberController{
    private $model;

    public function __construct(){
        $this->model = new MemberModel();
    }

    public function index()
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing member id";
            return;
        }

        $id = (int) $_GET['id'];
        $member = $this->model->findById($id);

        if (!$member) {
            http_response_code(404);
            echo "Member not found";
            return;
        }

        $view = new MemberView();
        $view->renderIndex($member);
    }
}