<?php
require_once __DIR__ . "/../../model/EventModel.php";
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
}