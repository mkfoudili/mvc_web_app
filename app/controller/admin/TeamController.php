<?php
require_once __DIR__ . "/../../model/TeamModel.php";
require_once __DIR__ . "/../../model/MemberModel.php";
require_once __DIR__ . "/../../view/admin/TeamView.php";

Class TeamController{
    private $model;
    public function __construct(){
        $this->model = new TeamModel();
    }

    public function index(){
        $teams = $this->model->getAll();

        foreach ($teams as &$team) {
            $members = $this->model->getMembers($team['id']);
            $team['members_list'] = implode(', ', array_map(function($m) {
                return $m['last_name'] . ' ' . $m['first_name'];
            }, $members));
        }

        $view = new TeamView();
        $view->renderIndex($teams);
    }

    public function create(): void {
        $memberModel = new MemberModel();
        $members = $memberModel->getAll();
        $view = new TeamView();
        $view->renderAddForm($members);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $data = [
            'name'             => $_POST['name'],
            'leader_member_id' => $_POST['leader_member_id'] ?? null,
            'domain'           => $_POST['domain'] ?? null,
            'description'      => $_POST['description'] ?? null
        ];

        $teamId = $this->model->create($data);
        $memberModel = new MemberModel();
        $members = $_POST['members'] ?? [];
        $memberModel->assignToTeam((int)$data['leader_member_id'],(int)$teamId);
        $this->model->addMemberToTeam((int)$teamId,(int)$data['leader_member_id']);
        foreach ($members as $memberId) {
            $memberModel->assignToTeam((int)$memberId,(int)$teamId);
            $this->model->addMemberToTeam((int)$teamId,(int)$memberId);
        }

        header("Location: /admin/team/index");
        exit;
    }
}