<section class="page-heading">
    <div>
        <h1>Record Sale</h1>
        <p>Select a product, an optional customer, and the quantity sold.</p>
    </div>

    <a class="button button-secondary" href="<?= base_url('sales') ?>">
        Sales History
    </a>
</section>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<?php $errors = session()->getFlashdata('errors'); ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <strong>Please correct the following:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (empty($products)): ?>
    <div class="alert alert-warning">
        There are no products with available stock. Add or restock a product first.
    </div>
<?php else: ?>
    <form class="form-card" action="<?= base_url('sales') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="product_id">Product <span class="required">*</span></label>
            <select id="product_id" name="product_id" required>
                <option value="">Select a product</option>

                <?php foreach ($products as $product): ?>
                    <option
                        value="<?= esc($product['id']) ?>"
                        <?= (string) old('product_id') === (string) $product['id'] ? 'selected' : '' ?>
                    >
                        <?= esc($product['name']) ?> —
                        ₱<?= number_format((float) $product['price'], 2) ?> —
                        Stock: <?= esc($product['stock_quantity']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small>The price and available stock are verified again when submitted.</small>
        </div>

        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id">
                <option value="">Walk-in Customer</option>

                <?php foreach ($customers as $customer): ?>
                    <option
                        value="<?= esc($customer['id']) ?>"
                        <?= (string) old('customer_id') === (string) $customer['id'] ? 'selected' : '' ?>
                    >
                        <?= esc($customer['full_name']) ?>
                        <?php if (! empty($customer['email'])): ?>
                            — <?= esc($customer['email']) ?>
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity <span class="required">*</span></label>
            <input
                type="number"
                id="quantity"
                name="quantity"
                min="1"
                step="1"
                value="<?= esc(old('quantity', '1')) ?>"
                required
            >
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Record Sale</button>
            <a class="button button-secondary" href="<?= base_url('dashboard') ?>">Cancel</a>
        </div>
    </form>
<?php endif; ?>
