<!doctype html><html lang="en"><head><meta charset="utf-8"><title><?= esc($title) ?></title></head><body>
<h1>Add Customer</h1><p><a href="<?= site_url('customers') ?>">Back to Customers</a></p>
<?php if (session()->getFlashdata('errors')): ?><ul><?php foreach (session()->getFlashdata('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
<form action="<?= site_url('customers/create') ?>" method="post">
<?= csrf_field() ?><label>Full Name <input name="full_name" value="<?= old('full_name') ?>" required></label><br>
<label>Email <input type="email" name="email" value="<?= old('email') ?>" required></label><br>
<label>Phone <input name="phone" value="<?= old('phone') ?>" required></label><br><br><button type="submit">Save Customer</button>
</form></body></html>
