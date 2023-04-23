<?php
include_once("../inc/bootstrap.php");

$message = "";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: ../php/login.php');
  exit;
}

$email = $_SESSION["email"];

try {
  $conn = Db::getInstance();
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  $message = "Try again later: " . $e->getMessage();
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["title"];
  $description = $_POST["description"];
  $model = $_POST["model-type"];
  $price = $_POST["price"];
  $prompt = $_POST["prompt"];

  // Handle uploaded file
  $file_name = $_FILES["image-upload"]["name"];
  $file_temp_name = $_FILES["image-upload"]["tmp_name"];
  $file_size = $_FILES["image-upload"]["size"];
  $file_error = $_FILES["image-upload"]["error"];

  if ($file_error !== UPLOAD_ERR_OK) {
    $message = "Upload failed with error code $file_error.";
    exit;
  }

  // Check file size
  if ($file_size > 1000000) {
    header('Location: ../php/fail.php');
    exit;
  }

  // Check file name for invalid characters
  if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]{3,4}$/', $file_name)) {
    header('Location: ../php/fail.php');
    exit;
  }

  // Save uploaded file to disk
  $uploads_dir = "../media/";
  $file_path = $uploads_dir . $file_name;
  if (!move_uploaded_file($file_temp_name, $file_path)) {
    $message = "Failed to move uploaded file.";
    exit;
  }

  $categories = array("Animals", "3D", "Space", "Game", "Car", "Nature", "Portrait", "Anime", "Interior", "Realistic", "Geek", "Building");
  $selected_categories = array();
  foreach ($categories as $category) {
    if (isset($_POST["categories"]) && in_array($category, $_POST["categories"])) {
      $selected_categories[] = $category;
    }
  }
  $categories_str = implode(", ", $selected_categories);

  $tags = array();
  if (isset($_POST["tags"])) {
    foreach ($_POST["tags"] as $tag) {
      $tags[] = $tag;
    }
  }
  $tags_str = implode(", ", $tags);

  // Insert data into database, including image file name
  $query = $conn->prepare("INSERT INTO prompts (name, user, description, model, pictures, characteristics, price, prompt, tags) VALUES (:name, :email, :description, :model, :pictures, :categories, :price, :prompt, :tags)");
  $query->bindValue(":name", $name);
  $query->bindValue(":email", $email);
  $query->bindValue(":description", $description);
  $query->bindValue(":model", $model);
  $query->bindValue(":pictures", $file_name);
  $query->bindValue(":categories", implode(", ", $selected_categories));
  $query->bindValue(":price", $price);
  $query->bindValue(":prompt", $prompt);
  $query->bindValue(":tags", implode(", ", $tags));
  $query->execute();

  header('Location: ../php/succes.php');
}

// Retrieve data from database, including image file name
$query = $conn->prepare("SELECT * FROM prompts WHERE user = :email");
$query->bindValue(":email", $email);
$query->execute();
$prompts = $query->fetchAll(PDO::FETCH_ASSOC);

$response = array('success' => true);
echo json_encode($response);
