<?php
require_once __DIR__ . '/../../helpers/components.php';

class TeamCard extends Component {
    protected function template(): void {
        ?>
        <div class="section-block">
            <h2><?= e($this->prop('name')) ?></h2>
            <p><strong>Leader:</strong>
                <?= e($this->prop('leader_first_name') ?? '-') ?>
                <?= e($this->prop('leader_last_name') ?? '-') ?>
            </p>
            <p>
                <strong>Domain:</strong>
                <?= e($this->prop('domain') ?? '-') ?>
            </p>
            <p>
                <strong>Description:</strong>
                <br>
                <?= nl2br(e($this->prop('description') ?? '-')) ?>
            </p>
            <h3>Members</h3>
            <?php if (!$this->has('members')): ?>
                <p>No members assigned.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($this->prop('members') as $member): ?>
                        <li>
                            <a href="<?= base('member/index?id=' . (int)$member['id']) ?>">
                                <?= e($member['first_name'] . ' ' . $member['last_name']) ?>
                            </a>
                            <?php if (!empty($member['role_in_team'])): ?>
                                (<?= e($member['role_in_team']) ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }
}