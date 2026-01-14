<?php
require_once __DIR__ . '/NewsView.php';
require_once __DIR__ . '/TeamView.php';
require_once __DIR__ . '/EventView.php';
require_once __DIR__ . '/ProjectView.php';

Class HomeView{
public function renderIndex(array $news,array $teams, array $eventsPage, int $pageEvents, int $totalPagesEvents,array $projectsPage, int $pageProjects, int $totalPagesProjects):void{
        $newsView = new NewsView();
        $teamView = new TeamView();
        $eventView = new EventView();
        $projectView = new ProjectView();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Research Lab Home</title>
            <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
             <link rel="stylesheet" href="<?= base('css/base.css') ?>">
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <div class="container home-centered">
        <h1>University Research Lab</h1>
        <p>
            <a href="https://www.uni-website.example" target="_blank">University Website</a>
            <a href="https://www.facebook.com/labpage" target="_blank">Facebook</a> |
            <a href="https://twitter.com/labpage" target="_blank">Twitter</a> |
            <a href="https://www.linkedin.com/company/labpage" target="_blank">LinkedIn</a> |
            <a href="https://www.youtube.com/channel/labchannel" target="_blank">YouTube</a>
        </p>
        <section>
            <?php $newsView->renderDiaporama(array_slice($news, 0, 4)); ?>
        </section>

        <section>
            <h2>Latest News</h2>
            <?php $newsView->renderNews($news); ?>
        </section>

        <section>
            <h2>About the Lab</h2>
            <p>
                Our lab is dedicated to advancing scientific knowledge and addressing real-world challenges through interdisciplinary research and collaboration.
            </p>
            <h2>Topics of research</h2>
            <p>
                We explore a wide range of research topics that span fundamental theory, applied methodologies, and real-world problem solving. By combining rigorous analysis, experimental approaches, and modern technologies, our work aims to contribute meaningful insights to both academic research and practical applications. The laboratory encourages collaboration across disciplines, promotes critical thinking, and supports the development of innovative solutions to complex scientific and technological challenges.
            </p>
            <h2>Our teams</h2>
            <?php $teamView->renderTeams($teams); ?>
        </section>

        <section>
            <h2>Organigramme</h2>
            <img src="<?= base('assets/organigramme/organigramme.png') ?>" alt="Lab Organigramme" class="zoom-hover">
        </section>

        <section>
            <h2>Publications</h2>
            <p>Explore our published articles and research contributions.</p>
            <a href="<?= base('publication/index') ?>"><button>View Publications</button></a>
        </section>

        <section id="events">
            <h2>Events</h2>
            <?php $eventView->renderCards($eventsPage, $pageEvents, $totalPagesEvents, "/home/index?event_page=","#events"); ?>
            <a href="<?= base('event/index') ?>"><button>Details Events</button></a>
        </section>

        <section id="projects">
            <h2>Projects</h2>
            <?php $projectView->renderCards($projectsPage, $pageProjects, $totalPagesProjects, "/home/index?project_page=","#projects"); ?>
            <a href="<?= base('project/index') ?>"><button>Details Projects</button></a>
        </section>

        <section>
            <h2>Partners</h2>
            <div style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap;">
                <img src="<?= base('assets/partners/gamma.png') ?>" alt="Gamma" width="120" class="zoom-hover">
                <img src="<?= base('assets/partners/copilot.png') ?>" alt="Copilot" width="120" class="zoom-hover">
                <img src="<?= base('assets/partners/perplexity.png') ?>" alt="Perplexity" width="120" class="zoom-hover">
                <img src="<?= base('assets/partners/claude.png') ?>" alt="Claude" width="120" class="zoom-hover">
            </div>
        </section>

        <section>
            <h2>Contact Us</h2>
            <a href="<?= base('contact/index') ?>"><button>Contact the Lab</button></a>
        </section>
        </div>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }
}