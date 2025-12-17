<?php
require_once __DIR__ . '/../model/EventModel.php';
require_once __DIR__ . '/../view/EventView.php';


Class EventController{
    private $model;

    public function __construct(){
        $this->model = new EventModel();
    }

    public function index()
    {
        $events = $this->model->getAll();

        $today = new DateTime('today');

        foreach ($events as &$event) {
            $eventDate = $event['event_date']
                ? new DateTime($event['event_date'])
                : null;

            $event['is_upcoming'] = $eventDate && $eventDate >= $today;
        }

        $view = new EventView();
        $view->renderIndex($events);
    }

    public function joinForm()
    {
        $eventId = $_GET['id'] ?? null;
        if (!$eventId) {
            header("Location: /event");
            exit;
        }

        $event = $this->model->findById($eventId);
        if (!$event) {
            header("Location: /event");
            exit;
        }

        require_once __DIR__ . '/../view/EventView.php';
        $view = new EventView();
        $view->renderJoinForm($event);
    }

    public function joinEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'];
            $memberId = $_POST['member_id'] ?? null;
            if ($memberId === '') {
                $memberId = null;
            }
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $message = $_POST['message'] ?? null;

            $this->model->submitRequest($eventId, [
                'member_id' => $memberId,
                'name'      => $name,
                'email'     => $email,
                'message'   => $message
            ]);

            header("Location: /event");
            exit;
        }
    }
}