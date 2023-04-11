<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
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
            <img class="banner" src="/achtergrond.jpg" alt="">
            <img class="pfp" src="/pickachu.png" alt="">
        </div>

        <div class="profilename">
            <h2 class="nameuser">DaBawsVL</h2>
            <div class="likeandfollow">
            <a class="btnfollow" href="#">Follow</a>
            <a class="btnfollow" href="#">Flag</a>
        </div>
        </div>  

        <div class="profilebio">
            <p class="biotext">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint quod soluta consequatur! Cum aut dolores pariatur quo repellat aliquam iure, ut vel eius nobis dolor facere. Quas dignissimos consequatur vitae.</p>
        </div>
    </div>

      <div class="allprompts">
        <div>
            <h1>All prompts by <span>DaBawsVL</span></h1><!-- Hier ga je de username nemen van het profiel waar je op zit-->
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
