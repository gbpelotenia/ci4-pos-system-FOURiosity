<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Products</h1><p class="muted">Manage products, prices, stock, and images.</p></div></div>

<div class="panel form-panel">
    <h2>Add Product</h2>
    <form method="post" action="<?= site_url('products/create') ?>" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <label>Name<input type="text" name="name" required></label>
        <label>Price<input type="number" name="price" min="0" step="0.01" required></label>
        <label>Stock Quantity<input type="number" name="stock_quantity" min="0" step="1" required></label>
        <label>Image<input type="file" name="image" accept="image/png,image/jpeg,image/webp"></label>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Add Product</button></div>
    </form>
</div>

<div class="panel">
    <div class="panel-header"><h2>Product List</h2><span class="muted"><?= count($products) ?> products</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php if ($product['image']): ?><img class="thumb" src="<?= base_url('uploads/products/' . $product['image']) ?>" alt=""><?php else: ?><span class="no-image">—</span><?php endif; ?></td>
                    <td><?= esc($product['name']) ?></td>
                    <td>₱<?= number_format((float) $product['price'], 2) ?></td>
                    <td><span class="badge <?= ((int) $product['stock_quantity'] <= 5) ? 'badge-warn' : '' ?>"><?= esc($product['stock_quantity']) ?></span></td>
                    <td class="actions">
                        <details><summary class="btn btn-small btn-outline">Edit</summary>
                            <form method="post" action="<?= site_url('products/update/' . $product['id']) ?>" enctype="multipart/form-data" class="inline-form">
                                <?= csrf_field() ?>
                                <input type="text" name="name" value="<?= esc($product['name']) ?>" required>
                                <input type="number" name="price" value="<?= esc($product['price']) ?>" min="0" step="0.01" required>
                                <input type="number" name="stock_quantity" value="<?= esc($product['stock_quantity']) ?>" min="0" required>
                                <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
                                <button class="btn btn-primary btn-small" type="submit">Save</button>
                            </form>
                        </details>
                        <form method="post" action="<?= site_url('products/delete/' . $product['id']) ?>" onsubmit="return confirm('Delete this product?');">
                            <?= csrf_field() ?><button class="btn btn-small btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (! $products): ?><tr><td colspan="5" class="empty">No products yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
