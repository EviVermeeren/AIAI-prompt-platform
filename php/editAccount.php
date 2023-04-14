<?php
include_once("../inc/bootstrap.php");

$message = "";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../php/login.php');
    exit;
}

$email = $_SESSION["email"];

try {
    $conn = Db::getInstance();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $message = "Try again later: " . $e->getMessage();
    exit;
}

$query = $conn->prepare("SELECT firstname, lastname, username, bio, profile_picture, profile_banner, password FROM users WHERE email = :email");

$query->bindValue(":email", $email);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$bio = $row['bio'];
$profile_picture = $row['profile_picture'];
$profile_banner = $row['profile_banner'];
$hashed_password = $row['password'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$username = $row['username'];


// Define an array of default profile pictures
$profilePictures = ProfilePicture::getDefaultPictures();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $current_password = $_POST["password"];
    $new_password = $_POST["newpassword"];
    $repeat_password = $_POST["repeatnewpassword"];
    $bio = $_POST["bio"];
    $profile_picture = isset($_POST["profile_picture"]) ? $_POST["profile_picture"] : $profile_picture;

    // Check if new password and repeat password match
    if (!empty($new_password) && $new_password !== $repeat_password) {
        header("Location: ../php/editAccount.php");
        $message = "New password and repeat password do not match";
        exit;
    }

    // Check if current password is correct, if a new password is provided
    if (!empty($new_password) && !password_verify($current_password, $hashed_password)) {
        header("Location: ../php/editAccount.php");
        $message = "Current password is incorrect";
        exit;
    }

    // Hash new password, if provided
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // Update database with form data
    $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, username=:username, bio=:bio, profile_picture=:profile_picture";

    // Update password, if provided
    if (!empty($new_password)) {
        $sql .= ", password=:hashed_password";
        $parameters[':hashed_password'] = $hashed_password;
    }

    $sql .= " WHERE email=:email";
    $parameters = array(':firstname' => $firstname, ':lastname' => $lastname, ':username' => $username, ':bio' => $bio, ':profile_picture' => $profile_picture, ':email' => $email);

    $query = $conn->prepare($sql);
    $result = $query->execute($parameters);

    if ($result && $query->rowCount() > 0) {
        header("Location: ../php/account.php");
        exit;
    } else {
        $message = "Your account has not been updated";
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
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
  <?php include_once("../inc/nav.inc.php"); ?>

    <div class="profile">

        <div class="profileimg">
            <img class="banner2" src="<?php echo $profile_banner ?>" alt="">
            <img class="pfp2" src="<?php echo $profile_picture ?>" alt="">
        </div>
        
    <form method="post" class="editAccount">

        <p class="errormessage"><?php echo $message ?></p>

        <label for="title">First name</label><br>
        <input class="inputfield" type="text" id="title" name="firstname" value="<?php echo $firstname; ?>"><br><br>

        <label for="title">Last name</label><br>
        <input class="inputfield" type="text" id="title" name="lastname" value="<?php echo $lastname; ?>"><br><br>

        <label for="title">Username</label><br>
        <input class="inputfield" type="text" id="title" name="username" value="<?php echo $username; ?>"><br><br>
      
        <label for="title">Current password</label><br>
        <input class="inputfield" type="password" id="title" name="password"><br><br>

        <label for="title">New password</label><br>
        <input class="inputfield" type="password" id="title" name="newpassword"><br><br>

        <label for="title">Repeat new password</label><br>
        <input class="inputfield" type="password" id="title" name="repeatnewpassword"><br><br>

        <label for="title">Bio</label><br>
        <textarea class="inputfield" id="bio" name="bio"><?php echo $bio ?></textarea><br><br>

        <?php foreach ($profilePictures as $picture) { ?>
        <label>
        <input type="radio" name="profile_picture" value="<?php echo $picture; ?>" <?php if ($picture === $profile_picture) echo "checked"; ?>>
            <img src="<?php echo $picture; ?>" alt="Profile Picture" style="width: 100px;">
        </label>
        <?php } ?>

        <!--
        <label for="profile_picture">Profile Picture</label><br>
        <input type="file" name="profile_picture" accept="image/*"><br><br>

        <label>Profile Banner</label><br>
        <input type="file" name="profile_banner" accept="image/*"><br><br>
        -->

        <input class="submitbtn" type="submit" value="Save profile">

    </form>

    <?php include_once("../inc/foot.inc.php"); ?>

    </body>
</html>