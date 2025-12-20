<?php
require_once __DIR__ . "/../../model/EquipmentModel.php";
require_once __DIR__ . "/../../view/admin/EquipmentView.php";

Class EquipmentController {
    private $model;
    public function __construct(){
        $this->model = new EquipementModel();
    }
    public function index(){
        $this->model->archivePastReservations();
        $equipments = $this->model->getAll();

        foreach ($equipments as $e) {
            if ($e['state_id'] != 3) {
                $this->model->updateStateBasedOnReservations($e['id']);
            }
        }
        
        $equipments = $this->model->getAll();
        $view = new EquipmentView();
        $view->renderIndex($equipments);
    }

    public function add(): void {
        $states = $this->model->getEquipmentStates();

        $view = new EquipmentView();
        $view->renderAddForm($states);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = $value === '' ? null : $value;
        }

        $this->model->create($data);

        header("Location: /admin/equipment/index");
        exit;
    }

    public function edit(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Equipment id required";
            return;
        }

        $equipment = $this->model->findById($id);
        if (!$equipment) {
            http_response_code(404);
            echo "Equipment not found";
            return;
        }

        $states = $this->model->getEquipmentStates();

        $view = new EquipmentView();
        $view->renderEditForm($equipment, $states);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int)$_POST['id'];

        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = $value === '' ? null : $value;
        }

        $this->model->update($id, $data);

        header("Location: /admin/equipment/index");
        exit;
    }
}