<?php
require_once __DIR__ . '/../../helpers/components.php';

class Table extends Component {
    protected function template(): void {
        ?>
        <div class="table-wrapper">
            <table border="1" cellpadding="5" cellspacing="0" class="sortable-table">
                <thead>
                    <tr>
                        <?php foreach ($this->prop('headers') as $header): ?>
                            <th><?= e($header) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->prop('rows') as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td>
                                    <?php
                                    if (is_array($cell)) {
                                        switch ($cell['type']) {
                                            case 'link':
                                                if (!empty($cell['href'])) {
                                                    ?><a href="<?= e($cell['href']) ?>" target="_blank"><?= e($cell['label'] ?? 'Link') ?></a><?php
                                                } else {
                                                    echo '-';
                                                }
                                                break;
                                            case 'button':
                                                ?><button type="button" <?= $this->renderAttributes($cell['attrs'] ?? []) ?>><?= e($cell['label']) ?></button><?php
                                                break;
                                            case 'text':
                                            default:
                                                echo e($cell['value'] ?? '-');
                                                break;
                                        }
                                    } else {
                                        echo e($cell ?? '-');
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
