<?php
require_once __DIR__ . '/../../model/AdminModel.php';
require_once __DIR__ . '/../../view/admin/LoginView.php';


Class LoginController{
    private $model;
    public function __construct(){
        $this->model = new AdminModel();
    }
    public function index(){
        session_start();
        $view = new LoginView();
        if (!empty($_SESSION['admin_id'])) {
            $admin = $this->model->findById($_SESSION['admin_id']);
            $view->renderLoggedIn($admin);
        } else {
            $view->renderIndex();
        }
    }
    public function login(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo "Method not allowed";
                return;
        }

        $login    = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $admin = $this->model->authenticate($login, $password);

        $view = new LoginView();
        if ($admin) {
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['login']   = $admin['login'];
            $view->renderSuccess($admin);
        } else {
            $view->renderError("Invalid login or password.");
        }
    }
    public function logout(): void {
        session_start();
        session_destroy();
        redirect("/admin/login/index");
        exit;
    }
}