<?php

Class NewsView {
    public function renderIndex(array $news):void
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
}