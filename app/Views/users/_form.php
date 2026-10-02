<?php
$errors = session()->getFlashdata('errors');
?>

<?php if ($errors): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<label>Username:</label><br>
<input type="text" name="username"
       value="<?= old('username', $user['username'] ?? '') ?>" required><br><br>

<label>Full Name:</label><br>
<input type="text" name="full_name"
       value="<?= old('full_name', $user['full_name'] ?? '') ?>" required><br><br>

<label>Password:</label><br>
<input type="password" name="password"><br>
<small>Leave blank when editing if you do not want to change it.</small><br><br>

<label>Confirm Password:</label><br>
<input type="password" name="password_confirm"><br><br>

<label>Avatar:</label><br>
<input type="file" name="avatar" accept="image/png,image/jpeg"><br><br>

<?php if (!empty($user['avatar'])): ?>
    <img src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>"
         width="100" alt="Current avatar"><br><br>
<?php endif; ?>

<button type="submit"><?= esc($buttonText) ?></button>
<a href="<?= site_url('users') ?>">Cancel</a>