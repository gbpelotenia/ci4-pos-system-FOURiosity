<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Management</title>
</head>
<body>

<h1>Staff Management</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;">
        <?= esc(session()->getFlashdata('success')) ?>
    </p>
<?php endif; ?>

<a href="<?= site_url('users/new') ?>">Add New Staff</a> |
<a href="<?= site_url('logout') ?>">Logout</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Avatar</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>"
                         width="70" alt="Avatar">
                <?php else: ?>
                    No avatar
                <?php endif; ?>
            </td>

            <td><?= esc($user['username']) ?></td>
            <td><?= esc($user['full_name']) ?></td>

            <td>
                <a href="<?= site_url('users/edit/' . $user['id']) ?>">
                    Edit
                </a>

                <form action="<?= site_url('users/delete/' . $user['id']) ?>"
                      method="post"
                      style="display: inline;"
                      onsubmit="return confirm('Delete this staff member?');">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>