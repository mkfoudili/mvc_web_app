<?php
require __DIR__ . '/../../helpers/components.php';

class NewsCard extends Component {
    protected function template(): void {
        $photo = $this->has('photo_url') 
                 ? $this->prop('photo_url') 
                 : 'assets/news/default.png';
        ?>
        <div class="news-item">
            <img src="<?= base($photo) ?>" alt="News Image">
            <div class="news-item-content">
                <h2><?= e($this->prop('title')) ?></h2>
                <p><?= e($this->prop('description')) ?></p>
                <p><small><?= e($this->prop('published_at')) ?></small></p>
            </div>
        </div>
        <?php
    }
}