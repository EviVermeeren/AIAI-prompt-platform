<?php
include_once("../inc/bootstrap.php"); //this file contains the database connection
include_once("../inc/functions.inc.php"); //this file contains the functions that are used in this file

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { //if the user is not logged in, redirect to the login page
  header('Location: ../php/login.php');
  exit;
}

$email = $_SESSION["email"]; //get the email from the session
$user = new User($email); //create a new user object
$profile_picture = $user->getProfilePicture(); //get the profile picture from the database
$profile_banner = $user->getProfileBanner(); //get the profile banner from the database
$bio = $user->getBio(); //get the bio from the database
$username = $user->getUsername(); //get the username from the database

//this is the url that will be copied to the clipboard when the share button is clicked
//it will be the url to the account page of the user that is currently logged in
//the id of the user is added to the url so that the account page can be loaded with the correct data
//the id is retrieved from the database
//javascript:void(0) is added to the url so that the page doesn't reload when the button is clicked
//the javascript function copyToClipboard() is called when the button is clicked
$user_id = $user->getId();
$share_url = "http://localhost/AIAI-prompt-platform-main/php/account.php?id=$user_id";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account</title>
  <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>

  <?php include_once("../inc/nav.inc.php"); ?> <!-- Include navigation -->

  <div class="profile">

    <div class="profileimg">
      <img class="banner" src="../media/<?php echo $profile_banner ?>" alt="">
      <img class="pfp" src="../media/<?php echo $profile_picture ?>" alt="">
    </div>

    <div class="profilename">
      <h2 class="nameuser"><?php echo $username ?></h2> <!-- Here we display the username of the user -->
      <div class="likeandfollow">
        <a class="btnfollow" href="#">Follow</a> <!-- This button will be used to follow the user -->
        <a class="btnfollow" href="#">Flag</a> <!-- This button will be used to flag the user -->
        <a class="btnfollow" href="../php/editAccount.php">Edit Account</a> <!-- This button will be used to edit the account -->
        <a class="btnfollow" id="share-btn" href="javascript:void(0)" onclick="copyToClipboard('<?php echo $share_url ?>')">Share</a> <!-- This button will be used to share the account -->

        <?php if (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?> <!-- if user is  logged in -->

          <a href="../php/favorites.php">Favorites</a>
        <?php endif; ?>

      </div>
    </div>

    <div class="profilebio">
      <p class="biotext"><?php echo $bio ?></p> <!-- Here we display the bio of the user -->
    </div>
  </div>

  <div class="allprompts">
    <div>
      <h1>All prompts by <span><?php echo $username ?></span></h1> <!-- Here we display the username of the user -->
    </div>

    <div class="promptflex">

      <h3 style="margin-top: 50px">You don't have any prompts yet!</h3>
      <!-- don't change the html here, you update this list with a loop with data from the database, for an example check marketplace.php -->

    </div>

    <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->
    <script src="../css/script.js"></script> <!-- Include script -->
</body>

</html>