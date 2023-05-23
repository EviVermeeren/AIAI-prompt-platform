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
  echo "Error: Failed to connect to the database"; // if not, display an error message
  exit; // and exit the script
}

$prompt = new Prompt();
$promptData = $prompt->getPromptById($id);

if (!$promptData) {
  echo "Error: No results found";
  exit;
}

$name = $promptData['name'];
$user = $promptData['user'];
$rating = $promptData['rating'];
$description = $promptData['description'];
$price = $promptData['price'];
$characteristics = $promptData['characteristics'];
$model = $promptData['model'];
$promptContent = $promptData['prompt'];
$pictures = $promptData['pictures'];
$date = $promptData['date'];
$tags = $promptData['tags'];

$userz = new User();
$username = $userz->getUsernameByEmail($user);

$usera = new User();
$favorites = $usera->getFavoritesByUserID($user_id, $id);
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
  <script src="../css/script.js"></script> <!-- Import the JavaScript file -->
  <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>
  <?php include_once("../inc/nav.inc.php"); ?> <!-- include the navigation bar -->

  <div class="detailheader">
    <div class="detailimgheader"><img class="detailimgheader" src="../media/<?php echo $pictures ?>" alt=""></div>
    <div>
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
        <div class="detailinfo">
          <h1><?php echo $name ?></h1>
          <h2><?php echo $username ?> <span>🦸‍♂️</span>
            <?php
            for ($i = 0; $i < $rating; $i++) {
              echo '<span>⭐</span>';
            }
            ?>
          </h2>
          <h3 class="desc"><?php echo $description ?><br></h3>
          <h3 class="desc"><?php echo $tags ?></h3>
          <h2 class="money">💶 <?php echo $price ?></h2>
        </div>
      <?php else : ?>
        <div class="detailinfo">
          <h1><?php echo $name ?></h1>
          <h3 class="desc">
            <a href="../php/login.php">Want to see more details? Login.</a>
          </h3>
        </div>
      <?php endif; ?>

      <div class="detailbuttondiv">
        <a class="detailbutton" href="#">Buy prompt</a>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && count($favorites) == 0) : ?>
          <button class="detailbutton" id="add-to-favorites">Add to favorites ⭐</button>
        <?php endif ?>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && count($favorites) > 0) : ?>
          <button class="detailbutton" id="delete-favorite" onclick="deleteFavorite()">Delete from favorites</button>
        <?php endif ?>

        <p id="message"></p>

        <a class="detailbutton" href="#" onclick="reportPrompt(promptId)">Report prompt 🏳‍🌈</a>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) : ?>
          <button type="submit" class="detailbutton">Delete this (my) prompt</button>
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
        <span class="comment-count"><span class="countcomment">5</span> comments</span><!--Hier komt uit de database de hoeveelheid comments-->
      </div>
    </div>

    <div class="actions">
      <button class="action-button like">Like</button>
      <button class="action-button comment">Comment</button>
      <button class="action-button share">Share</button>
    </div>

    <div class="comments">
      <div class="comment">
        <?php foreach ($allComments as $c) : ?>
          <img src="../media/pickachu.png" alt="Profile Picture">
          <div class="comment-info">
            <h4 class="comment-name"><?php echo $c["userId"] ?></h4>
            <p class="comment-text"><?php echo $c['text'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="add-comment">
        <img src="../media/pickachu.png" alt="Your Profile Picture">
        <textarea style="resize: none;" id="commentText" placeholder="Write a comment..."></textarea>
        <button class="comment-button" id="btnComment" data-postid="<?php echo $_GET['id']; ?>">Comment</button>
        <ul class="listUpdate">
          <?php foreach ($allComments as $c) : ?>
            <li><?php echo $c['text']; ?></li>
          <?php endforeach; ?>
        </ul>
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

    document.getElementById("btnComment").addEventListener("click", function() {
      // post id
      console.log("click");
      // comment text
      let postId = document.querySelector("#btnComment").dataset.postid;
      let text = document.querySelector("#commentText").value;
      // let userId = this.dataset.userId;

      console.log(postId, text);
      // posten naar database
      let formData = new FormData();

      formData.append("postId", postId);
      formData.append("text", text);

      fetch("saveComment.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(result => {
          console.log(result);
          // toon comment onderaan
          let newComment = document.createElement("li");
          newComment.innerHTML = result.body;
          document.querySelector(".listUpdate").appendChild(newComment);
        })
        .catch(error => {
          console.error("Error:", error);
        });
      // antwoord ok? toon comment onderaan
    });
  </script>
</body>

</html>