<?php
require_once __DIR__ . '/../model/MemberModel.php';
require_once __DIR__ . '/../model/PublicationModel.php';
require_once __DIR__ . '/../model/ProjectModel.php';
require_once __DIR__ . '/../view/ProjectView.php';
require_once __DIR__ . '/../view/MemberView.php';


Class MemberController{
    private $model;

    public function __construct(){
        $this->model = new MemberModel();
    }

    public function index()
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing member id";
            return;
        }

        $id = (int) $_GET['id'];
        $member = $this->model->findById($id);

        if (!$member) {
            http_response_code(404);
            echo "Member not found";
            return;
        }

        $publicationModel = new PublicationModel();
        $publications = $this->model->getPublications($id);
        foreach ($publications as &$pub) {
            $authors = $publicationModel->getAuthors($pub['id']);

            $authorNames = [];
            foreach ($authors as $a) {
                $authorNames[] = $a['display_name'];
            }

            $pub['authors'] = implode(', ', $authorNames);
        }


        $projectModel = new ProjectModel();
        $allProjects = $projectModel->getByMember($id);
        $perPage = 3;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $totalPages = (int) ceil(count($allProjects) / $perPage);

        $projectsPage = array_slice(
            $allProjects,
            ($page - 1) * $perPage,
            $perPage
        );

        $baseurl = "/member/index?id=" . $id . "&";

        $view = new MemberView();
        $projectView = new ProjectView();
        $view->renderMyProfile($member, $publications, $projectsPage, $page, $totalPages, $projectView, $baseurl);
        // $view->renderIndex($member, $publications, $projectsPage, $page, $totalPages, $projectView, $baseurl);
    }
}