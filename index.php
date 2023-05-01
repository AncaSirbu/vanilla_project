<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'common.php';
$userData['username'] = strip_tags($_POST['username'] ?? null);
$userData['email'] = $_POST['email'] ?? null;
$userData['image'] = $_FILES['image'] ?? null;
$userData['consent'] = $_POST['consent'] ?? false;

if (isset($_POST['submit'])) {
    if (!$userData['username']) {
        $inputErrors['userNameError'] = 'Enter a username';
    }

    if (!$userData['email']) {
        $inputErrors['userEmailError'] = 'Enter a email address';
    } else {
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $inputErrors['userEmailError']  = "Invalid email format";
        }
    }

    if (!$userData['image'] || !($userData['image']["size"] ?? 0)) {
        $inputErrors['imageError'] = 'Please choose an image.';
    }

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileType = mime_content_type($_FILES['image']['tmp_name']);
        $image = [
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
        ];
        if (!in_array($imageFileType, $image)) {
            $inputErrors['imageError'] = 'Only JPG, JPEG, PNG, GIF, JPE, BMP files are allowed.';
        }
        $imageInfo = getimagesize($_FILES["image"]["tmp_name"]);
        if ($imageInfo) {
            $imageWidth = $imageInfo[0];
            $imageHeight = $imageInfo[1];
            if ($imageWidth > 500 && $imageHeight > 500) {
                resizeImage($_FILES["image"]["tmp_name"], 500, 500);
            }
        }

    } else {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.',
        ];
        $inputErrors['imageError'] = $uploadErrors[$_FILES['image']['error']] ?? 'Image upload error.';
    }
    if (!($inputErrors['imageError'] ?? '') && !$userData['consent']) {
        $inputErrors['userConsentError'] = 'Bad request';
    }

    if (!count($inputErrors)) {
        $pathImage = 'uploads/' . time() . $_FILES['image']['name'];
        $parameters = [
            $_POST['username'],
            $_POST['email'],
            $_POST['consent']
        ];
        $sql = 'INSERT INTO users (name, email, consent, image) VALUES (?, ?, ?, ?)';
        $parameters[] = $pathImage;

        move_uploaded_file($_FILES['image']['tmp_name'], $pathImage);
        $connection = getDbConnection();
        $insertUser = $connection->prepare($sql);
        $insertUser->execute($parameters);
        header('Location: users.php');
        die();
    }
}

?>

<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>

</head>
<body>
<div class="row">
    <div>
        <img src="img.png" alt="" class="col-3">
    </div>
    <div class="col-9 login-form">
        <form action="index.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="username">Name</label>
                <br>
                <input type="text" name="username" id="username" placeholder="Enter your name">
                <br>
                <span class="error">
                    <?= $inputErrors['userNameError'] ?? ''; ?>
                </span>
                <br>
                <label for="email">Email</label>
                <br>
                <input type="email" name="email" id="email" placeholder="Enter a valid email address">
                <br>
                <span class="error">
                    <?= $inputErrors['userEmailError'] ?? ''; ?>
                </span>
                <br>
                <label for="image">Image</label>
                <br>
                <input type="file" id="image" name="image" placeholder="upload your photo">
                <br>
                <span class="error">
                  <?= $inputErrors['imageError'] ?? ''; ?>
                </span>
                <br>
                <input type="checkbox" id="consent" name="consent" value="1"> <label for="consent">I accept the Terms of Service</label>
                <br>
                <span class="error">
                    <?= $inputErrors['userConsentError'] ?? ''; ?>
                </span>
                <br>
                <button type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>

</div>

</body>

</html>
