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

$sql = "SELECT * FROM prompts WHERE id = $id"; // query the database to get the prompt with the specified ID
$result = $conn->query($sql); // execute the query
if (!$result) { // check if the query was successful
  echo "Error: Failed to execute query: " . $conn->error; // if not, display an error message
  exit; // and exit the script 
}

if ($result->rowCount() > 0) { // check if the query returned any results
  while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // loop through the results
    $name = $row['name']; // store the name of the prompt in a variable
    $user = $row['user']; // store the name of the user in a variable
    $rating = $row['rating']; // store the rating of the prompt in a variable
    $description = $row['description']; // store the description of the prompt in a variable
    $price = $row['price']; // store the price of the prompt in a variable
    $characteristics = $row['characteristics']; // store the characteristics of the prompt in a variable
    $model = $row['model']; // store the model of the prompt in a variable
    $prompt = $row['prompt']; // store the prompt
    $pictures = $row['pictures']; // store the pictures of the prompt in a variable
    $date = $row['date']; // store the date of the prompt in a variable
    $tags = $row['tags']; // store the tags of the prompt in a variable

    $user_id = $row['user']; // store the ID of the user in a variable
    $stmt = $conn->prepare("SELECT username FROM users WHERE email = :email"); // query the database for the username with the specified email
    $stmt->bindParam(':email', $user_id);
    $stmt->execute();
    $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user_row['username']; // get the username from the resulting row

  }
} else { // if the query returned no results
  echo "Error: No results found"; // display an error message
  exit; // and exit the script
}

// Prepare SQL statement
$stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND prompt_id = :id");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':id', $id);
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll();


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
          <h3 class="desc">
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
    document.getElementById("add-to-favorites").addEventListener("click", function() {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "add-to-favorites.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          location.reload();

        }
      };
      xhr.send("id=<?php echo $id; ?>");
    });

    function deleteFavorite() {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "delete-favorite.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          location.reload();

        }
      };
      xhr.send("id=<?php echo $id; ?>&_method=DELETE");
    }
  </script>



</body>

</html>