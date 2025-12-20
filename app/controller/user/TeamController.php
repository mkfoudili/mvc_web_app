<?php
require_once __DIR__ . '/../../model/TeamModel.php';
require_once __DIR__ . '/../../view/user/TeamView.php';

Class TeamController{

    private $model;

    public function __construct(){
        $this->model = new TeamModel();
    }

    public function index() {
        $teams = $this->model->getAll();

        foreach ($teams as &$team) {
            $team['members'] = $this->model->getMembers($team['id']);
        }

        $view = new TeamView();
        $view->renderIndex($teams);
    }
    public function general(){
        $teams = $this->model->getAll();
        $view = new TeamView();
        $view->renderTeams($teams);
    }
}