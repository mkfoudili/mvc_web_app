<?php

class MemberView {
    public function renderIndex(array $member, array $publications, array $projects, int $page, int $totalPages, ProjectView $projectView, $baseurl): void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>
                <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
            </title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></h1>
        <?php $this->renderMemberDetails($member); ?>

        <h2>Publications</h2>
        <?php $this->renderPublications($publications); ?>
        <h2 id="projects">Projects</h2>
        <?php
            $projectView->renderCards($projects, $page, $totalPages, $baseurl,"#projects");
        ?>
        </body>
        </html>
        <?php
    }

    public function renderMyProfile(array $member, array $publications, array $projects, int $page, int $totalPages, ProjectView $projectView, $baseurl, EventView $eventView, array $events, EquipmentView $equipmentView, array $reservations): void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>My Profile</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></h1>
        <?php $this->renderMemberDetails($member); ?>
        <a href="<?= base('member/edit?id=' . $member['id']) ?>">
            <button>Edit Profil</button>
        </a>

        <h2>My Publications</h2>
        <?php $this->renderPublications($publications); ?>
        <a href="<?= base('publication/create?id=' . $member['id']) ?>">
            <button>Add Publication</button>
        </a>

        <h2 id="projects">My Projects</h2>
        <?php
            $projectView->renderCards($projects, $page, $totalPages, $baseurl,"#projects");
        ?>
        <a href="<?= base('project/create?member_id=' . $member['id']) ?>">
            <button>Add Project</button>
        </a>
        <h2>My Events</h2>
        <?php
            $eventView->renderMyEvents($events);
        ?>
        <h2>My Reservations</h2>
        <?php
            $equipmentView->renderReservations($reservations);
        ?>
        </body>
        </html>
        <?php
    }

    public function renderMemberDetails(array $member): void {
        ?>
        <?php
        if (!empty($member['photo_url'])): ?>
            <img src="<?= htmlspecialchars($member['photo_url']) ?>" alt="Member photo">
        <?php endif; ?>

        <div class="block">
            <span class="label">Login:</span>
            <?= htmlspecialchars($member['login'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Role:</span>
            <?= htmlspecialchars($member['role_in_lab'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Specialty:</span>
            <?= htmlspecialchars($member['specialty_name'] ?? '-') ?>
        </div>

        <div class="block">
                <span class="label">Team:</span>
                <?= htmlspecialchars($member['team_name'] ?? '-') ?>
        </div>

        <div class="block">
            <span class="label">Website:</span>
            <?php if (!empty($member['website'])): ?>
                <a href="<?= htmlspecialchars($member['website']) ?>" target="_blank">
                    <?= htmlspecialchars($member['website']) ?>
                </a>
            <?php else: ?>
                -
            <?php endif; ?>
        </div>

        <div class="block">
            <span class="label">Bio:</span><br>
            <?= nl2br(htmlspecialchars($member['bio'] ?? '-')) ?>
        </div>
        <?php
    }

    public function renderPublications(array $publications): void{
        ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Authors</th>
                    <th>Date</th>
                    <th>DOI</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($publications)): ?>
                <tr>
                    <td colspan="6">No publications</td>
                </tr>
            <?php else: ?>
                <?php foreach ($publications as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['publication_type_id'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['authors'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['date_published'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['doi'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($p['url'])): ?>
                                <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank">Link</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

    public function renderEditProfile(array $member, array $specialties, array $teams): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Profile</title>
        </head>
        <body>
        <?php require_once __DIR__ . '/../Shared/NavLoader.php'; NavLoader::render(); ?>
        <h1>Edit Profile</h1>

        <form method="post" action="<?= base('member/update') ?>" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">
            <input type="hidden" name="login" value="<?= htmlspecialchars($member['login']) ?>">

            <div class="block">
                <label>First Name:</label><br>
                <input type="text" name="first_name" 
                       value="<?= htmlspecialchars($member['first_name']) ?>" required>
            </div>

            <div class="block">
                <label>Last Name:</label><br>
                <input type="text" name="last_name" 
                       value="<?= htmlspecialchars($member['last_name']) ?>" required>
            </div>

            <div class="block">
                <label>Profile Photo:</label><br>
                <input type="file" name="photo" accept="image/*">
                <?php if (!empty($member['photo_url'])): ?>
                    <br>
                    <img src="<?= htmlspecialchars($member['photo_url']) ?>" alt="Current photo" width="120">
                    <br>
                    <button type="submit" name="delete_photo" value="1">Delete Photo</button>
                <?php endif; ?>
            </div>

            <div class="block">
                <label>Role:</label><br>
                <input type="text" name="role_in_lab" 
                       value="<?= htmlspecialchars($member['role_in_lab'] ?? '') ?>">
            </div>

            <div class="block">
                <label>Specialty:</label><br>
                <select name="specialty_id">
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= ($member['specialty_id'] == $s['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label>Or add new specialty:</label><br>
                <input type="text" name="new_specialty" placeholder="New specialty name">
            </div>

            <div class="block">
                <label>Team:</label><br>
                <select name="team_id">
                    <option value="">-- Select Team --</option>
                    <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= ($member['team_id'] == $t['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="block">
                <label>Website:</label><br>
                <input type="url" name="website" 
                       value="<?= htmlspecialchars($member['website'] ?? '') ?>">
            </div>

            <div class="block">
                <label>Bio:</label><br>
                <textarea name="bio" rows="5" cols="50"><?= htmlspecialchars($member['bio'] ?? '') ?></textarea>
            </div>

            <br>
            <button type="submit">Save Changes</button>
            <a href="<?= base('member/index?id=' . htmlspecialchars($member['id'])) ?>">
                <button type="button">Cancel</button>
            </a>

        </form>

        </body>
        </html>
        <?php
    }
}