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
}