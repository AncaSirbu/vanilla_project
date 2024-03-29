<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'User.php';
$user = new User('users');
$orderBy = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';
$users = $user->getUsers($orderBy, $order);
$pages = $user->getPaginationNo();

if(isset($_POST['export'])) {
    require_once 'Export.php';
    $export = new Export('users');
    $export->exportAllRecords();
    exit();
}
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
        <form method="post" action="users.php" style="float: right">
            <button type="submit" name="export">Export Records</button>
        </form>
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
