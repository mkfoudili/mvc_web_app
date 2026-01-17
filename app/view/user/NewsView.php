<?php
require_once __DIR__ . '/../../helpers/components.php';
Class NewsView {
    public function renderIndex(array $news):void
    {
        $pageTitle = '<h1>News</h1>';
        $newsListHtml = $this->renderNewsList($news);
        $pageHtml = $pageTitle . $newsListHtml;

        layout('base', [
            'title'   => 'Admin - News',
            'content' => $pageHtml
        ]);
    }

    public function renderNewsList(array $news):string
    {
        if (empty($news)){
            $newsListHtml = '<p>No news found.</p>';
        }
        else {
            $newsListHtml = '<div class="container">';
            foreach ($news as $item) {
                $newsListHtml .= component('NewsCard', $item);
            }
            $newsListHtml .= '</div>';
        }
        return $newsListHtml;
    }

    public function renderNews(array $news): void
    {
        ?>
        <div class="container">
        <?php foreach($news as $newsItem):
            $photo = !empty($newsItem['photo_url']) ? $newsItem['photo_url'] : 'assets/news/default.png';
        ?>
            <div class="news-item">
                <img src="<?= base($photo) ?>" alt="News Image">
                <div class="news-item-content">
                    <h2><?= htmlspecialchars($newsItem['title']) ?></h2>
                    <p><?= htmlspecialchars($newsItem['description']) ?></p>
                    <p><small><?= htmlspecialchars($newsItem['published_at']) ?></small></p>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
    }

    public function renderDiaporama(array $news):void
    {
        ?>
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
                background-repeat: no-repeat;
                color: white;
                display: flex;
                align-items: flex-end;
                padding: 20px;
                box-sizing: border-box;
                overflow: hidden;
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

        <div class="diaporama" id="diaporama">
            <div class="slides" id="slides">
                <?php 
                $defaultImage = '<?= base("assets/news/default.png") ?>';
                foreach ($news as $newsItem): 
                    $photo = !empty($newsItem['photo_url']) ? htmlspecialchars($newsItem['photo_url']) : 'assets/news/default.png';
                ?>
                    <div class="slide" style="background-image: url('<?= $photo ?>');">
                        <div class="overlay">
                            <h2><?= htmlspecialchars($newsItem['title']) ?></h2>
                            <p><?= htmlspecialchars($newsItem['description']) ?></p>
                            <p><?= htmlspecialchars($newsItem['published_at']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!empty($news)): 
                    $firstPhoto = !empty($news[0]['photo_url']) ? htmlspecialchars($news[0]['photo_url']) : 'assets/news/default.png';
                ?>
                    <div class="slide" style="background-image: url('<?= $firstPhoto ?>');">
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
                    }, 1000);
                }
            }

            diaporama.addEventListener('mouseenter', () => clearInterval(interval));
            diaporama.addEventListener('mouseleave', () => interval = setInterval(nextSlide, 5000));
        </script>
        <?php
    }
}