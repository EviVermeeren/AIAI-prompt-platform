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

try { // connect to database
    $conn = Db::getInstance(); // get database connection
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error mode
} catch (PDOException $e) { // if connection fails, display error message
    $message = "Try again later: " . $e->getMessage(); // set error message
    exit; // exit script
}

$query = $conn->prepare("SELECT firstname, lastname, username, bio, profile_picture, profile_banner FROM users WHERE email = :email"); // get user data from database

$query->bindValue(":email", $email); // bind email to query
$query->execute(); // execute query

$row = $query->fetch(PDO::FETCH_ASSOC); // fetch data from query
$bio = $row['bio']; // get bio from database
$profile_picture = $row['profile_picture']; // get profile picture from database
$profile_banner = $row['profile_banner']; // get profile banner from database
$firstname = $row['firstname']; // get firstname from database
$lastname = $row['lastname']; // get lastname from database 
$username = $row['username']; // get username from database

$profilePictures = User::getDefaultPictures(); // get default profile pictures from database (array)

if ($_SERVER["REQUEST_METHOD"] == "POST") { // if form is submitted
    // Get form data from POST
    $firstname = $_POST["firstname"]; // get firstname from form
    $lastname = $_POST["lastname"]; // get lastname from form 
    $username = $_POST["username"]; // get username from form
    $bio = $_POST["bio"]; // get bio from form
    $profile_picture = isset($_POST["profile_picture"]) ? $_POST["profile_picture"] : $profile_picture; // get profile picture from form


    // Check if the new username is already in use
    $username_query = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username AND email != :email"); // get user data from database
    $username_query->bindValue(":username", $username); // bind username to query
    $username_query->bindValue(":email", $email); // bind email to query
    $username_query->execute(); // execute query

    $username_row = $username_query->fetch(PDO::FETCH_ASSOC); // fetch data from query

    if ($username_row['count'] > 0) { // if username is already in use 
        $message = "Username is already in use"; // set error message
        header("Location: ../php/editAccount.php?error=" . urlencode($message)); // redirect to edit account page
        exit;
    }

    // Update database with form data
    $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, username=:username, bio=:bio, profile_picture=:profile_picture";

    // Add WHERE clause to limit the update to the logged-in user
    $sql .= " WHERE email=:email";

    // Prepare and execute query with parameters
    $query = $conn->prepare($sql);
    $query->bindValue(":firstname", $firstname);
    $query->bindValue(":lastname", $lastname);
    $query->bindValue(":username", $username);
    $query->bindValue(":bio", $bio);
    $query->bindValue(":profile_picture", $profile_picture);
    // Bind email parameter to limit the update to the logged-in user
    $query->bindValue(":email", $email);

    $query->execute(); // execute query

    if ($query->rowCount() > 0) { // if query is successful
        header("Location: ../php/account.php"); // redirect to account page
        exit; // exit script
    } else { // if query is not successful
        $message = "Your account has not been updated"; // set error message
    }
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