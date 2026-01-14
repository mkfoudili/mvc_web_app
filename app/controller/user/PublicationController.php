<?php
require_once __DIR__ . '/../../model/PublicationModel.php';
require_once __DIR__ . '/../../model/TeamModel.php';
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../view/user/PublicationView.php';



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

        $view = new PublicationView();
        $view->renderIndex($data);
    }

    public function create()
    {
        $memberId = (int)($_GET['id'] ?? 0);

        if ($memberId <= 0) {
            die('Invalid member');
        }

        $memberModel = new MemberModel();

        $members = $memberModel->getAll();
        $types = $this->model->getTypes();

        $view = new PublicationView();
        $view->addPublication($memberId, $members, $types);
    }

    public function store()
    {

        $publication = array_map(function($value) {
            return $value === '' ? null : $value;
        }, $_POST);
        if (empty($publication['date_published'])) {
        $publication['date_published'] = date('Y-m-d');
        }
        $publicationId = $this->model->create($publication);
        $authors = $_POST['authors'] ?? [];
        foreach ($authors as &$a) {
            $this->model->addAuthor($publicationId,$a);
        }
        redirect('member/index?id=' . (int)$_POST['member_id']);
        exit;
    }
}