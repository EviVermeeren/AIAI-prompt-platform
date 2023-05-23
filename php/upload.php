<?php
include_once("../inc/bootstrap.php");

if (isset($_GET["error"])) {
  $error = $_GET["error"];
}

$user = new User();
if (!$user->isAuthenticated()) {
  $user->redirectToLogin();
}

$email = $_SESSION["email"];
$conn = Db::getInstance();
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $prompt = new Prompt();

  $prompt->setName($_POST["title"]);
  $prompt->setDescription($_POST["description"]);
  $prompt->setModel($_POST["model-type"]);
  $prompt->setPrice($_POST["price"]);
  $prompt->setPrompt($_POST["prompt"]);
  $prompt->setTags($_POST["tags"]);

  $categories = array("Animals", "3D", "Space", "Game", "Car", "Nature", "Portrait", "Anime", "Interior", "Realistic", "Geek", "Building");
  $selected_categories = array();
  foreach ($categories as $category) {
    if (isset($_POST["categories"]) && in_array($category, $_POST["categories"])) {
      $selected_categories[] = $category;
    }
  }
  $prompt->setCharacteristics($selected_categories);

  $current_date = date("Y-m-d H:i:s");
  $file_name = $prompt->handleFileUpload($_FILES["image-upload"]);

  $prompt->setPictures($file_name);
  $prompt->createPrompt();

  header('Location: ../php/succes.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>upload your prompt</title>
  <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
  <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
  <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>
  <?php include_once("../inc/nav.inc.php"); ?> <!-- This is the nav bar -->


  <form class="uploadform" enctype="multipart/form-data" method="POST">
    <h2>Upload a new prompt</h2>
    <?php if (isset($error)) : ?> <!-- if error message is set -->
      <p class="errormessage"><?php echo $error ?></p> <!-- display error message -->
    <?php endif; ?>

    <label for="title">Title</label><br>
    <input class="inputfield" type="text" id="title" name="title" required><br><br>

    <label for="description">Description</label><br>
    <textarea style="resize: none;" class="inputfield2" id="description" name="description" required></textarea><br><br>

    <label for="description">Prompt</label><br>
    <textarea style="resize: none;" class="inputfield2" id="prompt" name="prompt" required></textarea><br><br>

    <label for="description">Price</label><br>
    <select class="inputfield3" id="price" name="price">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select><br><br>

    <label for="model-type">Model</label><br>
    <select class="inputfield3" id="model-type" name="model-type">
      <option value="dalle">DALL-E</option>
      <option value="midjourney">Midjourney</option>
      <option value="lexica">Lexica</option>
      <option value="stablediffusion">Stable Diffusion</option>
    </select><br><br>

    <label for="image-upload">Image</label>
    <input class="inputfield4" type="file" id="image-upload" name="image-upload" accept="image/*" required><br><br>

    <label for="title">Tags</label><br>
    <input class="inputfield" type="text" id="tags" name="tags" required><br><br>


    <p class="filter">Category:</p>

    <input type="checkbox" id="animals" name="categories[]" value="Animals">
    <label for="animals"> Animals</label><br>

    <input type="checkbox" id="3D" name="categories[]" value="3D">
    <label for="3D"> 3D</label><br>

    <input type="checkbox" id="space" name="categories[]" value="Space">
    <label for="space"> Space</label><br>

    <input type="checkbox" id="game" name="categories[]" value="Game">
    <label for="game"> Game</label><br>

    <input type="checkbox" id="car" name="categories[]" value="Car">
    <label for="car"> Car</label><br>

    <input type="checkbox" id="nature" name="categories[]" value="Nature">
    <label for="nature"> Nature</label><br>

    <input type="checkbox" id="portrait" name="categories[]" value="Portrait">
    <label for="portrait"> Portrait</label><br>

    <input type="checkbox" id="anime" name="categories[]" value="Anime">
    <label for="anime"> Anime</label><br>

    <input type="checkbox" id="interior" name="categories[]" value="Interior">
    <label for="interior"> Interior</label><br>

    <input type="checkbox" id="realistic" name="categories[]" value="Realistic">
    <label for="realistic"> Realistic</label><br>

    <input type="checkbox" id="geek" name="categories[]" value="Geek">
    <label for="geek"> Geek</label><br>

    <input type="checkbox" id="building" name="categories[]" value="Building">
    <label for="building"> Building</label><br>

    <input class="submitbtn" type="submit" value="Submit">
  </form>


  <?php include_once("../inc/foot.inc.php"); ?> <!-- This is the footer -->
</body>

</html>