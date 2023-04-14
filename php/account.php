<?php
include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../php/login.php');
    exit;
}

$email = $_SESSION["email"];
$user = new User($email);
$profile_picture = $user->getProfilePicture();
$profile_banner = $user->getProfileBanner();
$bio = $user->getBio();
$username = $user->getUsername();

//this is the url that will be copied to the clipboard when the share button is clicked
//it will be the url to the account page of the user that is currently logged in
//the id of the user is added to the url so that the account page can be loaded with the correct data
//the id is retrieved from the database
//javascript:void(0) is added to the url so that the page doesn't reload when the button is clicked
//the javascript function copyToClipboard() is called when the button is clicked
$id = $user->getId();
$share_url = "http://localhost/promptswap/AIAI-prompt-platform-main/php/account.php?id=$id";

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

  <?php include_once("../inc/nav.inc.php"); ?>

    <div class="profile">

        <div class="profileimg">
            <img class="banner" src="../media/<?php echo $profile_banner ?>" alt="">
            <img class="pfp" src="../media/<?php echo $profile_picture ?>" alt="">
        </div>

        <div class="profilename">
            <h2 class="nameuser"><?php echo $username ?></h2>
            <div class="likeandfollow">
            <a class="btnfollow" href="#">Follow</a>
            <a class="btnfollow" href="#">Flag</a>
            <a class="btnfollow" href="../php/editAccount.php">Edit Account</a>
            <a class="btnfollow" id="share-btn" href="javascript:void(0)" onclick="copyToClipboard('<?php echo $share_url ?>')">Share</a>
        </div>
        </div>  

        <div class="profilebio">
            <p class="biotext"><?php echo $bio ?></p>
        </div>
    </div>

      <div class="allprompts">
        <div>
            <h1>All prompts by <span><?php echo $username ?></span></h1><!-- Hier ga je de username nemen van het profiel waar je op zit-->
        </div>

        <div class="promptflex"> 
          
        <h3 style="margin-top: 50px">You don't have any prompts yet!</h3>
        <!-- Hier ga je éénmaal een prompt nemen in html en daarover lussen met een foreach in php vanuit uw database, dus niet de html aanpassen-->    
        <!--
          <a href="detail.php">
          <div class="prompt">
                <p class="modelboxtitle">Stable Diffuson</p> --><!-- telkens de titel van de prompt, als bgi doe je dan de foto uit de database --><!--
                <p class="promptboxtitle">Animals in cinema <span class="span">💶</span></p>--><!-- tussen de span zet je de prijs, maar dit is geen echt geld --><!--
            </div>
          </a>
          -->

        </div>

        <?php include_once("../inc/foot.inc.php"); ?>
        <script src="../css/script.js"></script>
  </body>
</html>
