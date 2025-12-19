<?php
require_once __DIR__ . './../model/NewsModel.php';
require_once __DIR__ . './../view/NewsView.php';

CLass NewsController{
    private $model;

    public function __construct(){
        $this->model = new NewsModel();
    }

    public function index()
    {
        $news = $this->model->getAll();
        $view = new NewsView();
        $view->renderIndex($news);
    }
}