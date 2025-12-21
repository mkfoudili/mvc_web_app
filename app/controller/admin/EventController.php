<?php
require_once __DIR__ . "/../../model/EventModel.php";
require_once __DIR__ . "/../../model/MemberModel.php";
require_once __DIR__ . "/../../view/admin/EventView.php";

Class EventController {
    private $model;
    public function __construct(){
        $this->model = new EventModel();
    }

    public function index(){
        $events = $this->model->getAll();
        foreach ($events as &$event) {
            $event['participants'] = $this->model->getParticipants((int)$event['id']);
        }
        $view = new EventView();
        $view->renderIndex($events);
    }

    public function add(): void {
        $view = new EventView();
        $eventTypes = $this->model->getEventTypes();
        $memberModel = new MemberModel();
        $members = $memberModel->getAll();
        $view->renderAddForm($eventTypes,$members,null);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $data = [
            'name'        => $_POST['name'],
            'event_type_id' => $_POST['event_type_id'] ?? null,
            'event_date'  => $_POST['event_date'] ?? null,
            'description' => $_POST['description'] ?? null,
            'link'        => $_POST['link'] ?? null
        ];

        $eventId = $this->model->create($data);

        $participants = $_POST['participants'] ?? [];
        foreach ($participants as $memberId) {
            $this->model->addParticipant($eventId, (int)$memberId);
        }
        header("Location: /admin/event/index");
        exit;
    }

    public function edit(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Event id required";
            return;
        }

        $event = $this->model->findById((int)$id);
        if (!$event) {
            http_response_code(404);
            echo "Event not found";
            return;
        }

        $eventTypes = $this->model->getEventTypes();

        $memberModel = new MemberModel();
        $members = $memberModel->getAll();

        $participants = $this->model->getParticipants((int)$id);

        $view = new EventView();
        $view->renderEditForm($event, $eventTypes, $members, $participants, null);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int)$_POST['id'];

        $data = [
            'name'          => $_POST['name'],
            'event_type_id' => $_POST['event_type_id'] ?? null,
            'event_date'    => $_POST['event_date'] ?? null,
            'description'   => $_POST['description'] ?? null,
            'link'          => $_POST['link'] ?? null
        ];

        $this->model->update($id, $data);

        $this->model->deleteParticipants($id);
        $participants = $_POST['participants'] ?? [];
        foreach ($participants as $memberId) {
            $this->model->addParticipant($id, (int)$memberId);
        }

        header("Location: /admin/event/index");
        exit;
    }
}