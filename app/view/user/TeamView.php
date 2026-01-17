<?php
require_once __DIR__ . '/../../helpers/components.php';
Class TeamView{

    public function renderIndex(array $teams): void{
        $pageTitle = '<h1>Teams</h1>';
        $teamsListHtml = $this->renderTeamsList($teams);
        $pageHtml = $pageTitle . $teamsListHtml;
        layout('base', [
            'title'   => 'Teams',
            'content' => $pageHtml
        ]);
    }

    public function renderTeamsList(array $teams): string{
        if (empty($teams)){
            $teamListHtml ='<p>No teams found.</p>';
        }else{
            $teamListHtml = '<div class="section-block-list">';
            foreach ($teams as $team){
                $teamListHtml .= component('TeamCard', $team);
            }
            $teamListHtml .= '</div>';
        }
        return $teamListHtml;
    }
    public function renderTeams(array $teams) : void{
        ?>
        <div class="container stack">
            <?php if (empty($teams)): ?>
                <p>No teams found.</p>
                    <?php else: ?>
                        <div class="section-block-list">
                            <?php foreach ($teams as $team): ?>
                                <div class="section-block">
                                    <h2><?= htmlspecialchars($team['name']) ?></h2>
                                    <p><strong>Leader:</strong>
                                        <?= htmlspecialchars($team['leader_first_name'] ?? '-') ?>
                                        <?= htmlspecialchars($team['leader_last_name'] ?? '-') ?>
                                    </p>
                                    <p><strong>Domain:</strong> <?= htmlspecialchars($team['domain'] ?? '-') ?></p>
                                    <p><strong>Description:</strong><br>
                                        <?= nl2br(htmlspecialchars($team['description'] ?? '-')) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                <?php endif; ?>
        </div>

        </body>
        </html>
        <?php
    }
}