<?php
$user = [];
$buttonText = 'Add Staff';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Staff</title>
</head>
<body>

<h1>Add Staff Member</h1>

<form action="<?= site_url('users/create') ?>" method="post" enctype="multipart/form-data">
    <?= view('users/_form', [
    'user' => $user,
    'buttonText' => $buttonText
]) ?>
</form>

</body>
</html>