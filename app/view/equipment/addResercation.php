<!DOCTYPE html>
<html>
<head>
    <title>Add reservation</title>
</head>
<body>

<h1>Add reservation</h1>

<p>
    Equipment: <strong><?= htmlspecialchars($equipment['name']) ?></strong>
</p>

<form method="post">
    <label>
        From:
        <input
            type="datetime-local"
            name="reserved_from"
            value="<?= date('Y-m-d\TH:i') ?>"
            required
        >
    </label>
    <br><br>

    <label>
        To:
        <input
            type="datetime-local"
            name="reserved_to"
            required
        >
    </label>
    <br><br>

    <label>
        Purpose (optional):
        <br>
        <textarea name="purpose" rows="3" cols="40"></textarea>
    </label>
    <br><br>

    <button type="submit">Save reservation</button>
    <a href="/equipment">Cancel</a>
</form>

</body>
</html>