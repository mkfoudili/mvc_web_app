<!DOCTYPE html>
<html>
<head>
    <title>Report Breakdown</title>
</head>
<body>

<h1>Report Breakdown</h1>

<p>
    Equipment: <strong><?= htmlspecialchars($equipment['name']) ?></strong>
</p>

<form method="post">
    <label>
        Description of the breakdown:
        <br>
        <textarea name="description" rows="4" cols="50" required></textarea>
    </label>
    <br><br>

    <button type="submit">Report</button>
    <a href="/equipment">Cancel</a>
</form>

</body>
</html>