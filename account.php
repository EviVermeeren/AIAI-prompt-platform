<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION["email"];

$conn = new PDO('mysql:host=localhost;dbname=promptswap', "evi", "12345");
$query = $conn->prepare("SELECT profile_picture FROM users WHERE email = :email");
$q = $conn->prepare("SELECT profile_banner FROM users WHERE email = :email");

$query->bindValue(":email", $email);
$query->execute();

$q->bindValue(":email", $email);
$q->execute();

$profile_picture = $query->fetchColumn();
$profile_banner = $q->fetchColumn();

$query_username = $conn->prepare("SELECT username FROM users WHERE email = :email");
$query_username->bindValue(":email", $email);
$query_username->execute();
$username = $query_username->fetchColumn();

if (!empty($profile_banner)) {
  $banner_src = $profile_banner;
} else {
  $banner_src = "./achtergrond.jpg";
}

if (!empty($profile_picture)) {
  $picture_src = $profile_picture;
} else {
  $picture_src = "./pickachu.png";
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

  <?php include_once("nav.inc.php"); ?>

    <div class="profile">

        <div class="profileimg">
            <img class="banner" src="<?php echo $profile_banner ?>" alt="">
            <img class="pfp" src="<?php echo $profile_picture ?>" alt="">
        </div>

        <div class="profilename">
            <h2 class="nameuser"><?php echo $username ?></h2>
            <div class="likeandfollow">
            <a class="btnfollow" href="#">Follow</a>
            <a class="btnfollow" href="#">Flag</a>
            <a class="btnfollow" href="editAccount.php">Edit Account</a>
        </div>
        </div>  

        <div class="profilebio">
            <p class="biotext">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint quod soluta consequatur! Cum aut dolores pariatur quo repellat aliquam iure, ut vel eius nobis dolor facere. Quas dignissimos consequatur vitae.</p>
        </div>
    </div>

      <div class="allprompts">
        <div>
            <h1>All prompts by <span><?php echo $username ?></span></h1><!-- Hier ga je de username nemen van het profiel waar je op zit-->
        </div>

        <div class="promptflex"> <!-- Hier ga je éénmaal een prompt nemen in html en daarover lussen met een foreach in php vanuit uw database, dus niet de html aanpassen-->
            
          <a href="detail.php">
          <div class="prompt">
                <p class="modelboxtitle">Stable Diffuson</p> <!-- telkens de titel van de prompt, als bgi doe je dan de foto uit de database -->
                <p class="promptboxtitle">Animals in cinema <span class="span">💶</span></p><!-- tussen de span zet je de prijs, maar dit is geen echt geld -->
            </div>
          </a>

          <a href="detail.php">
            <div class="prompt">
                  <p class="modelboxtitle">Stable Diffuson</p> <!-- telkens de titel van de prompt, als bgi doe je dan de foto uit de database -->
                  <p class="promptboxtitle">Animals in cinema <span class="span">💶</span></p><!-- tussen de span zet je de prijs, maar dit is geen echt geld -->
              </div>
            </a>
        </div>

        <?php include_once("foot.inc.php"); ?>
  </body>
</html>
