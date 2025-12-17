<?php
require_once __DIR__ . '/../model/PublicationModel.php';
require_once __DIR__ . '/../model/TeamModel.php';


class PublicationController {
    private $model;

    public function __construct() {
        $this->model = new PublicationModel();
    }

    public function index() {
        $teamModel = new TeamModel();
        $this->model = new PublicationModel();

        $teams = $teamModel->getAll();
        $data = [];

        foreach ($teams as $team) {
            $publications = $this->model->getByTeam($team['id']);

            foreach ($publications as &$pub) {
                $authors = $this->model->getAuthors($pub['id']);

                $authorNames = [];
                foreach ($authors as $a) {
                    $authorNames[] = $a['display_name'];
                }

                $pub['authors'] = implode(', ', $authorNames);
            }

            $data[] = [
                'team_name'    => $team['name'],
                'publications' => $publications
            ];
        }

        require_once __DIR__ . '/../view/PublicationView.php';
        $view = new PublicationView();
        $view->renderIndex($data);
    }
}