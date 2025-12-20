<?php
require_once __DIR__ . '/../../model/ProjectModel.php';
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../view/admin/ProjectView.php';


Class ProjectController{
    private $model;
    public function __construct(){
        $this->model = new ProjectModel();
    }

    public function index(){
        $projects = $this->model->getAll();

        foreach ($projects as &$project) {
            $members = $this->model->getMembers($project['id']);
            $partners = $this->model->getPartners($project['id']);

            $project['members_list'] = implode(', ', array_map(function($m) {
                return $m['first_name'] . ' ' . $m['last_name'];
            }, $members));

            $project['partners_list'] = implode(', ', array_map(function($p) {
                return $p['name'];
            }, $partners));
        }
        $view = new ProjectView();
        $view->renderIndex($projects);
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo "Project id required";
            return;
        }

        $project = $this->model->findById($id);

        if (!$project) {
            http_response_code(404);
            echo "Project not found";
            return;
        }

        $members  = $this->model->getMembers($id);
        $partners = $this->model->getPartners($id);

        $members = array_filter($members, function($m) use ($project) {
            return $m['member_id'] != $project['leader_member_id'];
        });

        $view = new ProjectView();
        $view->renderShow($project, $members, $partners);
    }
    public function create(): void {
        $memberModel = new MemberModel();
        $members = $memberModel->getAll();

        $fundingTypes = $this->model->getFundingTypes();

        $view = new ProjectView();
        $view->renderAddForm($members, $fundingTypes);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $data = [];
        foreach ($_POST as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            } else {
                $data[$key] = $value;
            }
        }

        $projectId = $this->model->create($data);

        $members = $_POST['members'] ?? [];
        foreach ($members as $m) {
            $this->model->addMember($projectId, (int)$m['member_id'], $m['role_in_project'] ?? null);
        }

        $partners = $_POST['partners'] ?? [];
        foreach ($partners as $p) {
            $this->model->addPartner($projectId, $p);
        }

        header("Location: /admin/project/index");
        exit;
    }
}