<?php
require_once __DIR__ . "/../../view/admin/HomeView.php";

Class HomeController {
    private $model;
    
    public function __construct(){
    }
    
    public function index(){
        $view = new HomeView();
        $view->renderIndex();
    }
}