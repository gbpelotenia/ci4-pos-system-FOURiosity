<section class="page-heading">
    <div>
        <h1>Sales History</h1>
        <p>Completed transactions, newest first.</p>
    </div>

    <a class="button" href="<?= base_url('sales/new') ?>">Record Sale</a>
</section>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<?php if (empty($sales)): ?>
    <div class="empty-state">
        <p>No sales have been recorded yet.</p>
        <a class="button" href="<?= base_url('sales/new') ?>">Record the First Sale</a>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Staff</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= esc($sale['id']) ?></td>
                        <td><?= esc($sale['product_name']) ?></td>
                        <td><?= esc($sale['customer_name']) ?></td>
                        <td><?= esc($sale['staff_name']) ?></td>
                        <td><?= esc($sale['quantity']) ?></td>
                        <td>₱<?= number_format((float) $sale['total_price'], 2) ?></td>
                        <td><?= esc(date('M j, Y g:i A', strtotime($sale['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
