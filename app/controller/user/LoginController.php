<?php
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/MemberModel.php';
require_once __DIR__ . '/../../view/user/LoginView.php';


Class LoginController{
    private $model;
    public function __construct(){
        $this->model = new UserModel();
    }
    public function index(){
        session_start();
        $view = new LoginView();
        if (!empty($_SESSION['user_id'])) {
            $user = $this->model->findById($_SESSION['user_id']);
            $view->renderLoggedIn($user);
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

            $user = $this->model->authenticate($login, $password);

            $view = new LoginView();
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login']   = $user['login'];

                $memberModel = new MemberModel();
                $member = $memberModel->findByLogin($user['login']);
                if ($member) {
                    $_SESSION['member_id'] = $member['id'];
                }

                $view->renderSuccess($user);
            } else {
                $view->renderError("Invalid login or password.");
            }
    }
    public function logout(): void {
        session_start();
        session_destroy();
        redirect("/login/index");
        exit;
    }
}