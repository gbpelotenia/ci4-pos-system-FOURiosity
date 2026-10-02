<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Sales</h1><p class="muted">Record transactions and review sales history.</p></div></div>

<div class="panel form-panel">
    <h2>Record Sale</h2>
    <form method="post" action="<?= site_url('sales/create') ?>" class="form-grid sale-form">
        <?= csrf_field() ?>
        <label>Product
            <select name="product_id" id="product_id" required>
                <option value="">Select product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id'] ?>" data-price="<?= esc($product['price']) ?>" data-stock="<?= $product['stock_quantity'] ?>">
                        <?= esc($product['name']) ?> — ₱<?= number_format((float) $product['price'], 2) ?> (Stock: <?= $product['stock_quantity'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Customer (optional)
            <select name="customer_id">
                <option value="">Walk-in customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['id'] ?>"><?= esc($customer['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Quantity<input type="number" name="quantity" id="quantity" min="1" value="1" required></label>
        <div class="sale-total"><span>Estimated Total</span><strong id="sale_total">₱0.00</strong></div>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Record Sale</button></div>
    </form>
</div>

<div class="panel">
    <div class="panel-header"><h2>Sales History</h2><span class="muted"><?= count($sales) ?> shown</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Product</th><th>Customer</th><th>Staff</th><th>Qty</th><th>Unit Price</th><th>Total Price</th></tr></thead>
            <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= esc(date('M d, Y h:i A', strtotime($sale['created_at']))) ?></td>
                    <td><?= esc($sale['product_name']) ?></td>
                    <td><?= esc($sale['customer_name'] ?: 'Walk-in') ?></td>
                    <td><?= esc($sale['staff_name']) ?></td>
                    <td><?= esc($sale['quantity']) ?></td>
                    <td>₱<?= number_format((float) $sale['unit_price'], 2) ?></td>
                    <td><strong>₱<?= number_format((float) $sale['total_price'], 2) ?></strong></td>
                </tr>
            <?php endforeach; ?>
            <?php if (! $sales): ?><tr><td colspan="7" class="empty">No sales yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
(function () {
    const product = document.getElementById('product_id');
    const quantity = document.getElementById('quantity');
    const total = document.getElementById('sale_total');
    function update() {
        const option = product.options[product.selectedIndex];
        const price = option ? parseFloat(option.dataset.price || '0') : 0;
        const stock = option ? parseInt(option.dataset.stock || '0', 10) : 0;
        const qty = Math.max(0, parseInt(quantity.value || '0', 10));
        quantity.max = stock || 1;
        total.textContent = '₱' + (price * qty).toFixed(2);
    }
    product.addEventListener('change', update);
    quantity.addEventListener('input', update);
    update();
})();
</script>
<?= $this->endSection() ?>
