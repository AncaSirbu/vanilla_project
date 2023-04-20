<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Pagination.php';
$pagination = new Pagination('users');

$users = $pagination->getUsers();
$pages = $pagination->getPaginationNo();


?>

<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="col-md-12 head">
    <div style="float: right">
        <a href="exportData.php"  class="btn btn-success"><i class="dwn"></i> Export</a>
    </div>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Email</th>
    </tr>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td>
                <?= $user->id ?>
            </td>
            <td>
                <img src="<?= $user->image ?>" alt="image">
            </td>
            <td>
                <?= $user->name ?>
            </td>
            <td>
                <?= $user->email ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td>
            <?php for($i = 1; $i <= $pages; $i++) : ?>
                <a href="?page=<?= $i;?>"><?= $i;?></a>
            <?php endfor; ?>
        </td>
    </tr>
</table>
</body>
</html>
