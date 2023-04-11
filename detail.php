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
    <title>Detail</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <?php include_once("nav.inc.php"); ?>

    <div class="detailheader">
        <div class="detailimgheader"></div>
        <div>

          <div class="detailinfo">
            <h1>Animals in cinema <span>❤</span></h1> <!--Hier komt uit de database de titel aan de hand van waar je net op hebt geklikt-->
   
            <h2>DaBawsVL <span>🦸‍♂️</span> <span>⭐⭐⭐⭐⭐</span> </h2> <!--Hier komt uit de database de username aan de hand van waar je net op hebt geklikt-->
  
            <h3 class="desc">
                Introducing our brand new mid-journey prompt - 
                Anthropomorphic Gangsta Animals! Take your writing to the next level with these badass animal characters. <br><br>
                Choose your subject and watch as they come to life with attitude and style.  Whether it's a gritty crime thriller 
                or a quirky comedy, these gangsta animals will add a unique edge to your story. Impress your readers with unforgettable 
                characters that jump off the page. With our prompt, you'll have everything you need to stand out!<br>
            </h3> <!--Hier komt uit de database de description aan de hand van waar je net op hebt geklikt-->
            <h2 class="money">💶 2</h2>
        </div>
          <div class="detailbuttondiv">
            <a class="detailbutton" href="marketplace.php">Buy prompt</a>
            <a class="icon" href="#">📲</a>
            <a class="icon" href="#">🏳‍🌈</a>
          </div>
        </div>
        
      </div>

      <div class="like-comment-section">

        <div class="likes">
            <div class="like-count">
                <span>👍🏻</span>
              <span class="last-likes">John Doe, Jane Smith, and Bob Johnson and <span class="count">3</span> others</span><br> <br><!--Hier komt uit de database de hoeveelheid likes en van wie-->
              <span>💭</span>
              <span class="comment-count"><span class="countcomment">5 </span>comments</span><!--Hier komt uit de database de hoeveelheid comments-->
            </div>
          </div>
    
        <div class="actions">
          <button class="action-button like">Like</button>
          <button class="action-button comment">Comment</button>
          <button class="action-button share">Share</button>
        </div>
        
        <div class="comments">
          
          
          <div class="comment"> 
            <img src="./pickachu.png" alt="Profile Picture"> <!--Hier komt uit de database de comment en van wie, pfp en naam, we tonen alle comments, maar de laatste 3 en de rest via "laden", loopen foreach comments->comment-->
            <div class="comment-info">
              <h4 class="comment-name">John Doe</h4>
              <p class="comment-text">Great post! Keep up the good work.</p>
            </div>
          </div>
          
          <div class="add-comment">  <!--Hier komt uit de database de pfp van de persoon die ingelogd is-->
            <img src="./pickachu.png" alt="Your Profile Picture">
            <textarea style="resize: none;" placeholder="Write a comment..."></textarea>
            <button class="comment-button">Comment</button>  <!-- voegt de comment toe in de database van de persoon + foto + naam en print deze af -->
          </div>
        </div>
      </div>
    


      <?php include_once("foot.inc.php"); ?>
  </body>
</html>
