<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div><h1>Dashboard</h1><p class="muted">Overview of your POS system.</p></div>
    <a class="btn btn-primary" href="<?= site_url('sales') ?>">Record Sale</a>
</div>

<div class="cards">
    <div class="stat-card"><span>Products</span><strong><?= esc($totalProducts) ?></strong></div>
    <div class="stat-card"><span>Customers</span><strong><?= esc($totalCustomers) ?></strong></div>
    <div class="stat-card"><span>Staff</span><strong><?= esc($totalStaff) ?></strong></div>
    <div class="stat-card"><span>Transactions</span><strong><?= esc($totalTransactions) ?></strong></div>
    <div class="stat-card stat-wide"><span>Total Sales</span><strong>₱<?= number_format($totalSales, 2) ?></strong></div>
</div>

<div class="grid-2">
    <section class="panel">
        <div class="panel-header"><h2>Recent Sales</h2><a href="<?= site_url('sales') ?>">View all</a></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Product</th><th>Customer</th><th>Qty</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach ($recentSales as $sale): ?>
                    <tr>
                        <td><?= esc($sale['product_name']) ?></td>
                        <td><?= esc($sale['customer_name'] ?: 'Walk-in') ?></td>
                        <td><?= esc($sale['quantity']) ?></td>
                        <td>₱<?= number_format((float) $sale['total_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (! $recentSales): ?><tr><td colspan="4" class="empty">No sales yet.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header"><h2>Low Stock</h2><a href="<?= site_url('products') ?>">Manage</a></div>
        <div class="stock-list">
            <?php foreach ($lowStock as $product): ?>
                <div class="stock-row"><span><?= esc($product['name']) ?></span><strong><?= esc($product['stock_quantity']) ?></strong></div>
            <?php endforeach; ?>
            <?php if (! $lowStock): ?><div class="empty">All products have more than 5 units in stock.</div><?php endif; ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
