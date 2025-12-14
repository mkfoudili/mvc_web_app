<?php
require_once __DIR__ . '/../model/ProjectModel.php';

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
        require_once __DIR__ . '/../view/ProjectView.php';
        $view = new ProjectView();
        $view->renderIndex($projects);
    }
}