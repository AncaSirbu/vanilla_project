<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Pagination.php';
$pagination = new Pagination('users');
$orderBy = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
$users = $pagination->getUsers($orderBy, $order);
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
    <div>
        <h2>All users</h2>
        <a href="exportData.php" class="export"><i class="dwn"></i> Export</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th><a href="?sort=name&order=<?= $order === 'asc' ? 'desc' : 'asc'?>">Name</a></th>
            <th><a href="?sort=email&order=<?= $order == 'asc' ? 'desc' : 'asc' ?>">Email</a></th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td>
                    <?= $user->id ?>
                </td>
                <td>
                    <img src="<?= $user->image ?>" alt="image" class="profile-img">
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
            <td colspan="4">
                <?php for($i = 1; $i <= $pages; $i++) : ?>
                    <a href="?page=<?= $i;?>"><?= $i;?></a>
                <?php endfor; ?>
            </td>
        </tr>
    </table>
</body>
</html>
