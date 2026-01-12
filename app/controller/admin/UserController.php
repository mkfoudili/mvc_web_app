<?php
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../view/admin/UserView.php';

Class UserController {
    private $model;
    public function __construct() {
        $this->model = new UserModel();
    }
    public function index() {
        $users = $this->model->getAll();
        $view = new UserView();
        $view->renderIndex($users);
    }

    public function addForm(): void {

    $roles = $this->model->getRoles();
    $specialties = $this->model->getSpecialties();

    $view = new UserView();
    $view->renderAddForm($roles, $specialties);
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login  = trim($_POST['login']);
            $email  = trim($_POST['email']);
            $password = $_POST['password'];

            if ($this->model->findByLogin($login)) {
                $error = "Login already exists.";
            } elseif ($this->model->findByEmail($email)) {
                $error = "Email already exists.";
            }

            if (!empty($error)) {
                $roles = $this->model->getRoles();
                $specialties = $this->model->getSpecialties();
                $view = new UserView();
                $view->renderAddForm($roles, $specialties, $error);
                return;
            }

            $data = [
                'login'       => $_POST['login'],
                'email'       => $_POST['email'],
                'password'    => $_POST['password'],
                'status'      => $_POST['status'] ?? 'active',
                'role_id'     => $_POST['role_id'] ?: null,
                'specialty_id'=> $_POST['specialty_id'] ?: null,
                'permissions' => []
            ];

            if (!empty($_POST['new_role'])) {
                $data['role_id'] = $this->model->createRole(['name' => $_POST['new_role']]);
            }
            if (!empty($_POST['new_specialty'])) {
                $data['specialty_id'] = $this->model->createSpecialty(['name' => $_POST['new_specialty']]);
            }

            $this->model->create($data);
            redirect("admin/user/index");
            exit;
        }
    }
    public function edit(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect("admin/user/index");
            exit;
        }

        $user = $this->model->findById($id);
        if (!$user) {
            redirect("admin/user/index");
            exit;
        }

        $roles = $this->model->getRoles();
        $specialties = $this->model->getSpecialties();

        $view = new UserView();
        $view->renderEditForm($user, $roles, $specialties);
    }


    public function delete(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect("admin/user/index");
            exit;
        }

        $user = $this->model->findById($id);
        if (!$user) {
            redirect("admin/user/index");
            exit;
        }

        $this->model->delete($id);

        redirect("admin/user/index");
        exit;
    }

    public function toggleStatus(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect("admin/user/index");
            exit;
        }

        $user = $this->model->findById($id);
        if (!$user) {
            redirect("admin/user/index");
            exit;
        }

        $newStatus = ($user['status'] === 'active') ? 'suspended' : 'active';

        $this->model->update($id, [
            'login'         => $user['login'],
            'email'         => $user['email'],
            'password_hash' => $user['password_hash'], // keep existing hash
            'role_id'       => $user['role_id'],
            'permissions'   => json_decode($user['permissions'], true),
            'specialty_id'  => $user['specialty_id'],
            'status'        => $newStatus
        ]);

        redirect("admin/user/index");
        exit;
    }
}