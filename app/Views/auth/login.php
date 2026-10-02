<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | CI4 POS</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="logo-box">POS</div>
        <h1>Point of Sale</h1>
        <p class="muted">CodeIgniter 4 POS System</p>
        <?php if ($message = session()->getFlashdata('success')): ?>
            <div class="alert success"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')): ?>
            <div class="alert error"><?= esc($message) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= site_url('login') ?>" class="stack-form">
            <?= csrf_field() ?>
            <label>Username<input type="text" name="username" value="<?= old('username') ?>" required autofocus></label>
            <label>Password<input type="password" name="password" required></label>
            <button class="btn btn-primary btn-block" type="submit">Login</button>
        </form>
        <div class="demo-note"><strong>Default:</strong> admin / admin123</div>
    </div>
</body>
</html>
