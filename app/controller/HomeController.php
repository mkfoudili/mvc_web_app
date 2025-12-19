<?php
require_once __DIR__ . '/../model/NewsModel.php';
require_once __DIR__ . '/../model/TeamModel.php';
require_once __DIR__ . '/../model/EventModel.php';
require_once __DIR__ . '/../model/ProjectModel.php';
require_once __DIR__ . './../view/HomeView.php';

class HomeController {
    public function index(): void
    {
        $newsModel = new NewsModel();
        $teamModel = new TeamModel();
        $eventModel = new EventModel();
        $projectModel = new ProjectModel();

        $news   = $newsModel->getAll();
        $teams  = $teamModel->getAll();

        $allEvents = $eventModel->getAll();
        $today = new DateTime('today');
        foreach ($allEvents as &$event) {
            $eventDate = $event['event_date'] ? new DateTime($event['event_date']) : null;
            $event['is_upcoming'] = $eventDate && $eventDate >= $today;
        }
        $perPageEvents = 3;
        $pageEvents = max(1, (int)($_GET['event_page'] ?? 1));
        $totalEvents = count($allEvents);
        $totalPagesEvents = (int) ceil($totalEvents / $perPageEvents);
        $offsetEvents = ($pageEvents - 1) * $perPageEvents;
        $eventsPage = array_slice($allEvents, $offsetEvents, $perPageEvents);

        // --- Projects pagination ---
        $allProjects = $projectModel->getAll();
        $perPageProjects = 3;
        $pageProjects = max(1, (int)($_GET['project_page'] ?? 1));
        $totalProjects = count($allProjects);
        $totalPagesProjects = (int) ceil($totalProjects / $perPageProjects);
        $offsetProjects = ($pageProjects - 1) * $perPageProjects;
        $projectsPage = array_slice($allProjects, $offsetProjects, $perPageProjects);

        $view = new HomeView();
        $view->renderIndex(
            $news,
            $teams,
            $eventsPage, $pageEvents, $totalPagesEvents,
            $projectsPage, $pageProjects, $totalPagesProjects
        );
    }
}