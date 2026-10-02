<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Staff Management</h1><p class="muted">Manage user accounts for the POS.</p></div></div>

<div class="panel form-panel">
    <h2>Add Staff</h2>
    <form method="post" action="<?= site_url('users/create') ?>" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <label>Username<input type="text" name="username" required></label>
        <label>Full Name<input type="text" name="full_name" required></label>
        <label>Password<input type="password" name="password" minlength="6" required></label>
        <label>Password Confirmation<input type="password" name="password_confirmation" minlength="6" required></label>
        <label>Avatar<input type="file" name="avatar" accept="image/png,image/jpeg,image/webp"></label>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Add Staff</button></div>
    </form>
</div>

<div class="panel">
    <div class="panel-header"><h2>Staff List</h2><span class="muted"><?= count($users) ?> accounts</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Avatar</th><th>Username</th><th>Full Name</th><th>Role</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php if ($user['avatar']): ?><img class="avatar" src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>" alt=""><?php else: ?><span class="avatar placeholder">?</span><?php endif; ?></td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['full_name']) ?></td>
                    <td><span class="badge"><?= esc($user['role']) ?></span></td>
                    <td class="actions">
                        <details><summary class="btn btn-small btn-outline">Edit</summary>
                            <form method="post" action="<?= site_url('users/update/' . $user['id']) ?>" enctype="multipart/form-data" class="inline-form">
                                <?= csrf_field() ?>
                                <input type="text" name="username" value="<?= esc($user['username']) ?>" required>
                                <input type="text" name="full_name" value="<?= esc($user['full_name']) ?>" required>
                                <input type="password" name="password" placeholder="New password (optional)">
                                <input type="password" name="password_confirmation" placeholder="Confirm new password">
                                <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp">
                                <button class="btn btn-primary btn-small" type="submit">Save</button>
                            </form>
                        </details>
                        <?php if ((int) $user['id'] !== (int) session()->get('user_id')): ?>
                        <form method="post" action="<?= site_url('users/delete/' . $user['id']) ?>" onsubmit="return confirm('Delete this staff account?');">
                            <?= csrf_field() ?><button class="btn btn-small btn-danger" type="submit">Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
