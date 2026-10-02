<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'CI4 POS') ?> | CI4 POS</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body>
    <?= $this->include('layouts/navbar') ?>
    <main class="container page-wrap">
        <?php if ($message = session()->getFlashdata('success')): ?>
            <div class="alert success"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')): ?>
            <div class="alert error"><?= esc($message) ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>
