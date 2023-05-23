<?php

include_once("../inc/bootstrap.php"); // include the bootstrap file

if (!isset($_GET['id'])) { // check if the id parameter is set
  echo "Error: ID parameter not set"; // if not, display an error message
  exit; // and exit the script
}

$id = $_GET['id']; // if the id parameter is set, store it in a variable

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

$conn = Db::getInstance(); // connect to the database
if (!$conn) { // check if the connection was successful
  echo "Error: Failed to connect to database"; // if not, display an error message
  exit; // and exit the script
}

$new = new Prompt();
$prompt = $new->getPromptById($id);

if ($prompt) {
  $name = $prompt['name'];
  $user = $prompt['user'];
  $rating = $prompt['rating'];
  $description = $prompt['description'];
  $price = $prompt['price'];
  $characteristics = $prompt['characteristics'];
  $model = $prompt['model'];
  $promptContent = $prompt['prompt'];
  $pictures = $prompt['pictures'];
  $date = $prompt['date'];
  $tags = $prompt['tags'];
} else {
  echo "Error: No results found";
  exit;
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  $userz = new User();
  $username = $userz->getUsernameByEmail($user);
}

//only make User object if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  $usera = new User();
  $results = $usera->getFavoritesByUserID($user_id, $id);
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
  <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
  <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>
  <?php include_once("../inc/nav.inc.php"); ?> <!-- include the navigation bar -->

  <div class="detailheader">
    <div class="detailimgheader"><img class="detailimgheader" src="../media/<?php echo $pictures ?>" alt=""></div>
    <div>

      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?> <!-- check if the user is logged in -->
        <div class="detailinfo"> <!-- if the user is logged in, display the prompt details -->
          <h1> <?php echo $name ?></h1>
          <h2><?php echo $username ?> <span>🦸‍♂️</span>
            <?php
            for ($i = 0; $i < $rating; $i++) {
              echo '<span>⭐</span>';
            }
            ?>
          </h2>
          <h3 class=" desc">
            <?php echo $description ?><br>
          </h3>
          <h3 class="desc"><?php echo $tags ?></h3>
          <h2 class="money">💶 <?php echo $price ?></h2>

        </div>
      <?php else : ?> <!-- if the user is not logged in, display a message -->
        <div class="detailinfo">
          <h1> <?php echo $name ?></h1>
          <h3 class="desc">
            <a href="../php/login.php">Want to see more details? Login.</a>
          </h3>
        </div>
      <?php endif; ?> <!-- end of the if statement -->

      <div class="detailbuttondiv">
        <a class="detailbutton" href="#">Buy prompt</a> <!-- link to the buy prompt page, will go to login if not logged in -->


        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && count($results) == 0) : ?>
          <button id="add-to-favorites">Add to favorites</button>
        <?php endif ?>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && count($results) > 0) : ?>
          <button id="delete-favorite" onclick="deleteFavorite()">Delete from favorites</button>
        <?php endif ?>

        <p id="message"></p>

        <a class="icon" href="#">🏳‍🌈</a>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) : ?>
          <button type="submit" class="delete-button">Delete this (my) prompt</button>
        <?php endif; ?>

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
        <img src="../media/pickachu.png" alt="Profile Picture"> <!--Hier komt uit de database de comment en van wie, pfp en naam, we tonen alle comments, maar de laatste 3 en de rest via "laden", loopen foreach comments->comment-->
        <div class="comment-info">
          <h4 class="comment-name">John Doe</h4>
          <p class="comment-text">Great post! Keep up the good work.</p>
        </div>
      </div>

      <div class="add-comment"> <!--Hier komt uit de database de pfp van de persoon die ingelogd is-->
        <img src="../media/pickachu.png" alt="Your Profile Picture">
        <textarea style="resize: none;" placeholder="Write a comment..."></textarea>
        <button class="comment-button">Comment</button> <!-- voegt de comment toe in de database van de persoon + foto + naam en print deze af -->
      </div>
    </div>
  </div>



  <?php include_once("../inc/foot.inc.php"); ?> <!-- include the footer -->


  <script>
    // Attach the event listener to a parent element using event delegation
    document.addEventListener("click", function(event) {
      var target = event.target;
      if (target.id === "add-to-favorites") {
        var button = target;

        fetch("add-to-favorites.php", {
            method: "POST",
            headers: {
              "Content-type": "application/x-www-form-urlencoded"
            },
            body: "id=<?php echo $id; ?>"
          })
          .then(function(response) {
            if (response.ok) {
              return response.json();

            } else {
              throw new Error("Request failed.");
            }
          })
          .then(function(data) {
            // Handle the response here
            if (data.success) {
              // Update the button text dynamically
              button.textContent = "Delete from Favorites";
              button.id = "delete-favorite"; // Update the button ID
              button.setAttribute("onclick", "deleteFavorite()"); // Add onclick attribute

            }
          })
          .catch(function(error) {
            console.log(error);
          });
        location.reload(); // Reload the page
      }
    });

    function deleteFavorite() {
      var button = document.getElementById("delete-favorite");

      fetch("delete-favorite.php", {
          method: "POST",
          headers: {
            "Content-type": "application/x-www-form-urlencoded"
          },
          body: "id=<?php echo $id; ?>&_method=DELETE"
        })
        .then(function(response) {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error("Request failed.");
          }
        })
        .then(function(data) {
          // Handle the response here
          if (data.success) {
            // Update the button text dynamically
            button.textContent = "Add to Favorites";
            button.id = "add-to-favorites"; // Update the button ID
            button.setAttribute("onclick", null); // Remove onclick attribute

          }
        })
        .catch(function(error) {
          console.log(error);
        });

      location.reload(); // Reload the page
    }
  </script>



</body>

</html>