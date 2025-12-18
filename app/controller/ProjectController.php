<?php
require_once __DIR__ . '/../model/ProjectModel.php';
require_once __DIR__ . '/../view/ProjectView.php';

class ProjectController {
    private $model;

    public function __construct() {
        $this->model = new ProjectModel();
    }

    public function index() {
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

        $view = new ProjectView();
        $view->renderShow($project, $members, $partners);
    }

    public function cards(){
        $allProjects = $this->model->getAll();

        $perPage = 3;
        $page = max(1, (int)($_GET['page'] ?? 1));

        $totalProjects = count($allProjects);
        $totalPages = (int) ceil($totalProjects / $perPage);

        $offset = ($page - 1) * $perPage;
        $projectsPage = array_slice($allProjects, $offset, $perPage);

        $baseurl = "/project/cards?";
        $view = new ProjectView();
        $view->renderCards($projectsPage, $page, $totalPages,$baseurl);
    }
}