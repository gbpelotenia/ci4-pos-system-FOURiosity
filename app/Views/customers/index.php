<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Customers</h1><p class="muted">Manage customer records.</p></div></div>

<div class="panel form-panel">
    <h2>Add Customer</h2>
    <form method="post" action="<?= site_url('customers/create') ?>" class="form-grid">
        <?= csrf_field() ?>
        <label>Full Name<input type="text" name="full_name" required></label>
        <label>Email<input type="email" name="email"></label>
        <label>Phone<input type="text" name="phone"></label>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Add Customer</button></div>
    </form>
</div>

<div class="panel">
    <div class="panel-header"><h2>Customer List</h2><span class="muted"><?= count($customers) ?> customers</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Full Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= esc($customer['full_name']) ?></td>
                    <td><?= esc($customer['email'] ?: '—') ?></td>
                    <td><?= esc($customer['phone'] ?: '—') ?></td>
                    <td class="actions">
                        <details><summary class="btn btn-small btn-outline">Edit</summary>
                            <form method="post" action="<?= site_url('customers/update/' . $customer['id']) ?>" class="inline-form">
                                <?= csrf_field() ?>
                                <input type="text" name="full_name" value="<?= esc($customer['full_name']) ?>" required>
                                <input type="email" name="email" value="<?= esc($customer['email']) ?>">
                                <input type="text" name="phone" value="<?= esc($customer['phone']) ?>">
                                <button class="btn btn-primary btn-small" type="submit">Save</button>
                            </form>
                        </details>
                        <form method="post" action="<?= site_url('customers/delete/' . $customer['id']) ?>" onsubmit="return confirm('Delete this customer?');">
                            <?= csrf_field() ?><button class="btn btn-small btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (! $customers): ?><tr><td colspan="4" class="empty">No customers yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
