<?php

Class EquipmentView {
    public function renderIndex($equipments):void{
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin - Equipments</title>
        </head>
        <body>

        <h1>Equipments</h1>
        <a href="/admin/equipment/add">
            <button>Add Equipment</button>
        </a>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>State</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($equipments)): ?>
                <tr>
                    <td colspan="7">No equipment found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($equipments as $equipment): ?>
                    <tr>
                        <td><?= htmlspecialchars($equipment['name']) ?></td>
                        <td><?= htmlspecialchars($equipment['type'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($equipment['state_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($equipment['description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($equipment['location'] ?? '-') ?></td>
                        <td>
                            <a href="/admin/equipment/edit?id=<?=$equipment['id'] ?>">
                                <button>Edit</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        </body>
        </html>
        <?php
    }

    public function renderAddForm(array $states, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Add Equipment</title>
        </head>
        <body>

        <h1>Add Equipment</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="/admin/equipment/store">

            <label>Name</label><br>
            <input type="text" name="name" required><br><br>

            <label>Type</label><br>
            <input type="text" name="type"><br><br>

            <label>State</label><br>
            <select name="state_id">
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"></textarea><br><br>

            <label>Location</label><br>
            <input type="text" name="location"><br><br>

            <button type="submit">Save Equipment</button>
            <a href="/admin/equipment/index"><button type="button">Cancel</button></a>
        </form>

        </body>
        </html>
        <?php
    }

    public function renderEditForm(array $equipment, array $states, string $error = null): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edit Equipment</title>
        </head>
        <body>

        <h1>Edit Equipment</h1>
        <?php if ($error): ?><div style="color:#b00;"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post" action="/admin/equipment/update">
            <input type="hidden" name="id" value="<?= (int)$equipment['id'] ?>">

            <label>Name</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required><br><br>

            <label>Type</label><br>
            <input type="text" name="type" value="<?= htmlspecialchars($equipment['type'] ?? '') ?>"><br><br>

            <label>State</label><br>
            <select name="state_id">
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $s['id']==$equipment['state_id']?'selected':'' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Description</label><br>
            <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($equipment['description'] ?? '') ?></textarea><br><br>

            <label>Location</label><br>
            <input type="text" name="location" value="<?= htmlspecialchars($equipment['location'] ?? '') ?>"><br><br>

            <button type="submit">Update Equipment</button>
            <a href="/admin/equipment/index"><button type="button">Cancel</button></a>
        </form>

        </body>
        </html>
        <?php
    }
}