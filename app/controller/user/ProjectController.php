<?php
require_once __DIR__ . '/../../model/ProjectModel.php';
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../view/user/ProjectView.php';

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

        $members = array_filter($members, function($m) use ($project) {
            return $m['member_id'] != $project['leader_member_id'];
        });

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

        $baseurl = "/project/cards?page=";
        $view = new ProjectView();
        $view->renderCards($projectsPage, $page, $totalPages,$baseurl,"#");
    }
    public function create()
    {
        $memberId = $_GET['member_id'] ?? null;

        $memberModel = new MemberModel();
        $members = $memberModel->getAll();

        $fundingTypes = $this->model->getFundingTypes();

        $view = new ProjectView();
        $view->renderCreateForm($members, $fundingTypes, $memberId);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $data = [
            'title'            => $_POST['title'],
            'leader_member_id' => $_POST['leader_member_id'],
            'theme'            => $_POST['theme'],
            'funding_type_id'  => $_POST['funding_type_id'],
            'project_page_url' => $_POST['project_page_url'] ?? null,
            'poster_url'       => $_POST['poster_url'] ?? null,
            'description'      => $_POST['description'] ?? null
        ];

        $projectId = $this->model->create($data);

       if (!empty($_POST['members'])) {
        foreach ($_POST['members'] as $m) {
            $memberId = (int)($m['member_id'] ?? 0);

            if ($memberId === $data['leader_member_id']) {
                continue;
            }

            if ($memberId > 0) {
                $this->model->addMember($projectId, $memberId, 'participant');
            }
        }
    }

    if (!empty($_POST['partners'])) {
        foreach ($_POST['partners'] as $partner) {
            $partnerData = [
                'name'             => trim($partner['name'] ?? ''),
                'contact_info'     => trim($partner['contact_info'] ?? ''),
                'role_description' => trim($partner['role_description'] ?? '')
            ];

            if (!empty($partnerData['name'])) {
                $this->model->addPartner($projectId, $partnerData);
            }
        }
    }

        header("Location: /project/show?id=" . $projectId);
        exit;
    }
}