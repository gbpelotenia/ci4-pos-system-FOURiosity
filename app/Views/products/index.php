<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title><?= esc($title) ?></title></head>
<body>
<h1>Products</h1>
<p><a href="<?= site_url('products/new') ?>">Add Product</a> | <a href="<?= site_url('customers') ?>">Customers</a></p>
<?php if (session()->getFlashdata('message')): ?><p><?= esc(session()->getFlashdata('message')) ?></p><?php endif; ?>
<table border="1" cellpadding="8"><tr><th>Name</th><th>Price</th><th>Stock</th><th>Image</th><th>Actions</th></tr>
<?php foreach ($products as $product): ?><tr>
<td><?= esc($product['name']) ?></td><td><?= esc($product['price']) ?></td><td><?= esc($product['stock_quantity']) ?></td>
<td><?php if (!empty($product['image'])): ?><img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="Product image" width="60"><?php endif; ?></td>
<td><a href="<?= site_url('products/edit/' . $product['id']) ?>">Edit</a> | <a href="<?= site_url('products/delete/' . $product['id']) ?>" onclick="return confirm('Delete this product?')">Delete</a></td>
</tr><?php endforeach; ?></table>
</body></html>
