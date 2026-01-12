<?php

Class NewsView {
    public function renderIndex(array $news):void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin - Equipments</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>News</h1>
        <?php $this->renderNews($news); ?>
        <?php require_once __DIR__ . '/../Shared/FooterLoader.php'; FooterLoader::render(); ?>
        </body>
        </html>
        <?php
    }

    public function renderNews(array $news):void
    {
        ?>
        <?php foreach($news as $newsItem): ?>
            <div>
                <img src="<?= htmlspecialchars($newsItem['photo_url']) ?>" alt="News Image">
                <h2><?= htmlspecialchars($newsItem['title']) ?></h2>
                <p><?= htmlspecialchars($newsItem['description']) ?></p>
                <p><?= htmlspecialchars($newsItem['published_at']) ?></p>
            </div>
        <?php endforeach; ?>
        <?php
    }

    public function renderDiaporama(array $news):void
    {
        ?>
        <html>
        <head>
            <style>
            .diaporama {
                position: relative;
                width: 100%;
                max-width: 800px;
                height: 400px;
                margin: auto;
                overflow: hidden;
            }

            .slides {
                display: flex;
                height: 100%;
                transition: transform 1s ease;
            }

            .slide {
                flex: 0 0 100%;
                height: 100%;
                position: relative;
                background-size: cover;
                background-position: center;
                color: white;
                display: flex;
                align-items: flex-end;
                padding: 20px;
                box-sizing: border-box;
            }

            .overlay {
                background: rgba(0, 0, 0, 0.5);
                padding: 10px;
                border-radius: 4px;
                max-width: 90%;
            }

            .overlay h2, .overlay p {
                margin: 5px 0;
            }
        </style>
        </head>
        <body>

        <div class="diaporama" id="diaporama">
            <div class="slides" id="slides">
                <?php foreach ($news as $newsItem): ?>
                    <div class="slide" style="background-image: url('<?= htmlspecialchars($newsItem['photo_url']) ?>');">
                        <div class="overlay">
                            <h2><?= htmlspecialchars($newsItem['title']) ?></h2>
                            <p><?= htmlspecialchars($newsItem['description']) ?></p>
                            <p><?= htmlspecialchars($newsItem['published_at']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!empty($news)): ?>
                    <div class="slide" style="background-image: url('<?= htmlspecialchars($news[0]['photo_url']) ?>');">
                        <div class="overlay">
                            <h2><?= htmlspecialchars($news[0]['title']) ?></h2>
                            <p><?= htmlspecialchars($news[0]['description']) ?></p>
                            <p><?= htmlspecialchars($news[0]['published_at']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            const slides = document.getElementById('slides');
            const diaporama = document.getElementById('diaporama');
            const totalSlides = slides.children.length;
            let index = 0;
            let interval = setInterval(nextSlide, 5000);

            function nextSlide() {
                index++;
                slides.style.transition = 'transform 1s ease';
                slides.style.transform = `translateX(-${index * 100}%)`;

                if (index === totalSlides - 1) {
                    setTimeout(() => {
                        slides.style.transition = 'none';
                        slides.style.transform = 'translateX(0%)';
                        index = 0;
                    }, 1000); // match transition duration
                }
            }

            diaporama.addEventListener('mouseenter', () => clearInterval(interval));
            diaporama.addEventListener('mouseleave', () => interval = setInterval(nextSlide, 5000));
        </script>
        </body>
        </html>
        <?php
    }
}