<?php
require_once __DIR__ . '/../../helpers/components.php';
class Button extends Component {
    protected function template(): void {
        $type   = $this->prop('type') ?? 'button'; //'link' wela 'button'
        $label  = $this->prop('label') ?? 'Button';
        $attrs  = $this->prop('attrs') ?? [];
        $href   = $this->prop('href') ?? null;

        if ($type === 'link' && !empty($href)) {
            ?>
            <a href="<?= e($href) ?>">
                <button <?= $this->renderAttributes($attrs) ?>><?= e($label) ?></button>
            </a>
            <?php
        } else {
            ?>
            <button type="button" <?= $this->renderAttributes($attrs) ?>><?= e($label) ?></button>
            <?php
        }
    }
}