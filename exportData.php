<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'common.php';
$connection = getDbConnection();
$stm = $connection->prepare('SELECT * FROM users');
$stm->execute();
$users = $stm->fetchAll(PDO::FETCH_OBJ);

if ($users) {
    $delimiter = ',';
    $fileName = 'export-users_'. date('Y-m-d') . '.csv';
    $f = fopen('php://memory', 'w');
    $fields = ['ID', 'NAME', 'EMAIL', 'IMAGE'];
    fputcsv($f, $fields, $delimiter);

    foreach ($users as $user) {
        $data = [$user->id, $user->name, $user->email, $user->image];
        fputcsv($f, $data, $delimiter);
    }
    // Move back to beginning of file
    fseek($f, 0);
    // Set headers to download file rather than displayed
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Encoding: UTF-8');
    header('Content-Disposition: attachment; filename="' . $fileName . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
}
?>