<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title><?= esc($title) ?></title></head>
<body><h1>Customers</h1><p><a href="<?= site_url('customers/new') ?>">Add Customer</a> | <a href="<?= site_url('products') ?>">Products</a></p>
<?php if (session()->getFlashdata('message')): ?><p><?= esc(session()->getFlashdata('message')) ?></p><?php endif; ?>
<table border="1" cellpadding="8"><tr><th>Full Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr>
<?php foreach ($customers as $customer): ?><tr><td><?= esc($customer['full_name']) ?></td><td><?= esc($customer['email']) ?></td><td><?= esc($customer['phone']) ?></td>
<td><a href="<?= site_url('customers/edit/' . $customer['id']) ?>">Edit</a> | <a href="<?= site_url('customers/delete/' . $customer['id']) ?>" onclick="return confirm('Delete this customer?')">Delete</a></td></tr><?php endforeach; ?></table>
</body></html>
