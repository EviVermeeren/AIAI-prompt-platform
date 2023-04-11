<?php
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION["email"];

$dsn = "mysql:host=localhost;dbname=promptswap";
$user = "evi";
$pass = "12345";

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $message = "Try again later: " . $e->getMessage();
    exit;
}

$query = $conn->prepare("SELECT profile_picture, profile_banner, password FROM users WHERE email = :email");

$query->bindValue(":email", $email);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$profile_picture = $row['profile_picture'];
$profile_banner = $row['profile_banner'];
$hashed_password = $row['password'];

if (empty($profile_banner)) {
  $banner_src = "./achtergrond.jpg";
} else {
  $banner_src = $profile_banner;
}

if (empty($profile_picture)) {
  $picture_src = "./pickachu.png";
} else {
  $picture_src = $profile_picture;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = $_POST["firstname"];
    $last_name = $_POST["lastname"];
    $username = $_POST["username"];
    $current_password = $_POST["password"];
    $new_password = $_POST["newpassword"];
    $repeat_password = $_POST["repeatnewpassword"];

    // Check if new password and repeat password match
    if ($new_password !== $repeat_password) {
        header("Location: editAccount.php");
        $message = "New password and repeat password do not match";
        exit;
    }

    // Check if current password is correct
    if (!password_verify($current_password, $hashed_password)) {
        header("Location: editAccount.php");
        $message = "Current password is incorrect";
        exit;
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update database with form data
    $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, username=:username, password=:hashed_password WHERE email=:email";
    $query = $conn->prepare($sql);
    $query->bindParam(":firstname", $first_name);
    $query->bindParam(":lastname", $last_name);
    $query->bindParam(":username", $username);
    $query->bindParam(":hashed_password", $hashed_password);
    $query->bindParam(":email", $email);
    $result = $query->execute();

    if ($result && $query->rowCount() > 0) {
        header("Location: account.php");
        exit;
    } else {
        $message = "Failed to update account.";
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
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <?php include_once("nav.inc.php"); ?>

    <div class="profile">

        <div class="profileimg">
            <img class="banner2" src="<?php echo $profile_banner ?>" alt="">
            <img class="pfp2" src="<?php echo $profile_picture ?>" alt="">
        </div>
        
    <form method="post" class="editAccount">

        <p class="errormessage"><?php echo $message ?></p>

        <label for="title">First name</label><br>
        <input class="inputfield" type="text" id="title" name="firstname" required><br><br>

        <label for="title">Last name</label><br>
        <input class="inputfield" type="text" id="title" name="lastname" required><br><br>

        <label for="title">Username</label><br>
        <input class="inputfield" type="text" id="title" name="username" required><br><br>
      
        <label for="title">Current password</label><br>
        <input class="inputfield" type="password" id="title" name="password" required><br><br>

        <label for="title">New password</label><br>
        <input class="inputfield" type="password" id="title" name="newpassword" required><br><br>

        <label for="title">Repeat new password</label><br>
        <input class="inputfield" type="password" id="title" name="repeatnewpassword" required><br><br>
      
        <input class="submitbtn" type="submit" value="Save profile">

    </form>

    <?php include_once("foot.inc.php"); ?>

    </body>
</html>