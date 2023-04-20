<?php
include_once("../inc/bootstrap.php"); // include bootstrap file

$message = ""; // initialize message variable

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { // if user is not logged in, redirect to login page
  header('Location: ../php/login.php');
  exit;
}

$email = $_SESSION["email"]; // get email from session

try { // connect to database
  $conn = Db::getInstance(); // get database connection
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error mode
} catch (PDOException $e) { // if connection fails, display error message
  $message = "Try again later: " . $e->getMessage(); // set error message
  exit; // exit script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // if form is submitted
  // Get form data from POST
  $name = $_POST["title"]; // get title from form
  $description = $_POST["description"]; // get description from form 
  $model = $_POST["model-type"]; // get model from form

  // Get uploaded file data
  $file_name = $_FILES["image-upload"]["name"];

  $categories = array("Animals", "3D", "Space", "Game", "Car", "Nature", "Portrait", "Anime", "Interior", "Realistic", "Geek", "Building");
  $selected_categories = array();
  foreach ($categories as $category) {
    if (isset($_POST["categories"]) && in_array($category, $_POST["categories"])) {
      $selected_categories[] = $category;
    }
  }
  $categories_str = implode(", ", $selected_categories);

  $query = $conn->prepare("INSERT INTO prompts (name, user, description, model, pictures, characteristics) VALUES (:name, :email, :description, :model, :pictures, :categories)");
  $query->bindValue(":name", $name);
  $query->bindValue(":email", $email);
  $query->bindValue(":description", $description);
  $query->bindValue(":model", $model);
  $query->bindValue(":pictures", $file_name);
  $query->bindValue(":categories", implode(", ", $selected_categories));
  $query->execute();
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
</head>

<body>
  <?php include_once("../inc/nav.inc.php"); ?> <!-- This is the nav bar -->


  <form class="uploadform" enctype="multipart/form-data" method="POST">
    <h2>Upload a new prompt</h2>
    <p><?php echo $message ?></p>

    <label for="title">Title</label><br>
    <input class="inputfield" type="text" id="title" name="title" required><br><br>

    <label for="description">Description</label><br>
    <textarea style="resize: none;" class="inputfield2" id="description" name="description" required></textarea><br><br>

    <label for="model-type">Model</label><br>
    <select class="inputfield3" id="model-type" name="model-type">
      <option value="dalle">DALL-E</option>
      <option value="midjourney">Midjourney</option>
      <option value="lexica">Lexica</option>
      <option value="stablediffusion">Stable Diffusion</option>
    </select><br><br>

    <label for="image-upload">Image</label>
    <input class="inputfield4" type="file" id="image-upload" name="image-upload" accept="image/*" required><br><br>


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