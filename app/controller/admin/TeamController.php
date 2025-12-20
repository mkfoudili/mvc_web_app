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

        public function edit(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Team id required";
            return;
        }

        $team = $this->model->findById((int)$id);
        if (!$team) {
            http_response_code(404);
            echo "Team not found";
            return;
        }
        $memberModel = new MemberModel();
        $members     = $memberModel->getAll();
        $teamMembers = $this->model->getMembers((int)$id);

        $view = new TeamView();
        $view->renderEditForm($team, $members, $teamMembers);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int)$_POST['id'];
        $data = [
            'name'             => $_POST['name'],
            'leader_member_id' => $_POST['leader_member_id'] ?? null,
            'domain'           => $_POST['domain'] ?? null,
            'description'      => $_POST['description'] ?? null
        ];

        $this->model->update($id, $data);

        $members = $_POST['members'] ?? [];
        if (!empty($data['leader_member_id'])) {
            $members[] = (int)$data['leader_member_id'];
        }
        $memberModel = new MemberModel();
        foreach ($members as $member) {
            $memberModel->assignToTeam($member,$id);
        }
        $this->model->resetMembers($id, array_unique(array_map('intval', $members)));
        header("Location: /admin/team/index");
        exit;
    }

    public function delete(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Team id required";
            return;
        }

        $team = $this->model->findById((int)$id);
        if (!$team) {
            http_response_code(404);
            echo "Team not found";
            return;
        }

        $this->model->delete((int)$id);

        header("Location: /admin/team/index");
        exit;
    }
}