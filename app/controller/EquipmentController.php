<?php
require_once __DIR__ . '/../model/EquipmentModel.php';
require_once __DIR__ . '/../model/ReservationModel.php';
require_once __DIR__ . '/../view/EquipmentView.php';


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
        $view = new EquipmentView();
        $view->renderIndex($equipments);
    }
    
    // GET or POST /equipment/addReservation/{id}
    public function addReservation()
    {
        session_start();
        $equipmentId = $_GET['id'] ?? null;

        if (!$equipmentId) {
            http_response_code(400);
            die('Equipment ID is required');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $memberId = $_SESSION['member_id'] ?? null;
            if (!$memberId) {
                http_response_code(403);
                die('You must be logged in to reserve equipment.');
            }

            $data = [
                'equipment_id'   => (int)$equipmentId,
                'member_id'      => (int)$memberId,
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
            http_response_code(404);
            die('Equipment not found');
        }
        $view = new EquipmentView();
        $view->renderAddReservation($equipment);
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
        $view = new EquipmentView();
        $view->renderReportBreakdown($equipment);
    }
    
    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $id = (int)$_POST['id'];
        $reservationModel = new ReservationModel();

        $reservationModel->cancel($id);
        $reservation = $reservationModel->findById($id);
        $memberId = $reservation['member_id'] ?? null;

        if ($memberId) {
            header("Location: /member/index?id=" . $memberId);
        } else {
            header("Location: /member/index");
        }
        exit;
    }
}