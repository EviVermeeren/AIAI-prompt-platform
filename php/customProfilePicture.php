<?php
include_once("../classes/User.php"); // include head
include_once("../classes/Db.php"); // include head


ini_set('display_errors', 1);

$user = new User();
$userDetails = $user->getUserDetails();
$profilePicture = $userDetails['profile_picture'];

if (!empty($_POST)) {
    $target_dir = '/home/site/wwwroot/php/uploads/'; // Replace this with the absolute path to your desired directory
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    //test

    // check of image file is a actual image or fake image

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // check the file size

        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // allow certain file formats

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
            $uploadOk = 0;
        }

        // check if $uploadOk is set to 0 by an error

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                $user->setProfile_picture($target_file);
                $user->saveProfilePicture();
                $profilePicture = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Picture</title>
    <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>

    <form action="../php/customProfilePicture.php" method="post" enctype="multipart/form-data">
        <h2>Change Profile Picture</h2>
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload" name="submit">
    </form>
    <img src="<?php echo $profilePicture ?>" alt="">

</body>

</html>