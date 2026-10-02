<!doctype html><html lang="en"><head><meta charset="utf-8"><title><?= esc($title) ?></title></head><body>
<h1>Add Product</h1><p><a href="<?= site_url('products') ?>">Back to Products</a></p>
<?php if (session()->getFlashdata('errors')): ?><ul><?php foreach (session()->getFlashdata('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
<form action="<?= site_url('products/create') ?>" method="post" enctype="multipart/form-data">
<?= csrf_field() ?><label>Name <input name="name" value="<?= old('name') ?>" required></label><br>
<label>Price <input type="number" name="price" step="0.01" min="0" value="<?= old('price') ?>" required></label><br>
<label>Stock Quantity <input type="number" name="stock_quantity" min="0" value="<?= old('stock_quantity', '0') ?>" required></label><br>
<label>Image <input type="file" name="image" accept="image/*"></label><br><br><button type="submit">Save Product</button>
</form></body></html>
