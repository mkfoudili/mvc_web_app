<?php
require_once __DIR__ . '/../../model/ProjectModel.php';
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
}