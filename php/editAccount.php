<?php

include_once("../inc/bootstrap.php"); // include bootstrap file

if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { // if user is not logged in, redirect to login page
    header('Location: ../php/login.php');
    exit;
}

$email = $_SESSION["email"]; // get email from session
$user_id = $_SESSION['user_id'];

try { // connect to database
    $conn = Db::getInstance(); // get database connection
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error mode
} catch (PDOException $e) { // if connection fails, display error message
    $message = "Try again later: " . $e->getMessage(); // set error message
    exit; // exit script
}
$user = new User();
$userData = $user->getUserData($conn, $email);
$bio = $userData['bio'];
$profile_picture = $userData['profile_picture'];
$profile_banner = $userData['profile_banner'];
$firstname = $userData['firstname'];
$lastname = $userData['lastname'];
$username = $userData['username'];

$profilePictures = User::getDefaultPictures(); // get default profile pictures from database (array)

if ($_SERVER["REQUEST_METHOD"] == "POST") { // if form is submitted
    // Get form data from POST
    $firstname = $_POST["firstname"]; // get firstname from form
    $lastname = $_POST["lastname"]; // get lastname from form 
    $username = $_POST["username"]; // get username from form
    $bio = $_POST["bio"]; // get bio from form
    $profile_picture = isset($_POST["profile_picture"]) ? $_POST["profile_picture"] : $profile_picture; // get profile picture from form

    // Check if the new username is already in use
    if (User::checkIfUsernameExists($conn, $username, $email)) {
        $message = "Username is already in use";
        header("Location: ../php/editAccount.php?error=" . urlencode($message));
        exit;
    }

    User::updateUserData($conn, $firstname, $lastname, $username, $bio, $profile_picture, $email); // update user in database
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?> <!-- include navigation bar -->

    <div class="profile">

        <div class="profileimg">
            <img class="banner2" src="<?php echo $profile_banner ?>" alt=""> <!-- display profile banner -->
            <img class="pfp2" src="<?php echo $profile_picture ?>" alt=""> <!-- display profile picture -->
        </div>

        <form method="post" class="editAccount">

            <?php if (isset($error)) : ?> <!-- if error message is set -->
                <p class="errormessage"><?php echo $error ?></p> <!-- display error message -->
            <?php endif; ?>

            <label for="title">First name</label><br>
            <input class="inputfield" type="text" id="title" name="firstname" value="<?php echo $firstname; ?>"><br><br> <!-- display current firstname -->

            <label for="title">Last name</label><br>
            <input class="inputfield" type="text" id="title" name="lastname" value="<?php echo $lastname; ?>"><br><br> <!-- display current lastname -->

            <label for="title">Username</label><br>
            <input class="inputfield" type="text" id="title" name="username" value="<?php echo $username; ?>"><br><br> <!-- display current username -->

            <label for="title">Bio</label><br>
            <textarea class="inputfield" id="bio" name="bio"><?php echo $bio ?></textarea><br><br>

            <?php foreach ($profilePictures as $picture) { ?>
                <label>
                    <input type="radio" name="profile_picture" value="<?php echo $picture; ?>" <?php if ($picture === $profile_picture) echo "checked"; ?>> <!-- current profile picture is selected  -->
                    <img src="<?php echo $picture; ?>" alt="Profile Picture" style="width: 100px;"> <!-- display profile pictures -->
                </label>
            <?php } ?>

            <!--
        <label for="profile_picture">Profile Picture</label><br>
        <input type="file" name="profile_picture" accept="image/*"><br><br>

        <label>Profile Banner</label><br>
        <input type="file" name="profile_banner" accept="image/*"><br><br>
        -->

            <a href="../php/customProfilePicture.php"> Change Profile picture</a><br><br>

            <input class="submitbtn" type="submit" value="Save profile">
            <a href="../php/deleteAccount.php"> Delete your account</a>
            <a href="../php/change-password.php"> Change Password</a>

        </form>

        <?php include_once("../inc/foot.inc.php"); ?> <!-- include footer -->

</body>

</html>