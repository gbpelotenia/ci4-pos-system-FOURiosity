<section class="page-heading">
    <div>
        <h1>POS Dashboard</h1>
        <p>Overview of accounts, inventory, and sales.</p>
    </div>

    <a class="button" href="<?= base_url('sales/new') ?>">Record Sale</a>
</section>

<section class="stats-grid">
    <article class="stat-card">
        <span>Total Products</span>
        <strong><?= number_format((int) $total_products) ?></strong>
    </article>

    <article class="stat-card">
        <span>Total Customers</span>
        <strong><?= number_format((int) $total_customers) ?></strong>
    </article>

    <article class="stat-card">
        <span>Total Staff</span>
        <strong><?= number_format((int) $total_staff) ?></strong>
    </article>

    <article class="stat-card">
        <span>Transactions</span>
        <strong><?= number_format((int) $total_transactions) ?></strong>
    </article>

    <article class="stat-card stat-card-wide">
        <span>Total Revenue</span>
        <strong>₱<?= number_format((float) $total_revenue, 2) ?></strong>
    </article>
</section>

<section class="dashboard-grid">
    <article class="panel">
        <div class="panel-heading">
            <h2>Low-Stock Products</h2>
            <a href="<?= base_url('products') ?>">View Products</a>
        </div>

        <?php if (empty($low_stock_products)): ?>
            <p class="muted">No products currently have five or fewer units.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock_products as $product): ?>
                            <tr>
                                <td><?= esc($product['name']) ?></td>
                                <td>
                                    <span class="stock-badge">
                                        <?= esc($product['stock_quantity']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </article>

    <article class="panel">
        <div class="panel-heading">
            <h2>Recent Sales</h2>
            <a href="<?= base_url('sales') ?>">View History</a>
        </div>

        <?php if (empty($recent_sales)): ?>
            <p class="muted">No sales have been recorded yet.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_sales as $sale): ?>
                            <tr>
                                <td><?= esc($sale['product_name']) ?></td>
                                <td><?= esc($sale['quantity']) ?></td>
                                <td>₱<?= number_format((float) $sale['total_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </article>
</section>
