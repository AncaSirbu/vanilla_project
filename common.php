<?php
require_once 'config.php';
session_start();
function getDbConnection() {
    try {
        $connection = new PDO('mysql:host=' . SERVER_NAME . ';dbname=' . DB_NAME . '', DB_USERNAME, DB_PASSWORD);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (PDOException $exception) {
        echo 'Connection failed: ' . $exception->getMessage();
    }
}

function resizeImage($file, $width, $height)
{
    list($w, $h) = getimagesize($file);
    /* calculate new image size with ratio */
    $ratio = max($width / $w, $height / $h);
    $h = ceil($height / $ratio);
    $x = ($w - $width / $ratio) / 2;
    $w = ceil($width / $ratio);
    /* read binary data from image file */
    $imgString = file_get_contents($file);
    /* create image from string */
    $image = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($width, $height);
    imagecopyresampled($tmp, $image,
        0, 0,
        $x, 0,
        $width, $height,
        $w, $h);
    imagejpeg($tmp, $file, 100);
    /* cleanup memory */
    imagedestroy($image);
    return $file;
}

?>