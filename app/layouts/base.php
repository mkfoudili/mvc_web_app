<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? 'Lab') ?></title>
    <link rel="icon" type="image/png" href="<?= base('assets/favicon/favicon.ico') ?>">
    <link rel="stylesheet" href="<?= base('css/base.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../view/Shared/NavLoader.php'; NavLoader::render(); ?>

    <main>
        <?= $content ?>
    </main>

    <?php require_once __DIR__ . '/../view/Shared/FooterLoader.php'; FooterLoader::render(); ?>
</body>
</html>