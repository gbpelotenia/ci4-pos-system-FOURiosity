<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Dashboard</h1><p class="muted">Live overview of sales, profit, inventory, and expenses.</p></div><a class="btn btn-primary" href="<?= site_url('sales') ?>">+ Record Sale</a></div>

<div class="cards dashboard-cards">
 <div class="stat-card accent-blue"><span>Today's Sales</span><strong>₱<?= number_format($todaySales, 2) ?></strong><small><?= $todayTransactions ?> transaction<?= $todayTransactions === 1 ? '' : 's' ?></small></div>
 <div class="stat-card accent-green"><span>Today's Gross Profit</span><strong>₱<?= number_format($todayProfit, 2) ?></strong><small>Sales minus product cost</small></div>
 <div class="stat-card accent-purple"><span>Total Sales</span><strong>₱<?= number_format($totalSales, 2) ?></strong><small>All recorded sales</small></div>
 <div class="stat-card accent-orange"><span>This Month's Expenses</span><strong>₱<?= number_format($monthExpenses, 2) ?></strong><small>Operating expenses</small></div>
</div>
<div class="quick-stats"><div><strong><?= esc($totalProducts) ?></strong><span>Products</span></div><div><strong><?= esc($totalCustomers) ?></strong><span>Customers</span></div><div><strong><?= esc($totalStaff) ?></strong><span>Staff</span></div><div><strong><?= esc($totalTransactions) ?></strong><span>Transactions</span></div><div><strong><?= count($categories) ?></strong><span>Categories</span></div><div><strong><?= count($suppliers) ?></strong><span>Suppliers</span></div></div>

<div class="grid-2 dashboard-main">
 <section class="panel chart-panel"><div class="panel-header"><div><h2>Sales Overview</h2><span class="muted">Last 7 days</span></div></div><div class="chart-box"><canvas id="salesChart"></canvas></div></section>
 <section class="panel"><div class="panel-header"><h2>Top-Selling Products</h2><span class="muted">All time</span></div><div class="rank-list"><?php foreach ($topProducts as $index => $product): ?><div class="rank-row"><span class="rank-number"><?= $index + 1 ?></span><div><strong><?= esc($product['name']) ?></strong><small><?= esc($product['units_sold']) ?> units</small></div><b>₱<?= number_format((float) $product['revenue'], 2) ?></b></div><?php endforeach; ?><?php if (! $topProducts): ?><div class="empty">Record sales to see top products.</div><?php endif; ?></div></section>
</div>

<div class="grid-2">
 <section class="panel"><div class="panel-header"><h2>Recent Transactions</h2><a href="<?= site_url('sales') ?>">View all</a></div><div class="table-wrap"><table><thead><tr><th>Product</th><th>Customer</th><th>Qty</th><th>Total</th></tr></thead><tbody><?php foreach ($recentSales as $sale): ?><tr><td><?= esc($sale['product_name']) ?></td><td><?= esc($sale['customer_name'] ?: 'Walk-in') ?></td><td><?= esc($sale['quantity']) ?></td><td>₱<?= number_format((float) $sale['total_price'], 2) ?></td></tr><?php endforeach; ?><?php if (! $recentSales): ?><tr><td colspan="4" class="empty">No sales yet.</td></tr><?php endif; ?></tbody></table></div></section>
 <section class="panel"><div class="panel-header"><h2>Low-Stock Alerts</h2><a href="<?= site_url('products') ?>">Manage</a></div><div class="stock-list"><?php foreach ($lowStock as $product): ?><div class="stock-row"><span><?= esc($product['name']) ?></span><strong class="stock-pill"><?= esc($product['stock_quantity']) ?> left</strong></div><?php endforeach; ?><?php if (! $lowStock): ?><div class="empty">All products have more than 5 units.</div><?php endif; ?></div></section>
</div>

<section class="panel" id="expenses"><div class="panel-header"><div><h2>Expenses Report</h2><span class="muted">Track operating costs</span></div></div>
 <form method="post" action="<?= site_url('business/expense') ?>" class="form-grid compact-form"><?= csrf_field() ?><label>Description<input name="description" required placeholder="Electricity, delivery..."></label><label>Amount<input type="number" name="amount" min="0.01" step="0.01" required></label><label>Date<input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required></label><div class="form-actions"><button class="btn btn-primary" type="submit">Add Expense</button></div></form>
 <div class="expense-list"><?php foreach ($recentExpenses as $expense): ?><div class="expense-row"><div><strong><?= esc($expense['description']) ?></strong><small><?= date('M j, Y', strtotime($expense['expense_date'])) ?></small></div><b>₱<?= number_format((float) $expense['amount'], 2) ?></b><form method="post" action="<?= site_url('business/expense/delete/' . $expense['id']) ?>"><?= csrf_field() ?><button class="icon-delete" title="Delete expense">×</button></form></div><?php endforeach; ?><?php if (! $recentExpenses): ?><div class="empty">No expenses recorded yet.</div><?php endif; ?></div>
</section>

<div class="grid-2">
 <section class="panel"><div class="panel-header"><h2>Categories</h2><span class="badge"><?= count($categories) ?></span></div><form method="post" action="<?= site_url('business/category') ?>" class="inline-add"><?= csrf_field() ?><input name="name" placeholder="New category" required><button class="btn btn-primary" type="submit">Add</button></form><div class="tag-list"><?php foreach ($categories as $category): ?><span class="tag"><?= esc($category['name']) ?></span><?php endforeach; ?><?php if (! $categories): ?><span class="muted">Add categories such as Food, Drinks, or Supplies.</span><?php endif; ?></div></section>
 <section class="panel"><div class="panel-header"><h2>Suppliers</h2><span class="badge"><?= count($suppliers) ?></span></div><form method="post" action="<?= site_url('business/supplier') ?>" class="supplier-form"><?= csrf_field() ?><input name="name" placeholder="Supplier name" required><input name="contact_person" placeholder="Contact person"><input name="phone" placeholder="Phone"><button class="btn btn-primary" type="submit">Add</button></form><div class="tag-list"><?php foreach ($suppliers as $supplier): ?><span class="tag"><?= esc($supplier['name']) ?></span><?php endforeach; ?><?php if (! $suppliers): ?><span class="muted">Add your product suppliers here.</span><?php endif; ?></div></section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>new Chart(document.getElementById('salesChart'),{type:'line',data:{labels:<?= json_encode($chartLabels) ?>,datasets:[{data:<?= json_encode($chartValues) ?>,borderColor:'#4f46e5',backgroundColor:'rgba(79,70,229,.10)',fill:true,tension:.38,pointRadius:4}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'₱'+v.toLocaleString()}},x:{grid:{display:false}}}}});</script>
<?= $this->endSection() ?>
