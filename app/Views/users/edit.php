<?php
$buttonText = 'Update Staff';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff</title>
</head>
<body>

<h1>Edit Staff Member</h1>

<form action="<?= site_url('users/update/' . $user['id']) ?>"
      method="post"
      enctype="multipart/form-data">

    <?= view('users/_form', [
    'user' => $user,
    'buttonText' => $buttonText
]) ?>
</form>

</body>
</html>