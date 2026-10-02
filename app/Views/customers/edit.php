<!doctype html><html lang="en"><head><meta charset="utf-8"><title><?= esc($title) ?></title></head><body>
<h1>Edit Customer</h1><p><a href="<?= site_url('customers') ?>">Back to Customers</a></p>
<?php if (session()->getFlashdata('errors')): ?><ul><?php foreach (session()->getFlashdata('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
<form action="<?= site_url('customers/update/' . $customer['id']) ?>" method="post">
<?= csrf_field() ?><label>Full Name <input name="full_name" value="<?= old('full_name', $customer['full_name']) ?>" required></label><br>
<label>Email <input type="email" name="email" value="<?= old('email', $customer['email']) ?>" required></label><br>
<label>Phone <input name="phone" value="<?= old('phone', $customer['phone']) ?>" required></label><br><br><button type="submit">Update Customer</button>
</form></body></html>
