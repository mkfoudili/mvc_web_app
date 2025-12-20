<?php
require_once __DIR__ . '/../../model/PublicationModel.php';
require_once __DIR__ . '/../../view/admin/PublicationView.php';

class PublicationController {
    private $model;

    public function __construct() {
        $this->model = new PublicationModel();
    }

    public function index(): void {
        $publications = $this->model->getAll();

        foreach ($publications as &$pub) {
            $authors = $this->model->getAuthors($pub['id']);
            $authorNames = [];
            foreach ($authors as $a) {
                $authorNames[] = $a['display_name'];
            }
            $pub['authors'] = implode(', ', $authorNames);
        }

        $view = new PublicationView();
        $view->renderIndex($publications);
    }
}