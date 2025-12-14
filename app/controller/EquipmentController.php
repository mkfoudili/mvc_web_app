<?php
require_once __DIR__ . '/../model/EquipmentModel.php';

Class EquipmentController{
    private $model;

    public function __construct(){
        $this->model = new EquipementModel();
    }

    // GET /equipment
    public function index()
    {
        $this->model->archivePastReservations();
        $equipments = $this->model->getAll();

        foreach ($equipments as $e) {
            if ($e['state_id'] != 3) {
                $this->model->updateStateBasedOnReservations($e['id']);
            }
        }
        
        $equipments = $this->model->getAll();
        require __DIR__ . '/../view/equipment/index.php';
    }
    
    // GET or POST /equipment/addReservation/{id}
    public function addReservation()
    {
        $equipmentId = $_GET['id'] ?? null;

        if (!$equipmentId) {
            die('Equipment ID is required');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'equipment_id'   => $equipmentId,
                'reserved_from'  => $_POST['reserved_from'],
                'reserved_to'    => $_POST['reserved_to'],
                'purpose'        => $_POST['purpose'] ?? null,
            ];

            $this->model->addReservation($data);

            header('Location: /equipment');
            exit;
        }

        $equipment = $this->model->findById($equipmentId);

        if (!$equipment) {
            die('Equipment not found');
        }

        require __DIR__ . '/../view/equipment/addResercation.php';
    }

    public function reportBreakdown()
    {
        $equipmentId = $_GET['id'] ?? null;

        if (!$equipmentId) die('Equipment ID is required');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'description' => $_POST['description'] ?? null,
                'scheduled_at'=> null,
            ];

            $this->model->addMaintenance($equipmentId, $data);
            
            $this->model->setState($equipmentId, 3);

            header('Location: /equipment');
            exit;
        }

        $equipment = $this->model->findById($equipmentId);
        if (!$equipment) die('Equipment not found');

        require __DIR__ . '/../view/equipment/reportBreakdown.php';
    }

}