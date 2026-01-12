<?php
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../model/PublicationModel.php';
require_once __DIR__ . '/../../model/ProjectModel.php';
require_once __DIR__ . '/../../model/TeamModel.php';
require_once __DIR__ . '/../../model/EventModel.php';
require_once __DIR__ . '/../../model/ReservationModel.php';
require_once __DIR__ . '/../../view/user/ProjectView.php';
require_once __DIR__ . '/../../view/user/MemberView.php';
require_once __DIR__ . '/../../view/user/EventView.php';
require_once __DIR__ . '/../../view/user/EquipmentView.php';



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

        $teamModel = new TeamModel();
        $team = $teamModel->getByMemberId($id);
        if ($team) {
            $member['team_name'] = $team['name'];
        }

        $speciality = $this->model->getSpecialtyById($id);
        if ($speciality){
            $member['specialty_name'] = $speciality['name'];
        }

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

        $baseurl = "/member/index?id=" . $id . "&page=";

        session_start();
        $loggedInMemberId = $_SESSION['member_id'] ?? null;
        $view = new MemberView();
        $projectView = new ProjectView();

        if ($loggedInMemberId && (int)$loggedInMemberId === $id) {
            $eventView = new EventView();
            $equipmentView = new EquipmentView();
            $eventModel = new EventModel();
            $events = $eventModel->getUpcomingEventsByMember($id);
            $reservationModel = new ReservationModel();
            $reservations = $reservationModel->getByMember($id);
            $view->renderMyProfile($member, $publications, $projectsPage, $page, $totalPages, $projectView, $baseurl, $eventView, $events,$equipmentView, $reservations);
        }else{
            $view->renderIndex($member, $publications, $projectsPage, $page, $totalPages, $projectView, $baseurl);
        }
    }
    public function edit()
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

        $specialties = $this->model->getSpecialties();

        $teamModel = new TeamModel();
        $teams = $teamModel->getAll();

        $view = new MemberView();
        $view->renderEditProfile($member, $specialties, $teams);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int) $_POST['id'];
        $member = $this->model->findById($id);

        $data = array_map(function($value) {
            return $value === '' ? null : $value;
        }, $_POST);

        if (!empty($_POST['new_specialty'])) {
            $newId = $this->model->addSpeciality($_POST['new_specialty']);
            $data['specialty_id'] = $newId;
        }

        if (!empty($_POST['delete_photo'])) {
        $data['photo_url'] = "/assets/members/default.jpg";
        } elseif (!empty($_FILES['photo']['tmp_name'])) {
        $login = $_POST['login'];

        $targetDir = __DIR__ . "/../../public/assets/members/";
        $targetPath = $targetDir . $login . ".png";

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $data['photo_url'] = "/assets/members/" . $login . ".png";
        }
        } else {
            $data['photo_url'] = $member['photo_url'] ?? "/assets/members/default.jpg";
        }
    
        $this->model->update($id, $data);

        redirect('member/index', ['id' => $id]);
        exit;
    }
}